<?php snippet("header") ?>

<main id="main" role="main">

    <div class="container ml-md-0 my-3 my-md-5">
        <!-- PAGE CONTENT -->
        <?php if($site->hasVisibleChildren()): ?>
        <ul>
            <?php foreach($site->children()->visible() as $documentation): ?>
                <li><a href="<?= $documentation->children()->visible()->first()->url() ?>"><?= $documentation->title() ?></a></li>
            <?php endforeach ?>
        </ul>
        <?php else: ?>
        <h3>You haven't added a documentation yet!</h3>
        <?php endif ?>
        <!-- PAGE CONTENT END -->
    </div>

</main>