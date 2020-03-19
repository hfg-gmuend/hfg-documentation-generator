<?php
    if(!isset($chapters)) $chapters = $page->parent() ? $page->parent()->children()->listed() : [];
    $nested = isset($nested) && $nested == true;
?>

<ul <?php if(!$nested) echo "role=\"navigation\"" ?> class="<?php if(!$nested) echo "menu " ?>list-unstyled <?= $nested ? "ml-5 mb-1" : "menu" ?>">
    <?php foreach($chapters as $chapter): ?>
        <li <?php if($chapter->depth() > 2) echo "class=\"collapsible\""?>>
            <a class="btn-link position-relative d-flex align-items-center <?= $chapter->is($page) ? "text-primary" : "text-dark" ?>" href="<?= $nested ? "#" . str::slug($chapter->id()) : $chapter->url() ?>">
                <?php if($chapter->is($page)): ?> <span class="arrow">&#8594;</span> <?php endif ?><?= html($chapter->title()) ?>
            </a>

            <!-- SUBCHAPTERS OF CURRENT CHAPTER -->
            <?php if($chapter->is($page) || $page->isAncestorOf($chapter)): ?>
                <?php snippet("menu", ["chapters" => $chapter->children()->listed(), "nested" => true]) ?>
            <?php endif ?>
        </li>
    <?php endforeach ?>
</ul>