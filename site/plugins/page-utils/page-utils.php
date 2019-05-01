<?php

//------------ Code Accordion Indexing ------------
$code_accordion_count = -1;

// returns current code accordion index
page::$methods["getUniqueCodeAccordionID"] = function() use(&$code_accordion_count) {
    $code_accordion_count++;
    return $code_accordion_count;
};



//------------ Small Sized Image Layout Algorithm Utils ------------
$imageTagCount = -1;

// returns current image tag index
page::$methods["getCurrentImageTagID"] = function($page) use(&$imageTagCount) {
    $imageTagCount++;

    return $imageTagCount;
};

// resets imageTagCount back to -1
page::$methods["resetCurrentImageTagID"] = function($page) use(&$imageTagCount) {
    $imageTagCount = -1;
};


$imageList = null;

// returns current image list and generates it if it wasn't already. The image list contains
// all image sizes in the page text and a flag if image is directly followed by
// another image of the same size
page::$methods["getCurrentImageList"] = function($page) use(&$imageList) {
    // generate new imageList if it isn't currently defined
    if($imageList === null) {
        // fetch all image tags in page text
        $pageText = $page->text();

        preg_match_all("/\(image:.*?\)/", $pageText, $matches, PREG_OFFSET_CAPTURE);
        $images = $matches[0];

        // add sizes of all found images to imageList and flag if image is directly followed
        // by another image with same size
        $imageList = [];

        foreach($images as $image) {
            // fetch defined size in image tag. If no size was defined size = default
            preg_match("/size:\s*(?P<size>[^\s|\)]*)/", $image[0], $matches);
            $size = array_key_exists("size", $matches) ? $matches["size"] : "default";

            // check if image is directly followed by another image with same size
            $endOfImgRow = preg_match("/^\s*?\(image:.*?\)/", substr($pageText, $image[1] + strlen($image[0]))) == 0;

            // add size and endOfImgRow flag to imageList
            array_push($imageList, ["size" => $size, "endOfImgRow" => $endOfImgRow]);
        }
    }

    return $imageList;
};

// resets image list to null
page::$methods["resetCurrentImageList"] = function($page) use(&$imageList) {
    $imageList = null;
};



// does same as $page->nextVisible(), but works with staticbuilder
page::$methods["nextVisibleStaticFix"] = function($page) {
    if(!defined("STATIC_BUILD")) return $page->nextVisible();

    $pageNum  = $page->num();
    $siblings = $page->siblings(false)->visible();

    return $siblings->findBy("num", $pageNum + 1);
};
