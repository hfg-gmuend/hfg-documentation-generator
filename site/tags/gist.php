<?php

kirbytext::$tags["gist"] = array(
    "attr" => array(
        "file"
    ),
    "html" => function($tag) {
        return "<div class=\"embed-gist\">" . embed::gist($tag->attr("gist"), $tag->attr("file")) . "</div>";
    }
);