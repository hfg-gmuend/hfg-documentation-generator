<?php

kirbytext::$tags["p5"] = array(
    "attr" => array(
      "caption"
    ),
    "html" => function($tag) {

        $url = $tag->attr("p5");
        $file = $tag->file($url);
        $caption = $tag->attr("caption");

        // use the file url if available and otherwise the given url
        $url = $file ? $file->url() : url($url);

        // javascript code that runs when iframe has finished loading
        $onload_callback = "if(typeof resizeP5iframe === \"function\") {
                                resizeP5iframe(this);
                            } else {
                                if(window.loadedP5iframes === undefined) window.loadedP5iframes = [];
                                window.loadedP5iframes.push(this);
                            }";

        $figure = new Brick("figure");
        $figure->addClass($tag->attr("class", "w-100"));
        $figure->append("<div class=\"embed-responsive\">
                            <iframe class=\"p5 embed-responsive-item\" onload='" . $onload_callback . "' srcdoc='" . snippet("p5-iframe-template", ["url" => $url], true) . "'></iframe>
                        </div>");
        if(!empty($caption)) {
            $figure->append("<figcaption class=\"figure-caption d-flex mt-1\"><div class=\"mr-2\">&#11025;</div>" . escape::html($caption) . "</figcaption>");
        }

        return $figure;
    }
);