    <footer role="contentinfo">
        <?php $nextPage = $page->nextVisible() ?: ($site->hasVisibleChildren() > 1 ? $site->page("overview") : $page->parent()->children()->visible()->first()) ?>

        <div class="container ml-0">
            <a class="row" href="<?= $nextPage ? $nextPage->url() : "" ?>">
                <div class="col-11 col-md-10 col-lg-9 col-xl-8 offset-md-1">
                    <div class="border-top border-primary"></div>
                </div>
                <div class="d-none d-lg-block col-lg-1 col-xl-2"><!-- PLACEHOLDER COLUMN --></div>

                <div class="d-flex align-items-center col-1 offset-md-1 text-primary text-center">
                    <?php if($nextPage === $site->page("overview")): ?>
                        <div class="arrow-back fill-primary mx-auto mt-2 mt-lg-3 mb-2 mb-lg-3">
                            <?= file_get_contents(kirby()->roots()->assets() . "/images/ios-arrow-back.svg"); ?>
                        </div>
                    <?php else: ?>
                        <h2 class="d-none d-md-block mx-auto mb-0 pt-2 pt-lg-3 pb-2 pb-lg-3 align-self-center text-primary <?= $nextPage !== $page->parent()->children()->visible()->first() ? "arrow-rotate" : "arrow" ?>">&#8594;</h2>
                        <h3 class="d-block d-md-none mx-auto mb-0 pt-2 pt-lg-3 pb-2 pb-lg-3 align-self-center text-primary <?= $nextPage !== $page->parent()->children()->visible()->first() ? "arrow-rotate" : "arrow" ?>">&#8594;</h3>
                    <?php endif ?>
                </div>

                <div class="col-10 col-md-9 col-lg-8 col-xl-7 text-primary">
                    <h2 class="d-none d-md-block mb-0 pt-2 pt-lg-3 pb-2 pb-lg-3"><?= $nextPage === $site->page("overview") ? "Zurück zu der Projektübersicht" : ($nextPage === $page->parent()->children()->visible()->first() ? "Zurück zum Anfang" : $nextPage->title()) ?></h2>
                    <h3 class="d-block d-md-none mb-0 pt-2 pt-lg-3 pb-2 pb-lg-3"><?= $nextPage === $site->page("overview") ? "Zurück zu der Projektübersicht" : ($nextPage === $page->parent()->children()->visible()->first() ? "Zurück zum Anfang" : $nextPage->title()) ?></h3>
                </div>

            </a>
        </div>
    </footer>

    <!-- JavaScript -->
    <?php snippet("javascript") ?>
</body>
</html>