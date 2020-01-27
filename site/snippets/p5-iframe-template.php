<?php $file_exists = f::exists(str_replace($site->url(), $kirby->roots()->index, $url)) ?>
<html>
<head>
    <meta charset="UTF-8">
    <?= js("assets/vendor/p5/p5.min.js") ?>
    <?= js("assets/vendor/p5/addons/p5.sound.min.js") ?>
    <?= js("assets/vendor/matter.js/matter.min.js") ?>
    <?php if($file_exists == true) echo js($url) ?>
    <style>
        body {
            padding: 0;
            margin: 0;
            overflow: hidden;
        }
        .p5Canvas {
            width: 100% !important;
            height: auto !important;
            display: block;
        }
        #file-not-found {
            text-align: center;
            font-family: "IBM Plex Sans", "Helvetica Neue", Arial, sans-serif;
            color: dimgray;
            margin: 0;
            padding: 1rem;
            background-color: lightgrey;
        }
    </style>
</head>
<body>
    <?php if($file_exists == false): ?> <h1 id="file-not-found">File not Found!</h1> <?php endif ?>
</body>
</html>