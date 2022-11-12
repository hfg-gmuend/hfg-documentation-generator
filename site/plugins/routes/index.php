<?php

namespace D4L;

use Kirby\Cms\App as Kirby;

//------------ Routes ------------
Kirby::plugin("routes/routes", [
    "routes" => [
        [
            "pattern" => "/generateP5iframe",
            "action" => function () {
                return "hallo";
                return snippet("p5-iframe-template", ["url" => get("url")], true);
            },
        ],
        [
            "pattern" => "/export-static",
            "action" => function () {

                $kirby = kirby();

                $outputFolder = './ssgexport';
                $baseUrl = $kirby->option('d4l.static_site_generator.base_url', '/');
                $preserve = [];
                $pages = $kirby->site()->index();

                $staticSiteGenerator = new StaticSiteGenerator($kirby, null, $pages);
                $list = $staticSiteGenerator->generate($outputFolder, $baseUrl, $preserve);
                $count = count($list);
                return ['success' => true, 'files' => $list, 'message' => "$count files generated / copied"];
            }
        ],
        [
            "pattern" => "^(?!staticbuilder)(:any)",
            "action" => function ($uri) {
                $page = kirby()->page($uri);

                // redirect to first chapter of documentation if baseUrl/documentationName/ is called else don't redirect
                if ($uri !== "overview" && ($page && $page->children()->listed()->first())) {
                    go($page->children()->listed()->first());
                } else {
                    return $page;
                }
            },
        ]
    ]
]);
