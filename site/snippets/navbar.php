<?php
    $is_overview = $page === $site->page("overview");
?>

<nav id="navbar" class="navbar navbar-expand-xl navbar-light shadow-sm bg-white p-3">
    <a class="navbar-brand d-flex align-items-center text-dark" href="<?php if($is_overview == false && $page->parent()->children()->visible()->first()) echo $page->parent()->children()->visible()->first()->url(); ?>">
        <?php if($is_overview == false): ?>
            <div class="arrow-back mb-0 ml-md-3">
                <?= file_get_contents(kirby()->roots()->assets() . "/images/ios-arrow-back.svg"); ?>
            </div>
        <?php endif ?>
        <h5 class="ml-3 mb-0">
            <?= $page->parent()->title()->html() ?><br>
            <small class="text-muted"><?= $page->parent()->course()->html() ?></small>
        </h5>
    </a>
    <?php if($is_overview == false): ?>
        <button class="navbar-toggler btn btn-link hamburger--squeeze" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="hamburger-box">
                <span class="hamburger-inner"></span>
            </span>
        </button>
    <?php endif ?>

    <div class="collapse navbar-collapse" id="navbarContent">
        <div class="d-block d-xl-none ml-4 mt-2">
            <?php snippet("menu") ?>
        </div>
    </div>
</nav>
