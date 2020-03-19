<?php

// TODO: use https://getkirby.com/docs/reference/objects/file/srcset
$genResponsiveImage = function($class = null, $width = null, $height = null, $url = null, $alt = null, $title = null) {
    $file = $this;

    // if file isn't an image throw error
    if($file->type() !== "image") throw new Exception("genResponsiveImage is only available for images!");

    // create all image sizes if they don't exist and generate srcset and sizes attributes
    $definedSizes = option("image.widths");
    $srcset       = "";
    $sizes        = "";

    // only build responsive image if file and sizes are defined
    if($file && $definedSizes) {

        // resize base image if it exceeds file size limit
        $maxFileSize      = option("image.max_file_size");
        $targetImgWidth   = max(option("image.widths"));
        $targetImgQuality = option("thumbs.quality");
        $tolerance        = $maxFileSize * 0.05;
        $maxResizes       = 10;
        $file             = $file->resizeToFileSize($maxFileSize, $targetImgWidth, $targetImgQuality, $tolerance, $maxResizes);

        // resize image according to all defined sizes and generate srcset and sizes attributes
        foreach($definedSizes as $i => $size) {
            if($i != 0) {
                $srcset .= ", ";
                $sizes  .= ", ";
            }

            // resize image if size is smaller than image width else add original image as last responsive image source
            if($size < $file->width()) {
                // call resize function which only resizes image if there isn't already a resized version and get url
                $currentUrl = $file->resize($size)->url();

                // add url and image query to srcset and sizes
                $srcset .= $currentUrl . " " . $size . "w";
                $sizes  .= "(max-width: " . $size . "px) " . $size . "px";
            } else {
                // add url and image query to srcset and sizes
                $srcset .= $url . " " . $file->width() . "w";
                $sizes  .= $file->width() . "px";
                break;
            }
        }

        // if image is wider than maximum defined size also add original image size to srcset and sizes
        if(max($definedSizes) < $file->width()) {
            $srcset .= ", " . $url . " " . $file->width() . "w";
            $sizes  .= ", " . $file->width() . "px";
        }

        // change image url to the url of the smallest image
        $url = $file->resize(min($definedSizes))->url();
    }



    return html::img($url, [
        "width"  => $width,
        "height" => $height,
        "class"  => $class,
        "title"  => $title,
        "alt"    => $alt,
        "srcset" => $srcset,
        "sizes"  => $sizes
    ]);
};

$resizeToFileSize = function($maxFileSize, $targetImgWidth, $targetImgQuality, $tolerance, $maxResizes) {
    $file = $this;

    // if file isn't an image throw error
    if($file->type() !== "image") throw new Exception("resizeToFileSize is only available for images!");

    // init params for resizing
    $currResizeFactor = $maxFileSize / $file->size();
    $resizedImage     = $file;
    $resizeCtr        = 0;
    $prevFileSize     = $file->size();
    $prevResizeFactor = 1;

    // save important file info for use after file has been deleted
    $page         = $file->page();
    $fileName     = $file->filename();
    $originalPath = $file->root();

    // check if image needs to be resized
    if(abs($file->size() - $maxFileSize) < $tolerance || $file->size() < $maxFileSize) return $file;

    // resize image until it's inside tolerance range or maxResizes is reached
    while(abs($resizedImage->size() - $maxFileSize) > $tolerance && $resizeCtr < $maxResizes) {
        // delete previous resizedImage
        if($resizeCtr > 0) $resizedImage->delete();

        // if resize factor overshot above 1 increase it slowly to 1
        if($currResizeFactor > 1) $currResizeFactor = $prevResizeFactor + (1 - $prevResizeFactor) * 0.5;

        // resize image based on current ResizeFactor
        $resizedImage = $file->resize($file->width() * $currResizeFactor, null, $targetImgQuality);
        // throw error if resizing failed
        if($resizedImage->width() > $file->width() * $currResizeFactor) throw new Exception("Image failed to resize. Is proper thumb driver installed?");

        // if resizing didn't change the fileSize break out of loop to avoid later division by zero
        // fileSize should be anyway close to the tolerance range
        if($resizedImage->size() == $prevFileSize) break;

        // calculate new currResizeFactor to reach desired file size with linear approximation
        $currentAbsFileSizeChange                   = abs($prevFileSize - $resizedImage->size());
        $currentAbsFactorChange                     = abs($prevResizeFactor - $currResizeFactor);
        $currentResizeFactorChangePerFileSizeChange = $currentAbsFactorChange / $currentAbsFileSizeChange;
        $missingFileSizeChange                      = $resizedImage->size() - $maxFileSize;

        // set previous values
        $prevFileSize     = $resizedImage->size();
        $prevResizeFactor = $currResizeFactor;

        // set new resizeFactor
        $currResizeFactor -= $missingFileSizeChange * $currentResizeFactorChangePerFileSizeChange;

        // increase resize counter
        ++$resizeCtr;
    }

    $resizedImage->move($originalPath, true);
    // remove generated thumbs and jobs from media directory
    $file->unpublish();

    return $resizedImage->original();
};

// combine functions to plugin
Kirby::plugin("file-extensions/file-extensions", [
    "fileMethods" => [
        "genResponsiveImage" => $genResponsiveImage,
        "resizeToFileSize"   => $resizeToFileSize,
    ]
]);