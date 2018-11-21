<?php $currentAccordionID = $page->getUniqueCodeAccordionID() ?>

<div class="accordion code mb-3" id=<?= "code-accordion-" . $currentAccordionID ?>>
    <div class="card rounded bg-dark" style="overflow: hidden;">
        <div class="card-header" id=<?= "code-accordion-" . $currentAccordionID ?>>
            <button class="btn btn-link d-flex collapsed" type="button" data-toggle="collapse" data-target="#<?= "code-accordion-collapse" . $currentAccordionID ?>" aria-expanded="false" aria-controls="<?= "code-accordion-collapse" . $currentAccordionID ?>">
                <i class="circle"></i>
            </button>
        </div>
        <div id="<?= "code-accordion-collapse" . $currentAccordionID ?>" class="collapse" aria-labelledby="<?= "code-accordion-heading" . $currentAccordionID ?>" data-parent="#<?= "code-accordion-" . $currentAccordionID ?>">
            <div class="card-body">
                <?= $content ?>
            </div>
        </div>
    </div>
</div>