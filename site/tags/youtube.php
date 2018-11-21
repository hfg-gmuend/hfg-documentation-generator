<?php

kirbytext::$tags["youtube"] = array(
    "attr" => array(
        "width",
        "height",
        "class",
        "caption",
        "a-ratio"
    ),
    "html" => function($tag) {

        $caption = $tag->attr("caption");

        if(!empty($caption)) {
            $figcaption = "<figcaption class=\"figure-caption d-flex mt-1\"><div class=\"mr-2\">&#11025;</div>" . escape::html($caption) . "</figcaption>";
        } else {
            $figcaption = "";
        }

        return "<figure class=\"w-100\"><div class=\"embed-responsive embed-responsive-" . $tag->attr("a-ratio", "16by9") . "\"><div class=\"" . $tag->attr("class", kirby()->option("kirbytext.video.class", "video")) . "\">" . embed::youtube($tag->attr("youtube"), array(
            "width"   => $tag->attr("width",  kirby()->option("kirbytext.video.width")),
            "height"  => $tag->attr("height", kirby()->option("kirbytext.video.height")),
            "options" => kirby()->option("kirbytext.video.youtube.options")
        )) . "</div></div>" . $figcaption . "</figure>";

    }
);