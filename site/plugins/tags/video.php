<?php

Kirby::plugin("tags/video", [
    "tags" => [
        "video" => [
            "attr" => [
                "class",
                "caption",
                "a-ratio"
            ],
            "html" => function($tag) {

                $url     = $tag->attr("video");
                $caption = $tag->attr("caption");
                $file    = $tag->file($url);

                // get filename without extension
                $videoName = $file ? $file->name() : $tag->attr("video");

                // filter all page videos by html supported MIME types and video name
                $supportedVideoMIMETypes = ["video/mp4", "video/webm", "video/ogg"];

                $filteredVideos = $tag->parent()->videos()->filter(function($video) use($supportedVideoMIMETypes, $videoName) {
                    return in_array($video->mime(), $supportedVideoMIMETypes) && $video->name() === $videoName;
                });

                // get possible fallback images by searching for images with matching name
                $filteredImages = $tag->parent()->images()->filter(function($image) use($videoName) {
                    return $image->name() === $videoName;
                });

                // function that generates all available source tags and optional fallback image and returns it as a string
                $generateVideoSources = function() use($filteredVideos, $filteredImages) {
                    $generateVideoSourcesStr = "";

                    foreach($filteredVideos as $key => $video) {
                        $generateVideoSourcesStr .= "<source src=\"" . $video->url() . "\" type=\"" . $video->mime() . "\"/>";
                    }

                    foreach($filteredImages as $key => $image) {
                        $generateVideoSourcesStr .= kirbytag(["image" => $image->url(), "alt" => $image->filename(), "title" => "Your browser does not support the <video> tag"]);
                    }

                    return $generateVideoSourcesStr;
                };

                if(!empty($caption)) {
                    $figcaption = "<figcaption class=\"figure-caption d-flex mt-1\"><div class=\"mr-2\">&#11025;</div>" . escape::html($caption) . "</figcaption>";
                } else {
                    $figcaption = "";
                }

                return "<figure class=\"w-100\">
                            <div class=\"embed-responsive embed-responsive-" . $tag->attr("a-ratio", "16by9") . "\">
                                <video class=\"" . $tag->attr("class", option("kirbytext.video.class", "video")) . "\" controls>"
                                    . $generateVideoSources() .
                                "</video>
                            </div>"
                            . $figcaption .
                        "</figure>";
            }
        ]
    ]
]);

