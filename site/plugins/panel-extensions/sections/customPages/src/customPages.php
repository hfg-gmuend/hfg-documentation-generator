<?php

use Kirby\Cms\Section;

$base = Section::$types["pages"];

return array_replace_recursive($base, [
    "computed" => [
       "pages" => function () {
            switch ($this->status) {
                case "draft":
                    $pages = $this->parent->drafts();
                    break;
                case "listed":
                    $pages = $this->parent->children()->listed();
                    break;
                case "published":
                    $pages = $this->parent->children();
                    break;
                case "unlisted":
                    $pages = $this->parent->children()->unlisted();
                    break;
                default:
                    $pages = $this->parent->childrenAndDrafts();
            }

            // loop for the best performance
            foreach ($pages->data as $id => $page) {
                // remove all pages which currently logged in user isn't allowed to view
                if (!$page->hasPermission(kirby()->user())) {
                    unset($pages->data[$id]);
                    continue;
                }

                // remove all protected pages
                if ($page->isReadable() === false) {
                    unset($pages->data[$id]);
                    continue;
                }

                // filter by all set templates
                if ($this->templates && in_array($page->intendedTemplate()->name(), $this->templates) === false) {
                    unset($pages->data[$id]);
                    continue;
                }
            }

            // sort
            if ($this->sortBy) {
                $pages = $pages->sortBy(...$pages::sortArgs($this->sortBy));
            }

            // flip
            if ($this->flip === true) {
                $pages = $pages->flip();
            }

            // pagination
            $pages = $pages->paginate([
                "page"  => $this->page,
                "limit" => $this->limit
            ]);

            return $pages;
       },
       "add" => function () {
           if ($this->create === false) {
               return false;
            }

            if (in_array($this->status, ["draft", "all"]) === false) {
                return false;
            }

            if ($this->isFull() === true) {
                return false;
            }

            // return false, if the next page level is too deeply nested
            if (is_int($this->parent()->depth()) && $this->parent()->depth() >= option("max_page_depth")) {
                return false;
            }

            return true;
        }
    ],
]);
