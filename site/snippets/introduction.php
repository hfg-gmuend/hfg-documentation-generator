<div id="introduction">
    <div class="row">
        <div class="<?= c::get("columnify.default")["element_class"] ?>">
            <h1 class="d-none d-sm-block font-weight-bold"><?= $page->parent()->title()->html() ?></h1>
            <h2 class="d-block d-sm-none font-weight-bold"><?= $page->parent()->title()->html() ?></h2>

            <?php foreach($page->parent()->authors()->toStructure() as $author): ?>
            <?php if($author->website()->isNotEmpty()): ?>
                <a class="font-weight-bold" href="<?= $author->website()->url() ?>" target="_blank"><span class="mr-1">&#8594;</span><?= $author->name()->html() ?></a></br>
            <?php else: ?>
                <span class="mr-1">&#8594;</span><?= $author->name()->html() ?></br>
            <?php endif ?>
            <?php endforeach ?>
        </div>
        <div class="<?= c::get("columnify.default")["placeholder_class"] ?>"><!-- PLACEHOLDER COLUMN --></div>
    </div>

    <div class="row my-4 flex-column-reverse flex-md-row">
        <!-- Introduction -->
        <div class="col-11 col-md-6 offset-md-1 col-lg-7 lead font-weight-semibold">
            <?= $page->parent()->description()->kirbytext() ?>
        </div>

        <!-- Side Info -->
        <div class="col-11 col-md-3 col-lg-2 offset-md-1 pull-right">
            <div class="font-weight-bold">
                <hr align="left" class="mt-0 ml-0">
                <p class="mb-0">
                    <?= $page->parent()->course()->html() ?>
                </p>
                <p class="mb-0">
                    <?= $page->parent()->major()->html() . $page->parent()->semester()->html() ?>
                </p>
            </div>
            <br>
            <div class="small">Supervision<br>
                <?php foreach($page->parent()->supervisors()->split() as $supervisor): ?>
                    <?= $supervisor ?><br>
                <?php endforeach ?>
                <hr align="left" class="d-md-none ml-0">
            </div>
        </div>
    </div>

    <!-- Cover Image -->
    <div class="row">
        <?php if($page->parent()->coverImage() && $page->parent()->coverImage()->isNotEmpty()): ?>
            <div class="col-11 col-md-10 offset-md-1 col-lg-7">
                <?= kirbytag(array("image" => $page->parent()->coverImage()->toFile()->url())) ?>
            </div>
        <?php endif ?>
    </div>
</div>