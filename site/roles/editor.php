<?php

return [
    "name"        => "Editor",
    "default"     => true,
    "permissions" => [
        "*" => true,
        "panel.site.update" => false,
        "panel.user.*"      => function() {
            return $this->target()->user()->iscurrent();
        },
        "panel.user.create" => false,
        "panel.user.delete" => false,
        "panel.page.read"   => function() {
            return $this->target()->page()->id() !== "error" && $this->target()->page()->id() !== "overview";
        },
        "panel.page.create" => function() {
            return $this->target()->page() ? $this->target()->page()->depth() < 2 : false;
        }
    ]
];