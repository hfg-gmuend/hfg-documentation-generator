<?php

return [
    //------------ Enable Debug Mode ------------
    "debug" => true,

    //------------ Disable Cache ------------
    "cache" => false,

    //------------ Scss Plugin ------------
    "scssCompile" => true,
    "scssNestedCheck" => true,
    "d4l" => [
        "static_site_generator" => [
            "endpoint" => "export-static",
            "output_folder" => "./static",
            "base_url" => "/",
        ],
    ]
];
