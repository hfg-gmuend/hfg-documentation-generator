<?php

//------------ Routes ------------
kirby()->routes(array(
    // redirect to first page
    array(
        "pattern" => "/",
        "action" => function() {
            // redirect to documentation overview if there is more than one visible documentation, else redirect to first chapter of the only existing documentation. If no visible documentation exists redirects also to overview
            if(site()->hasVisibleChildren() > 1 || site()->hasVisibleChildren() == 0) {
                go("overview");
            } else {
                go(site()->pages()->visible()->first()->children()->visible()->first()->url());
            }
        }
    ),
    array(
        "pattern" => "^(?!staticbuilder)(:any)",
        "action" => function($uri) {
            // redirect to first chapter of documentation if baseUrl/documentationName/ is called else don't redirect
            if($uri !== "overview" && (site()->page($uri) && site()->page($uri)->children()->visible()->first())) {
                go(site()->page($uri)->children()->visible()->first());
            } else {
                return site()->visit($uri);
            }
        }
    )
));