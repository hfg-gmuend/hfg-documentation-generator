<?php

/*

---------------------------------------
License Setup
---------------------------------------

Please add your license key, which you've received
via email after purchasing Kirby on http://getkirby.com/buy

It is not permitted to run a public website without a
valid license key. Please read the End User License Agreement
for more information: http://getkirby.com/license

*/

c::set("license", "put your license key here");
/*

---------------------------------------
Kirby Configuration
---------------------------------------

By default you don't have to configure anything to
make Kirby work. For more fine-grained configuration
of the system, please check out http://getkirby.com/docs/advanced/options

*/


//------------ Max Page Depth ------------
// e.g when set to 3:
//   - documentations
//     - chapters
//       - subchapters
c::set("max_page_depth", 3);


//------------ Enable Cache ------------
c::set("cache", true);


//------------ Smartypants ------------
c::set("smartypants", true);


//------------ File Upload and Images ------------
c::set("image.resize_on_upload", false);
c::set("image.max_file_size", 1000000); // in Byte
c::set("image.widths", [480, 854, 1280, 1920, 2560]); // NOTE: images wider than biggest defined image size and bigger than maximum defined image file size get resized when resize_on_upload is set


//------------ Video ------------
c::set("kirbytext.video.class", "embed-responsive-item");


//------------ Sourcecode Tag ------------
c::set("sourcecode.max_file_size", 10000); // in Byte


//------------ Columnify Plugin ------------
c::set("columnify.default", [
    "element_class"     => "col-11 col-md-10 offset-md-1 col-lg-8 offset-lg-2 col-xl-6 offset-xl-2",
    "placeholder_class" => "d-none d-lg-block col-lg-1 col-xl-3"
]);

// define elements that should be columnified and define their element and placeholder class, if only element name is defined default classes are chosen
c::set("columnify.elements", [
    "p.important"          => [
        "element_class"     => "col-11 col-md-10 col-lg-8 offset-lg-1 col-xl-6 offset-xl-1 lead font-weight-semibold",
        "placeholder_class" => "d-none d-md-block col-md-1 col-lg-2 col-xl-4"
    ],
    "p",
    "hr",
    "ul",
    "ol",
    "figure",
    "blockquote",
    "code-accordion",
    "div.embed-gist",
    "audio",

    "div.image.default",
    "div.image.big"        => [
        "element_class"     => "col-11 col-md-10 offset-md-1 col-lg-10 offset-lg-1 col-xl-8 offset-xl-1",
        "placeholder_class" => "d-none d-xl-block col-xl-2"
    ],
    "div.image.half" => [
        "element_class"     => "col-11 col-md-5 offset-md-1 col-xl-4 offset-xl-1",
        "placeholder_class" => "col-6"
    ],
    "div.image.half_first" => [
        "element_class"     => "col-11 col-md-5 offset-md-1 col-xl-4 offset-xl-1",
        "placeholder_class" => ""
    ],
    "div.image.half_second" => [
        "element_class"     => "col-11 col-md-5 col-xl-4",
        "placeholder_class" => "d-none d-xl-block col-xl-2"
    ]
]);