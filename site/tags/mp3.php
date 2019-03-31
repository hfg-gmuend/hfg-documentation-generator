<?php

kirbytext::$tags["mp3"] = array(
    "attr" => array(
        "class"
    ),
    "html" => function($tag) {
        $url = $tag->attr("mp3");
        $file = $tag->file($url);
        $class = $tag->attr("class", "d-block w-100 mb-3");
        $url = $file ? $file->url() : url($url); // use the file url if available or otherwise the given url

        $audioEl = new Brick("audio");
            $audioEl->addClass($class);
            $audioEl->attr("preload", "auto");
            $audioEl->attr("controls", "controls");
            $audioEl->append("<source src=\"" . $url . "\" type=\"audio/mp3\">");

        return $audioEl;
    }
);
