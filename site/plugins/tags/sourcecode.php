<?php

Kirby::plugin("tags/sourcecode", [
    "tags" => [
        "sourcecode" => [
            "html" => function($tag) {

                $file = $tag->file($tag->attr("sourcecode"));

                // get sourcecode if file exists and doesn't exceed max file size
                $sourcecode = "ERROR 404: File Not Found";

                if($file) {
                    if($file->size() <= option("sourcecode.max_file_size")) {
                        $sourcecode = $file->read();
                    } else {
                        $sourcecode = "ERROR: file size exceeds limit of " . option("sourcecode.max_file_size") . "byte";
                    }
                }

                return $sourcecode;
            }
        ]
    ]
]);