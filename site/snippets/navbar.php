<?php
    $is_overview = $page->is("overview");

    // set brandLink. If there is more than one documentation link to overview page,
    // else to first chapter of current documentation
    $brandLink = "";
    if($is_overview == false && site()->hasVisibleChildren() > 1 || site()->hasVisibleChildren() == 0) {
        $brandLink = $site->page("overview")->url();
    } else if($is_overview == false && $page->parent()->children()->visible()->first()) {
        $brandLink = $page->parent()->children()->visible()->first()->url();
    }
?>

<nav id="navbar" class="navbar navbar-expand-xl navbar-light shadow-sm bg-white p-3">
    <div class="w-100 d-flex align-items-center justify-content-between flex-nowrap">
        <a class="navbar-brand d-flex align-items-center text-dark text-truncate" href="<?= $brandLink ?>">
            <?php if($is_overview == false): ?>
                <div class="arrow-back mb-0 ml-md-3">
                    <?= file_get_contents(kirby()->roots()->assets() . "/images/ios-arrow-back.svg"); ?>
                </div>
            <?php endif ?>
            <h5 class="ml-3 mb-0 text-truncate">
                <?php if($is_overview == false): ?>
                <?= $page->parent()->title()->html() ?><br>
                <small class="text-muted"><?= $page->parent()->course()->html() ?></small>
                <?php else: ?>
                <?= $page->title()->html() ?><br>
                <?php endif ?>

            </h5>
        </a>
        <?php if($is_overview == false): ?>
            <button class="navbar-toggler btn btn-link hamburger--squeeze" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="hamburger-box d-block">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        <?php endif ?>
    </div>

    <?php if($is_overview == false): ?>
        <div class="collapse navbar-collapse" id="navbarContent">
            <div class="d-block d-xl-none ml-4 mt-2">
                <?php snippet("menu") ?>
            </div>
        </div>
    <?php endif ?>
</nav>
