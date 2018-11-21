<?php
// generate js file which contains kirby file type definitions to be able to use them in js files later on
$TYPES_SRC = __DIR__ . "/assets/js/types.js";

$content = "const types = " . a::json(f::types());
if(f::read($TYPES_SRC) !== $content) f::write($TYPES_SRC, $content);


// generate index.js which wraps all contents of defined js files into one file and inside a jquery.onready function
$INDEX_SRC = __DIR__ . "/assets/js/index.js";

$js_files = [
    "types.js",
    "draggablefiles.js",
    "previewfiles.js",
    "observer.js"
];

// check if index file needs to be updated
$update = false;

// first check if index.js exists if not set update flag
if(f::exists($INDEX_SRC)) {
    // check if this file is newer than index.js, if it is update index.js else check if one of the included files is newer
    if(filemtime(__FILE__) > filemtime($INDEX_SRC)) {
        $update = true;
    } else {
        foreach($js_files as $file) {
            // if one of the files is newer than index.js set update flag and break out of loop
            if(f::exists(__DIR__ . "/assets/js/" . $file) && filemtime(__DIR__ . "/assets/js/" . $file) > filemtime($INDEX_SRC)) {
                $update = true;
                break;
            }
        }
    }
} else {
    $update = true;
}

// generate index.js if needed
if($update) {
    $content = "$(document).ready(function() {\n"; // open jquery on ready wrapper

    foreach($js_files as $file) {
        // add file contents to content if it exists
        if(f::exists(__DIR__ . "/assets/js/" . $file)) $content .= file_get_contents(__DIR__ . "/assets/js/" . $file) . "\n\n";
    }

    $content .= "});"; // close jquery on ready wrapper

    f::write($INDEX_SRC, $content);
}


/**
 * Pseudo-field that just includes javascript files, that kirby automatically builds into main plugin js.
 * So included js code will be globally available in panel and this field doesn't have to be manually added to blueprint.
 */
class ExtensionsField extends BaseField {
    static public $assets = [
        "js" => ["index.js"]
    ];
}
