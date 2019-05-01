<?php
    if(!isset($subchapters)) $subchapters = $page->children()->visible();
    $nested = isset($nested) && $nested == true;
?>

<?php foreach($subchapters as $subchapter): ?>
    <section id="<?= str::slug($subchapter->id()) ?>" class="<?= $nested ? "row m-0 w-100" : "row"?>">
        <div class="<?= c::get("columnify.default")["element_class"] ?> mt-4">
            <h5><?= $subchapter->title()->html() ?></h5>
        </div>
        <div class="<?= c::get("columnify.default")["placeholder_classes"][0] ?>"><!-- PLACEHOLDER COLUMN --></div>
        <div class="<?= c::get("columnify.default")["placeholder_classes"][1] ?>"><!-- PLACEHOLDER COLUMN --></div>
        <?= $subchapter->text()->kirbytext()->columnify() ?>
        <?php snippet("subchapters", array("subchapters" => $subchapter->children()->visible(), "nested" => true)) ?>
    </section>
<?php endforeach ?>