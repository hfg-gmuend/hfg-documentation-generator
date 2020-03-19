<?php

// file extension for custom drag texts
Kirby::plugin("file-extensions/customDragText", [
    "fileMethods" => [
        "customDragText" => function (string $type = null, bool $absolute = false) {
            $file = $this;
            $type = $type ?? "auto";

            if ($type === "auto") {
                $type = option("panel.kirbytext", true) ? "kirbytext" : "markdown";
            }

            $customDragTexts= option("customFileDragTexts");

            // if custom drag text is defined for file extension or kirby file type use it else tag is default 'file'
            $fileTypeTag = "file";
            if(array_key_exists($file->extension(), $customDragTexts)) {
                $fileTypeTag = $customDragTexts[$file->extension()];
            } else if(array_key_exists($file->type(), $customDragTexts)) {
                $fileTypeTag = $customDragTexts[$file->type()];
            }

            $url = $absolute ? $this->id() : $this->filename();

            switch ($type) {
                case "markdown":
                    if ($this->type() === "image") {
                        return "![" . $this->alt() . "](" . $url . ")";
                    } else {
                        return "[" . $this->filename() . "](" . $url . ")";
                    }
                    // no break
                default:
                    return "(" . $fileTypeTag . ": " . $url . ")";
            }
        }
    ]
]);

// NOTE: plugin structure inspired by https://github.com/rasteiner/k3-cdtfiles-section
Kirby::plugin("panel-extensions/customFiles-section", [
    "sections" => [
        "customFiles" => require __DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . "customFiles.php"
    ]
]);