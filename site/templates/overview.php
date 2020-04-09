<?php snippet("header") ?>

<main id="main" role="main">
            <a name="s-jump"></a>
  <div id="row" class="row my-4 flex-column-reverse flex-md-row">
      <div class="col-11 col-sm-11 col-md-6 offset-md-1 col-lg-7 offset-lg-1 col-xl-6 offset-xl-1">
          <h2 class="d-none d-sm-block ">
            <?php
            // Make long names from classes tags. E.g. [KG3, IG2, KG4] will be transformed to 
            // "Kommunikationsgestaltung 3+4, Interaktionsgestaltung 2"
            $classes = [];
            foreach($page->classes()->split() as $class) {
                // Split e.g. "IG2" into department "IG" and classNumber "2"
                $department = substr($class, 0, -1);
                $classNumber = substr($class, -1);
                // Store everything in a dictionary, e.g. {KG:[3,4], IG:[2]}
                if (array_key_exists($department, $classes)) {
                    $classes[$department][] = $classNumber;
                } else {
                    $classes[$department] = [];
                    $classes[$department][] = $classNumber;
                }
            }

            // Go through that dictionary and build a string of it
            $result = "";
            foreach ($classes as $department => $classNumbers) {
                // Look up long names of the departments given in config.php
                $departmentLongName = option("departmentLongName.{$department}");
                $result = "{$result}{$departmentLongName} ";
                // asort($classNumbers);
                foreach ($classNumbers as $classNumber) {
                    $result = "{$result}{$classNumber}+";
                }
                $result = substr($result, 0, -1);
                $result = "{$result}, ";
            }
            
            $result = substr($result, 0, -2);
            echo $result;
            ?>
            – <?= $page->course() ?>
          </h2>
          <h1 class="d-none d-sm-block "><?= $page->courseSubject() ?></h1>
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
                <a class="btn-link link-briefing" href="#briefing"><span class="mr-1">&#8595;</span>Briefing</a>
            </div>
      </div>

  </div>
        <!-- Side Info -->
        <div class="col-12 overview-container">
        <?php if($site->hasListedChildren()): ?>
        <ul class="col-10"id="overview-list">
            <?php
                // get all documentations with listed chapters
                $filteredDocumentations = $site->children()->listed()->filter(function($page) {
                    return $page->intendedTemplate()->name() === "documentation" && $page->hasListedChildren();
                });
                foreach($filteredDocumentations as $documentation): ?>
                <li ><a class="btn-link" href="<?= $documentation->children()->listed()->first()->url()?>">
                    <?php
                    $cover = $documentation->coverImage()->toFile();
                    if ($cover) {
                        $_size1x = 300;
                        $_size2x = $_size1x * 2;
                        $_src1x = $cover->resize(null, $_size1x, 90);
                        $_src2x = $cover->resize(null, $_size2x, 85);
                        echo html::img($_src1x->url(), [
                            'srcset' => $_src1x->url().' 1x, '.$_src2x->url().' 2x',
                        ]);
                    }
                    ?>
                    <div class="overview-caption">
                        <h4 class="overview-title"><?= $documentation->title()->html() ?>
                        </h4>
                        <p class="overview-authors">
                            <?php
                            $authors = [];
                            foreach($documentation->authors()->toUsers() as $author) {
                              array_push($authors, $author->firstName()->toString() . " " . $author->lastName()->toString());
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
          <a class="btn-link link-briefing" name="b-jump" href="#s-jump"><span class="mr-1">&#8593;</span>Overview</a>
  </div>
</div>
        <?php else: ?>
        <h3>You haven't added a documentation yet!</h3>
        <?php endif ?>
        <!-- PAGE CONTENT END -->
</main>
