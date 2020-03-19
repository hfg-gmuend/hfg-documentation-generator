<?php

Kirby::plugin("tags/p5", [
    "tags" => [
        "p5" => [
            "attr" => [
                "caption"
            ],
            "html" => function($tag) {

                $url     = $tag->attr("p5");
                $file    = $tag->file($url);
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

                // publish file so it is copied to public media directory
                if($file && $file->exists()) $file->publish();

                // create figure
                $figure = Html::tag(
                    "figure",
                    [
                        "<div class=\"embed-responsive\">
                            <iframe class=\"p5 embed-responsive-item\" onload='" . $onload_callback . "' src=\"" . site()->url() . "/generateP5iframe?url=" . $url . "\"></iframe>
                        </div>",
                        !empty($caption) ? "<figcaption class=\"figure-caption d-flex mt-1\"><div class=\"mr-2\">&#11025;</div>" . escape::html($caption) . "</figcaption>" : null
                    ],
                    [
                        "class" => $tag->attr("class", "w-100")
                    ]
                );

                return $figure;
            }
        ]
    ]
]);