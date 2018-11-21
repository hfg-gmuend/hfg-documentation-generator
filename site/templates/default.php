<?php snippet("header") ?>

    <main class="main" role="main">

        <div class="container ml-0 my-5">
            <!-- PAGE CONTENT -->
            <div class="row">
                <div class="col-10 offset-1">
                    <h1><?= $page->title()->html() ?></h1>
                    <?= $page->intro()->kirbytext() ?>
                    <?= $page->text()->kirbytext() ?>
                </div>
            </div>
            <!-- PAGE CONTENT END -->
        </div>

    </main>

<?php snippet("footer") ?>