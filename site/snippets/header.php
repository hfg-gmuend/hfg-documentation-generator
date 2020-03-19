<?php
    $parent = $page->parent() ?: site();
?>
<!doctype html>
<html lang="<?= site()->language() ? site()->language()->code() : "en" ?>">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title><?= $parent->title()->html() ?> | <?= $page->title()->html() ?></title>
    <meta name="description" content="<?= $site->description()->html() ?>">

    <!-- CSS -->
    <!-- highlight.js -->
    <?= css("assets/vendor/highlightjs/styles/monokai-sublime.css") ?>
    <!-- Custom template css and index.css -->
    <?php snippet("scss") ?>

</head>
<body>

    <header role="banner">
        <?php snippet("navbar") ?>
    </header>
