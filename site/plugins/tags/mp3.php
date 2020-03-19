<?php

Kirby::plugin("tags/mp3", [
    "tags" => [
        "mp3" => [
            "attr" => [
                "file"
            ],
            "html" => function($tag) {
                $url = $tag->attr("mp3");
                $file = $tag->file($url);
                $class = $tag->attr("class", "d-block w-100 mb-3");
                $url = $file ? $file->url() : url($url); // use the file url if available or otherwise the given url

                $audioEl = Html::tag(
                    "audio",
                    [
                        "<source src=\"" . $url . "\" type=\"audio/mp3\">",
                    ],
                    [
                        "class"    => $class,
                        "preload"  => "auto",
                        "controls" => "controls",
                    ]
                );

                return $audioEl;
            }
        ]
    ]
]);