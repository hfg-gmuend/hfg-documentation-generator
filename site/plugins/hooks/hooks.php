<?php

//------------ Panel Hooks ------------
// autopublish all pages when they are created by default and set author to current user
kirby()->hook("panel.page.create", function($page) {
    try {
        $page->sort("last");

        $page->update(array(
            "author" => site()->user()->username(),
        ));
    } catch(Exception $e) {
        echo $e->getMessage();
    }
});


// update url to match title when page title is updated
kirby()->hook("panel.page.update", function($page, $oldPage) {
    try {
        $newTitle = $page->title();
        $oldTitle = $oldPage->title();

        if ($oldTitle !== $newTitle) {
            $url = str::slug($newTitle);
            if($move = $page->move($url)) {
                panel()->redirect($page, "edit");
            }
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
});



kirby()->hook("panel.file.upload", "resizeImage");
kirby()->hook("panel.file.replace", "resizeImage");

// autoresize images on upload if they are to wide and their file size exceeds the defined limit
function resizeImage($file) {
    try {
        if(c::get("image.resize_on_upload") == true) {
            // check file type, dimensions and filesize
            if ($file->type() == "image" && $file->width() > max(c::get("image.widths")) && $file->size() > c::get("image.max_file_size")) {
                // get the original file path
                $originalPath = $file->dir() . "/" . $file->filename();

                // create a thumb and get its path
                $resizedImage = $file->resize(max(c::get("image.widths")));
                $resizedPath  = $resizedImage->dir() . "/" . $resizedImage->filename();

                // replace the original image with the resized one
                copy($resizedPath, $originalPath);
                unlink($resizedPath);
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}