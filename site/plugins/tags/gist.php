<?php

Kirby::plugin("tags/gist", [
    "tags" => [
        "gist" => [
            "attr" => [
                "file"
            ],
            "html" => function($tag) {
                return "<div class=\"embedded-gist\">" . Html::gist($tag->attr("gist"), $tag->attr("file")) . "</div>";
            }
        ]
    ]
]);