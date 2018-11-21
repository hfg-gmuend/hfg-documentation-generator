<?php

//------------ Code Accordion Indexing ------------
$code_accordion_count = -1;

// returns current code accordion index
page::$methods["getUniqueCodeAccordionID"] = function() use(&$code_accordion_count) {
    $code_accordion_count++;
    return $code_accordion_count;
};



//------------ Half Sized Image Layout Algorithm Utils ------------
$image_tag_count = -1;

// returns current image tag index. If reset is set image_tag_count gets reset back to -1
page::$methods["getCurrentImageTagID"] = function($page, $reset = false) use(&$image_tag_count) {
    if($reset != true) $image_tag_count++;
    else $image_tag_count = -1;

    return $image_tag_count;
};


$previousImageSize = "";

// returns previous image size
page::$methods["getPreviousImageSize"] = function($page) use(&$previousImageSize) {
    return $previousImageSize;
};

// sets previous image size and returns it
page::$methods["setPreviousImageSize"] = function($page, $newImageSize) use(&$previousImageSize) {
    $previousImageSize = $newImageSize;

    return $previousImageSize;
};