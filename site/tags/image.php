<?php

// overriding kirbys image tag
kirbytext::$tags["image"] = array(
    "attr" => array(
        "width",
        "height",
        "alt",
        "text",
        "title",
        "class",
        "imgclass",
        "linkclass",
        "caption",
        "link",
        "target",
        "popup",
        "rel",
        "size"
    ),
    "html" => function($tag) {
        $url     = $tag->attr("image");
        $width   = $tag->attr("width");
        $height  = $tag->attr("height");
        $alt     = $tag->attr("alt");
        $title   = $tag->attr("title");
        $link    = $tag->attr("link");
        $caption = $tag->attr("caption");
        // try to fetch file in following order: 1. search on current page  2. search on all pages  3. search on site
        $file    = $tag->file($url) ?: site()->index()->files()->findBy("url", $url) ?: site()->files()->findBy("url", $url);

        // get the right size based on image position and size in text
        $getSize = function($setSize) use($tag) {
            // only run algorithm for half sized images
            if($setSize === "half") {
                // get text of current page with all image tags
                $textField = $tag->page()->text();

                $imageRegex = "\(image:[^\)]*?size:\s*?half[^\)]*?\)";

                // get all half sized images in page text
                preg_match_all("/" . $imageRegex . "/", $textField, $matches, PREG_OFFSET_CAPTURE);

                // if there is more than one half sized image run algorithm else set image size to half
                $tagsCount = count($matches[0]);

                if($tagsCount > 1) {

                    // get current image tag index
                    $currentID = $tag->page()->getCurrentImageTagID();

                    // get previous image size
                    $previousSize = $tag->page()->getPreviousImageSize();

                    // if previousSize is half_first then this has to be half_second if not check if this has to be half_first or half
                    if($previousSize === "half_first") {
                        $size = $tag->page()->setPreviousImageSize("half_second");
                    } else {
                        // if image is directly followed by another half sized image it gets the half_first class else the half class
                        if(preg_match("/^" . $imageRegex . "\s*?" . $imageRegex . "/", substr($textField, $matches[0][$currentID][1])) == 1) {
                            $size = $tag->page()->setPreviousImageSize("half_first");
                        } else {
                            $size = $tag->page()->setPreviousImageSize("half");
                        }
                    }

                    // reset current image tag id and previous image size saved in page object if current image tag is also the last one in text
                    if($currentID == $tagsCount - 1) {
                        $tag->page()->getCurrentImageTagID(true);
                        $tag->page()->setPreviousImageSize("");
                    }

                    return $size;
                } else {
                    return "half";
                }
            }

            return $setSize;
        };

        // get size. If no size attribute is set the image gets the default class
        $size = $getSize($tag->attr("size", "default"));

        // use the file url if available and otherwise the given url
        $url = $file ? $file->url() : url($url);

        // alt is just an alternative for text
        if($text = $tag->attr("text")) $alt = $text;

        // try to get the title from the image object and use it as alt text
        if($file) {

            if(empty($alt) and $file->alt() != "") {
                $alt = $file->alt();
            }

            if(empty($title) and $file->title() != "") {
                $title = $file->title();
            }

        }

        // at least some accessibility for the image
        if(empty($alt)) $alt = " ";

        // link builder
        $_link = function($image) use($tag, $url, $link, $file) {

            if(empty($link)) return $image;

            // build the href for the link
            if($link == "self") {
                $href = $url;
            } else if($file and $link == $file->filename()) {
                $href = $file->url();
            } else if($tag->file($link)) {
                $href = $tag->file($link)->url();
            } else {
                $href = $link;
            }

            return html::a(url($href), $image, array(
                "rel"    => $tag->attr("rel"),
                "class"  => $tag->attr("linkclass"),
                "title"  => $tag->attr("title"),
                "target" => $tag->target()
            ));

        };

        if(kirby()->option("kirbytext.image.figure") or !empty($caption)) {
            // if file wasn't found return simple image else build responsive image
            if(!$file) {
                $image = $_link(html::img($url, array("alt" => $alt)));
            } else {
                $image = $_link($file->genResponsiveImage($tag->attr("imgclass", "img-fluid"), $width, $height, $url, $alt, $title));

                // if image file was found add img-wrapper to avoid jumpy website when images get loaded
                $image_wrapper = new Brick("div");
                    $image_wrapper->addClass("img-wrapper");
                    // add padding based on aspect ratio
                    $image_wrapper->attr("style", "padding-bottom: " . $file->height() / $file->width() * 100 . "%");
                    $image_wrapper->append($image);
            }

            $figure = new Brick("figure");
                $figure->addClass(($tag->attr("class", "w-100")));
                if(isset($image_wrapper)) {
                    $figure->append($image_wrapper);
                } else {
                    $figure->append($image);
                }
                if(!empty($caption)) {
                    $figure->append("<figcaption class=\"figure-caption d-flex mt-1\"><div class=\"mr-2\">&#11025;</div>" . escape::html($caption) . "</figcaption>");
                }

            $container = new Brick("div");
                $container->addClass("image " . $size);
                $container->append($figure);

            return $container;
        } else {
            $class = trim($tag->attr("class") . " " . ($tag->attr("imgclass", "img-fluid")));

            // if file wasn't found return simple image else build responsive image
            if(!$file) {
                $image = $_link(html::img($url, array("alt" => $alt)));
            } else {
                $image = $_link($file->genResponsiveImage($class, $width, $height, $url, $alt, $title));
            }

            return $image;
        }

    }
);