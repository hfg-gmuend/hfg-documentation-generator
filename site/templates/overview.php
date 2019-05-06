<?php snippet("header") ?>

<main id="main" role="main">
            <a name="s-jump"></a>

  <div id="row" class="row my-4 flex-column-reverse flex-md-row">
      <div class="col-11 col-sm-11 col-md-6 offset-md-1 col-lg-7 offset-lg-1 col-xl-6 offset-xl-1">
          <h2 class="d-none d-sm-block "><?= $page->major() ?>  – <?= $page->course() ?></h2>
          <h1 class="d-none d-sm-block "><?= $page->title() ?></h1>
          <?php foreach($page->parent()->authors()->toStructure() as $author): ?>
          <?php if($author->website()->isNotEmpty()): ?>
              <a class="font-weight-bold" href="<?= $author->website()->url() ?>" target="_blank"><span class="mr-1">&#8594;</span><?= $author->name()->html() ?></a>
          <?php else: ?>
              <span class="mr-1">&#8594;</span><?= $author->name()->html() ?></br>
          <?php endif ?>
          <?php endforeach ?>
      </div>

      <div id="meta" class="col-11 col-md-3 col-lg-2 offset-md-1 pull-right">
            <div class="font-weight-bold">
                <hr align="left" class="mt-0 ml-0">
            </div>
            <div class="small">Supervision<br>
                <?php foreach($page->supervisors()->split() as $supervisor): ?>
                    <?= $supervisor ?><br>
                <?php endforeach ?>
                <hr align="left" class="d-md-none ml-0"><br>
                <a class="link-briefing" href="#briefing"><span class="mr-1">&#8595;</span>Briefing</a>
            </div>
      </div>

  </div>
        <!-- Side Info -->
        <div class="col-12 overview-container">
        <?php if($site->hasVisibleChildren()): ?>
        <ul class="col-10"id="overview-list">
            <?php foreach($site->children()->visible() as $documentation): ?>
                <li ><a href="<?= $documentation->children()->visible()->first()->url()?>">
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
                            <?php
                            $authors= array();
                            foreach($documentation->authors()->toStructure() as $key => $author) {
                              array_push($authors, $author->name()->toString());
                            }
                            echo implode(", ", $authors);
                            ?>
                        </p>
                    </div>

                </a></li>
            <?php endforeach ?>
        </ul>
        </div>

<div id="row2" class="row my-4 flex-column-reverse flex-md-row">
  <div id="briefing" class="col-11 col-md-6 offset-md-1 col-lg-7 offset-lg-1 col-xl-6 offset-xl-1">
    <h5>Briefing</h5>
    <div name="briefing" class="important font-weight-semibold"><?= $page->text()->kirbytext() ?></div>
  </div>

  <div class="col-11 col-md-3 col-lg-2 offset-md-1 pull-right">
          <a class="link-briefing" name="b-jump" href="#s-jump"><span class="mr-1">&#8593;</span>Overview</a>
  </div>
</div>
        <?php else: ?>
        <h3>You haven't added a documentation yet!</h3>
        <?php endif ?>
        <!-- PAGE CONTENT END -->
</main>
