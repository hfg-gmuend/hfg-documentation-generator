<?php snippet("header") ?>

<main id="main" role="main">

    <div class="container ml-md-0 my-3 my-md-5">
        <!-- PAGE CONTENT -->
        <?php if($site->hasVisibleChildren()): ?>
        <ul id="overview-list">
            <?php foreach($site->children()->visible() as $documentation): ?>
                <li>
                    <?php if($imageMain = $documentation->image($documentation->coverImage())): ?>
                        <img srcset="<?= $imageMain->url(); ?> 1x, <?= $imageMain->url(); ?> 2x"; ?> 
                    <?php endif ?>
                    <a href="<?= $documentation->children()->visible()->first()->url() ?>">
                    <div class="overview-caption">
                        <h4 class="overview-title"><?= $documentation->title()->html() ?>
                        </h4>
                            <?php foreach($documentation->authors()->toStructure() as $auth): ?>
                            <p class="overview-authors"><?= $auth->name() ?></p>
                            <?php endforeach ?>
                    </div>                   
                        
                </a></li>
            <?php endforeach ?>
        </ul>
        <?php else: ?>
        <h3>You haven't added a documentation yet!</h3>
        <?php endif ?>
        <!-- PAGE CONTENT END -->
    </div>

</main>