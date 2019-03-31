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

// create image thumbs for responsiveness (as defined in config) and also resizes the uploaded
// base image if it exceeds the file size limit defined in the config
function resizeImage($file) {
    // only resize if resize_on_upload is activated and file is image
    if(c::get("image.resize_on_upload") !== true || $file->type() !== "image") return $file;

    try {
        // build responsive image so thumbs get generated
        $fileSize = $file->size();
        $file->genResponsiveImage();

        // notify if base image has been resized because it exceeded filesize limit
        if($fileSize != $file->size()) {
            panel()->alert("Image has been resized!
                            To guarantee quality upload images which don't
                            exceed the filesize limit of " . f::niceSize(c::get("image.max_file_size")) . "!");
            panel()->redirect("/");
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}