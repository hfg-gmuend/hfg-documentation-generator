<?php snippet("header") ?>

    <main id="main" role="main">

        <div class="container ml-md-0 my-3 my-md-5">
            <!-- PAGE CONTENT -->
            <?php if($page->is($page->parent()->children()->visible()->first())) snippet("introduction") ?>

                <!-- CHAPTER WRAPPER -->
                <div id="chapter-wrapper" class="position-relative">

                    <?php snippet("sticky-sidenavbar") ?>

                    <!-- CHAPTER CONTENT-->
                    <div id="chapter-content" class="position-relative mt-4 mt-md-5">
                        <!-- MAIN CHAPTER -->
                        <section id="<?= str::slug($page->id()) ?>" class="row">
                            <div class="col-2 col-md-1 offset-md-1 pb-2 fill-primary">
                                <?= file_get_contents(kirby()->roots()->assets() . "/images/arrow_right_long.svg"); ?>
                            </div>
                            <div class="col-9 col-6 col-md-6">
                                <h1 class="text-primary"><?= $page->title()->html() ?></h1>
                            </div>
                            <div class="d-none d-md-block col-3"><!-- PLACEHOLDER COLUMN --></div>
                            <?= $page->text()->kirbytext()->columnify() ?>
                        </section>
                        <!-- MAIN CHAPTER END -->

                        <!-- SUBCHAPTERS -->
                        <?php snippet("subchapters") ?>
                        <!-- SUBCHAPTERS END -->
                    </div>
                    <!-- CHAPTER CONTENT END-->
                </div>
                <!-- CHAPTER WRAPPER END-->
            <!-- PAGE CONTENT END -->
        </div>

    </main>

<?php snippet("footer") ?>