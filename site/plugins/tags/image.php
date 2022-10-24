<?php

Kirby::plugin("tags/image", [
    "tags" => [
        "image" => [
            "attr" => [
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
            ],
            "html" => function($tag) {
                $url     = $tag->attr("image");
                $width   = $tag->attr("width");
                $height  = $tag->attr("height");
                $alt     = $tag->attr("alt");
                $title   = $tag->attr("title");
                $link    = $tag->attr("link");
                $caption = $tag->attr("caption");
                // try to fetch file in following order: 1. search on current page  2. search on all pages  3. search on site
                $file    = $tag->file($url) ?: site()->index(true)->files()->findBy("url", $url) ?: site()->files()->findBy("url", $url);

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

                    return html::a(url($href), [$image], [
                        "rel"    => $tag->attr("rel"),
                        "class"  => $tag->attr("linkclass"),
                        "title"  => $tag->attr("title"),
                        "target" => $tag->target()
                    ]);

                };

                if(option("kirbytext.image.figure", true) or !empty($caption)) {
                    // if file wasn't found return simple image else build responsive image
                    if(!$file) {
                        $image = $_link(Html::img($url, ["alt" => $alt]));
                    } else {
                        $image = $_link($file->genResponsiveImage($tag->attr("imgclass", "img-fluid"), $width, $height, $url, $alt, $title));

                        // if image file was found add img-wrapper to avoid jumpy website when images get loaded
                        $image_wrapper = Html::tag(
                            "div",
                            [
                                $image,
                            ],
                            [
                                "class" => "img-wrapper",
                                "style" => "padding-bottom: " . number_format($file->height() / $file->width() * 100, 10, '.', '') . "%"
                            ]
                        );
                    }

                    $figure = Html::figure(
                        [
                            isset($image_wrapper) ? $image_wrapper : $image,
                            !empty($caption)      ? "<figcaption class=\"figure-caption d-flex mt-1\"><div class=\"mr-2\">&#11025;</div>" . escape::html($caption) . "</figcaption>" : null,
                        ],
                        null,
                        [
                            "class" => $tag->attr("class", "w-100")
                        ]
                    );

                    // get necessary params to determine if image is first, last or between in image row
                    $imagesPerRow = option("image.images_per_row");
                    $imageList    = $tag->parent()->getCurrentImageList();
                    $imageCount   = count($imageList);
                    $currentID    = $tag->parent()->getCurrentImageTagID();

                    // get number of images of same size directly occurring before current image without break in text
                    $previousImageCount = 0;
                    for($i = $currentID - 1; $i >= 0; $i--) {
                        if($imageList[$i]["size"] !== $tag->attr("size") || $imageList[$i]["endOfImgRow"]) break;
                        $previousImageCount++;
                    }

                    // if image size is defined in imagesPerRow get it's to position according class else class is the size defined
                    // in the tag or default if no size is defined
                    if(array_key_exists($tag->attr("size"), $imagesPerRow)) {
                        // determine if image is the first or last image or inbetween
                        $size             = "";
                        $imageCountPerRow = $imagesPerRow[$tag->attr("size")];

                        if($previousImageCount == 0 || $previousImageCount % $imageCountPerRow == 0) {
                            $size = "first";
                        } else if(($previousImageCount + 1) % $imageCountPerRow == 0) {
                            $size = "last";
                        } else {
                            $size = "between";
                        }

                        // mark image if it's position is an even number for better layout on smaller screens
                        if(($previousImageCount % $imageCountPerRow) % 2 == 0) {
                            $size .= " even";
                        }
                    } else {
                        $size = $tag->attr("size", "default");
                    }

                    // generate html container element which wraps around image
                    $html = Html::tag(
                        "div",
                        [
                            $figure,
                        ],
                        [
                            "class" => "image " . $size
                        ]
                    );

                    // if there's no image of same size following this one and this isn't the last image completing the row add as many
                    // placeholders as needed to fill row
                    $isRowFinished = $currentID < $imageCount - 1 && ($imageList[$currentID + 1]["size"] !== $tag->attr("size") || $imageList[$currentID]["endOfImgRow"]);
                    if(array_key_exists($tag->attr("size"), $imagesPerRow) && $size !== "last" && ($currentID == $imageCount - 1 || $isRowFinished)) {
                        // calculate the number of missing images to complete the row and add placeholder images accordingly
                        if($previousImageCount == 0) {
                            $missingImgCount = $imagesPerRow[$tag->attr("size")] - 1;
                        } else {
                            $missingImgCount = $imagesPerRow[$tag->attr("size")] - ($previousImageCount + 1) % $imagesPerRow[$tag->attr("size")];
                        }

                        for($i = $missingImgCount; $i > 0; $i--) {
                            $size = $i > 1 ? "between" : "last";

                            $placeholder = Html::tag(
                                "div",
                                [
                                    "<!--PLACEHOLDER IMAGE-->",
                                ],
                                [
                                    "class" => "image " . $size
                                ]
                            );

                            // concatenate placeholder to html string
                            $html .= "\n" . $placeholder;
                        }
                    }

                    // reset page values
                    if($currentID == $imageCount - 1 || $imageCount == 0) {
                        $tag->parent()->resetCurrentImageTagID();
                        $tag->parent()->resetCurrentImageList();
                    }

                    return $html;
                } else {
                    $class = trim($tag->attr("class") . " " . ($tag->attr("imgclass", "img-fluid")));

                    // if file wasn't found return simple image else build responsive image
                    if(!$file) {
                        $image = $_link(html::img($url, ["alt" => $alt]));
                    } else {
                        $image = $_link($file->genResponsiveImage($class, $width, $height, $url, $alt, $title));
                    }

                    return $image;
                }

            }
        ]
    ]
]);