// create observer instance
var observer = new MutationObserver(function(mutations) {
    extendFileSidebar();
});

// start observation by passing target node and config
observer.observe(app.content.root[0], { childList: true });

// functions that get called by observer
function extendFileSidebar() {
    // get all file nodes
    let items = app.content.element("ul.sidebar-list li .draggable-file").toArray();

    // call functions if they exist
    if(typeof previewFiles === "function") previewFiles(items);
    if(typeof updateDraggableDefs === "function") updateDraggableDefs(items);
}

// run functions once on init
extendFileSidebar();