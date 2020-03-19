<?php

// create image thumbs for responsiveness (as defined in config) and also resizes the uploaded
// base image if it exceeds the file size limit defined in the config
$resizeImage = function($file) {
    // only resize if resize_on_upload is activated and file is image
    if(option("image.resize_on_upload") !== true || $file->type() !== "image") return $file;

    try {
        // build responsive image so thumbs get generated
        $fileSize = $file->size();

        $file->genResponsiveImage();
    } catch (Exception $e) {
        throw new Exception($e);
    }
};


$checkUserPermission = function($pageOrFile) {
    // get page object
    $page = $pageOrFile instanceof Kirby\Cms\Page ? $pageOrFile : $pageOrFile->page();

    // throw error if currently logged in user has no permission for page
    if(!$page->hasPermission(kirby()->user())) throw new Exception("You have no permission to do this!");
};

//------------ Hooks ------------
Kirby::plugin("hooks/hooks", [
    "hooks" => [
        // autopublish all pages when they are created by default
        "page.create:after" => function($page) {
            try {
                $updatedPage = $page;

                // autopublish chapters
                if($page->intendedTemplate()->name() === "chapter") $updatedPage = $page->changeStatus("listed");

                return $updatedPage;
            } catch(Exception $e) {
                throw $e;
            }
        },
        // define all hooks where user permission should be checked
        "page.changeNum:before"      => $checkUserPermission,
        "page.changeSlug:before"     => $checkUserPermission,
        "page.changeStatus:before"   => $checkUserPermission,
        "page.changeTemplate:before" => $checkUserPermission,
        "page.changeTitle:before"    => $checkUserPermission,
        "page.create:before"         => function($page) use(&$checkUserPermission) {
            if($page->parent() && is_int($page->parent()->depth()) && $page->parent()->depth() >= option("max_page_depth")) throw new Exception("You cannot create pages this deep!");
            $checkUserPermission($page);
        },
        "page.delete:before"         => $checkUserPermission,
        "page.duplicate:before"      => $checkUserPermission,
        "page.update:before"         => $checkUserPermission,
        "file.changeName:before"     => $checkUserPermission,
        "file.changeSort:before"     => $checkUserPermission,
        "file.create:before"         => $checkUserPermission,
        "file.delete:before"         => $checkUserPermission,
        "file.replace:before"        => $checkUserPermission,
        "file.update:before"         => $checkUserPermission,

        "file.create:after"          => $resizeImage,
        "file.replace:after"         => $resizeImage
    ]
]);

