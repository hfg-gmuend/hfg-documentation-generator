<?php

use Kirby\Cms\Section;

$base = Section::$types["files"];

return array_replace_recursive($base, [
    "computed" => [
        "data" => function () use ($base) {
            $data = [];

            // the drag text needs to be absolute when the files come from
            // a different parent model
            $dragTextAbsolute = $this->model->is($this->parent) === false;

            foreach ($this->files as $file) {
                $image = $file->panelImage($this->image);

                $data[] = [
                    "dragText" => $file->customDragText("auto", $dragTextAbsolute),
                    "filename" => $file->filename(),
                    "id"       => $file->id(),
                    "text"     => $file->toString($this->text),
                    "info"     => $file->toString($this->info ?? false),
                    "icon"     => $file->panelIcon($image),
                    "image"    => $image,
                    "link"     => $file->panelUrl(true),
                    "parent"   => $file->parent()->panelPath(),
                    "url"      => $file->url(),
                ];
            }

            return $data;
        }
    ]
]);