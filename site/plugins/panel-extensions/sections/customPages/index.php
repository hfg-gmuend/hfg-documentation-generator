<?php

// NOTE: plugin structure inspired by https://github.com/rasteiner/k3-pagesdisplay-section
Kirby::plugin("panel-extensions/customPages-section", [
    "sections" => [
        "customPages" => require __DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "customPages.php"
    ]
]);