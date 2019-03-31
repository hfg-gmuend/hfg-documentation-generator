<?php

file::$methods["genResponsiveImage"] = function($file, $class = null, $width = null, $height = null, $url = null, $alt = null, $title = null) {
    // if file isn't an image throw error
    if($file->type() !== "image") throw new Exception("genResponsiveImage is only available for images!");

    // create all image sizes if they don't exist and generate srcset and sizes attributes
    $definedSizes = c::get("image.widths");
    $srcset       = "";
    $sizes        = "";


    // only build responsive image if file and sizes are defined
    if($file && $definedSizes) {
        // resize base image if it exceeds file size limit
        $maxFileSize      = c::get("image.max_file_size");
        $targetImgWidth   = max(c::get("image.widths"));
        $targetImgQuality = thumb::$defaults["quality"];
        $tolerance        = $maxFileSize * 0.05;
        $maxResizes       = 10;
        $file             = $file->resizeToFileSize($maxFileSize, $targetImgWidth, $targetImgQuality, $tolerance, $maxResizes);

        // resize image according to all defined sizes and generate srcset and sizes attributes
        foreach($definedSizes as $i => $size) {
            if($i != 0) {
                $srcset .= ", ";
                $sizes .= ", ";
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



    return html::img($url, array(
        "width"  => $width,
        "height" => $height,
        "class"  => $class,
        "title"  => $title,
        "alt"    => $alt,
        "srcset" => $srcset,
        "sizes"  => $sizes
    ));
};

file::$methods["resizeToFileSize"] = function($file, $maxFileSize, $targetImgWidth, $targetImgQuality, $tolerance, $maxResizes) {
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

    // rename resizedImage to random string so reference to file is lost and resizedImage doesn't get
    // deleted with file later on
    $tempPath = substr_replace(
                $resizedImage->root(),
                generateRandomString() . "." . $resizedImage->extension(),
                strrpos($resizedImage->root(), $resizedImage->filename()),
                strlen($resizedImage->filename())
            );
    $resizedImage->move($tempPath);

    // delete base file/image
    $file->delete();

    // move resizedImage to path of base file/image
    $resizedImage->move($originalPath);

    // reacquire resizedImage so it is of type file instead of asset when it gets returned (necessary for later use)
    $resizedImage = $page->image($fileName);

    // return resizedImage
    return $resizedImage;
};


// generates random string
// https://stackoverflow.com/questions/4356289/php-random-string-generator
function generateRandomString($length = 10) {
    $characters       = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $charactersLength = strlen($characters);
    $randomString     = "";

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}