<?php

//------------ Routes ------------
Kirby::plugin("routes/routes", [
    "routes" => [
        [
            "pattern" => "/generateP5iframe",
            "action"  => function() {
                return snippet("p5-iframe-template", ["url" => get("url")], true);
            }
        ],
        [
            "pattern" => "^(?!staticbuilder)(:any)",
            "action"  => function($uri) {
                $page = kirby()->page($uri);

                // redirect to first chapter of documentation if baseUrl/documentationName/ is called else don't redirect
                if($uri !== "overview" && ($page && $page->children()->listed()->first())) {
                    go($page->children()->listed()->first());
                } else {
                    return $page;
                }
            }
        ]
    ]
]);