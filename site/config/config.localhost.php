<?php

//------------ Enable Debug Mode ------------
c::set("debug", true);


//------------ Disable Cache ------------
c::set("cache", false);


//------------ Scss Plugin ------------
c::set("scssCompile", true);
c::set("scssNestedCheck", true);


//------------ Autologin ------------
c::set("autologin", true);


//------------ Staticbuilder Plugin ------------
c::set("staticbuilder", true);

// Filter out pages that shouldn't be included in static build (e.g. invisible documentations)
c::set("staticbuilder.filter", function($page) {
    // hack to set homepage to overview if there are multiple documentations or first chapter of only existing documentation
    if(site()->hasVisibleChildren() > 1 || site()->hasVisibleChildren() == 0) {
        kirby()->set("option", "home", "overview");
    } else {
        kirby()->set("option", "home", site()->pages()->visible()->first()->children()->visible()->first()->id());
    }

    // exclude documentations root pages because they don't have any content
    if($page->intendedTemplate() === "documentation")
        return [false, "Documentation root pages are excluded from static build."];

    // exclude overview if there is only one documentation
    if(!(site()->hasVisibleChildren() > 1 || site()->hasVisibleChildren() == 0) && $page->is("overview"))
        return [false, "Overview is excluded from static build because there's only one documentation."];

    // exclude all invisible pages except overview
    if(!$page->is("overview") && $page->isInvisible())
        return [false, "Invisible pages are excluded from static build."];

    // exclude all pages with invisible parents
    $parents = $page->parents();
    foreach($parents as $parent) {
        if($parent->isInvisible()) return [false, "Pages with invisible parents are excluded from static build."];
    }

    // call staticbuilder default filter
    return KirbyStaticBuilder\Plugin::defaultFilter($page);
});