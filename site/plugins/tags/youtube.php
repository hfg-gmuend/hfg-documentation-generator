<?php

Kirby::plugin("tags/youtube", [
    "tags" => [
        "youtube" => [
            "html" => function($tag) {

                $caption = $tag->attr("caption");

                if(!empty($caption)) {
                    $figcaption = "<figcaption class=\"figure-caption d-flex mt-1\"><div class=\"mr-2\">&#11025;</div>" . escape::html($caption) . "</figcaption>";
                } else {
                    $figcaption = "";
                }

                return "<figure class=\"w-100\"><div class=\"embed-responsive embed-responsive-" . $tag->attr("a-ratio", "16by9") . "\"><div class=\"" . $tag->attr("class", option("kirbytext.video.class", "video")) . "\">" . Html::youtube($tag->attr("youtube"), [
                    "width"   => $tag->attr("width",  option("kirbytext.video.width")),
                    "height"  => $tag->attr("height", option("kirbytext.video.height")),
                    "options" => option("kirbytext.video.youtube.options")
                ]) . "</div></div>" . $figcaption . "</figure>";

            }
        ]
    ]
]);