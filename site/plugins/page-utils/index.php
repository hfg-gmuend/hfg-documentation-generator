<?php

//------------ Code Accordion Indexing ------------
$code_accordion_count = -1;

// returns current code accordion index
$getUniqueCodeAccordionID = function() use(&$code_accordion_count) {
    $code_accordion_count++;
    return $code_accordion_count;
};



//------------ Small Sized Image Layout Algorithm Utils ------------
$imageTagCount = -1;

// returns current image tag index
$getCurrentImageTagID = function() use(&$imageTagCount) {
    $imageTagCount++;

    return $imageTagCount;
};

// resets imageTagCount back to -1
$resetCurrentImageTagID = function() use(&$imageTagCount) {
    $imageTagCount = -1;
};


$imageList = null;

// returns current image list and generates it if it wasn't already. The image list contains
// all image sizes in the page text and a flag if image is directly followed by
// another image of the same size
$getCurrentImageList = function() use(&$imageList) {
    $page = $this;

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
$resetCurrentImageList = function() use(&$imageList) {
    $imageList = null;
};



// does same as $page->nextVisible(), but works with staticbuilder
$nextListedStaticFix = function() {
    $page = $this;

    if(!defined("STATIC_BUILD")) return $page->nextListed();

    $pageNum  = $page->num();
    $siblings = $page->siblings(false)->listed();

    return $siblings->findBy("num", $pageNum + 1);
};

// returns true if user has permission to work with page based on authorship else false
$hasPermission = function($user) {
    $page = $this;

    // if no user was passed return false
    if(!isset($user)) return false;

    // if user is admin return true because admin has all permissions
    if($user->isAdmin()) return true;

    // return false if users shouldn't be able to edit their documentations
    if(kirby()->page("overview")->allowEdit()->toBool() === false) return false;

    // get to page according documentation
    $documentation = $page->intendedTemplate()->name() === "documentation" ? $page : $page->parents()->filterBy("intendedTemplate", "documentation")->first();

    // return true if a documentation was found and the user is one of its authors
    if($documentation !== null && !$documentation->authors()->isEmpty() && $documentation->authors()->toUsers()->has($user)) {
        return true;
    }

    return false;
};



// combine functions to plugin
Kirby::plugin("page-utils/page-utils", [
    "pageMethods" => [
        "getUniqueCodeAccordionID" => $getUniqueCodeAccordionID,
        "getCurrentImageTagID"     => $getCurrentImageTagID,
        "resetCurrentImageTagID"   => $resetCurrentImageTagID,
        "getCurrentImageList"      => $getCurrentImageList,
        "resetCurrentImageList"    => $resetCurrentImageList,
        "nextListedStaticFix"      => $nextListedStaticFix,
        "hasPermission"            => $hasPermission
    ]
]);