<?php snippet("header") ?>

<main id="main" role="main">

    <div class="container ml-md-0 my-3 my-md-5">
        <!-- PAGE CONTENT -->
        <?php if($site->hasVisibleChildren()): ?>
        <ul id="overview-list">
            <?php foreach($site->children()->visible() as $documentation): ?>
                <li><a href="<?= $documentation->children()->visible()->first()->url() ?>">
                    <?php
                    $cover = $documentation->coverImage()->toFile();
                    if ($cover) {
                        $_size1x = 300;
                        $_size2x = $_size1x * 2;
                        $_src1x = $cover->resize(null, $_size1x, 90);
                        $_src2x = $cover->resize(null, $_size2x, 85);
                        echo html::img($_src1x->url(), array(
                        'srcset' => $_src1x->url().' 1x, '.$_src2x->url().' 2x',
                        ));
                    }
                    ?>

                    <div class="overview-caption">
                        <h4 class="overview-title"><?= $documentation->title()->html() ?>
                        </h4>
                        <p class="overview-authors">
                            <?php foreach($authors = $documentation->authors()->toStructure() as $key=>$auth): ?>
                                <?= $auth->name() ?><?php if($key < $authors->count() - 1): ?>,<?php endif ?>
                            <?php endforeach ?>
                        </p>
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