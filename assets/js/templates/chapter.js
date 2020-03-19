"use-strict";


//------------ Smooth scrolling to anchor points ------------
document.querySelectorAll("a[href^=\"#\"]").forEach(anchor => {
    anchor.addEventListener("click", e => {
        e.preventDefault();

        // check if navbar is fixed. If it is return navbar height else 0
        let navbarCompensation = document.getElementById("main").style.paddingTop ? parseInt(document.getElementById("main").style.paddingTop) : 0;

        window.scrollTo({
            top: document.querySelector(anchor.getAttribute("href")).getBoundingClientRect().top + window.scrollY - navbarCompensation,
            behavior: "smooth"
        });
    });
});



//------------ Sticky Sidenavbar ------------
var stickySidebar = new StickySidebar("#sticky-sidenavbar", {
    containerSelector: "#chapter-wrapper",
    innerWrapperSelector: ".sticky-sidenavbar-inner",
    topSpacing: 20,
    bottomSpacing: 20
});



//------------ Navbar Hamburger ------------
// animate hamburger when navbar state changes
var navbarCollapse = document.getElementById("navbarContent");
navbarCollapse.addEventListener("show.bs.collapse", event => {
    // add active class to hamburger toggler
    event.explicitOriginalTarget.classList.add("is-active");
});
navbarCollapse.addEventListener("hide.bs.collapse", event => {
    // remove active class from hamburger toggler
    event.explicitOriginalTarget.classList.remove("is-active");
});



//------------ Bootstrap breakpoint handlers ------------
function xlHandler(e) {
    if (e.matches) {
        // temporary fix for sticky sidebar calculating own width wrongly
        stickySidebar.sidebarInner.style.width = stickySidebar.sidebar.offsetWidth + "px";

        setNavbarFixedState(false);
    } else {
        setNavbarFixedState(true);
    }
}

// init media query listeners
var xlBreakpoint = window.matchMedia("(min-width: 1200px)");

// call listener functions at runtime
xlHandler(xlBreakpoint);

// attach listener function
xlBreakpoint.addListener(xlHandler);

// changes if navbar is fixed on top or not
function setNavbarFixedState(fixed) {
    let navbar = document.getElementById("navbar");

    if(fixed) {
        navbar.classList.add("fixed-top");

        // add padding top to main content so it doesn't get overlapped by navbar
        document.getElementById("main").style.paddingTop = navbar.offsetHeight - navbar.getElementsByClassName("navbar-collapse")[0].offsetHeight + "px";
    } else {
        navbar.classList.remove("fixed-top");

        // remove padding top from main content
        document.getElementById("main").style.paddingTop = "";
    }
}



//------------ P5Iframe resizing ------------
// get all p5iframes from page
var p5iframes = document.querySelectorAll("iframe.p5");

function resizeP5iframes(iframes) {
    if(iframes !== undefined) {
        iframes.forEach(iframe => {
            resizeP5iframe(iframe);
        });
    } else {
        document.querySelectorAll("iframe").forEach(iframe => {
            resizeP5iframe(iframe);
        });
    }
}

function resizeP5iframe(iframe) {
    // trigger refresh of iframe by updating src attribute on first resize (on load)
    // to fix bad caching on firefox
    if(iframe.getAttribute("data-triggered_refresh_by_source_update") !== "true") iframe.src = iframe.src;
    iframe.setAttribute("data-triggered_refresh_by_source_update", "true");

    // set iframe height to 0 to guarantee that scrollHeight is correct
    iframe.style.height = "0";

    // get html element inside iframe
    let innerDoc = iframe.contentDocument || iframe.contentWindow.document;
    iframe.closest(".embed-responsive").style.height = innerDoc.body.scrollHeight + "px";

    // set iframe height back to 100%
    iframe.style.height = "100%";
}

// check if iframes already finished loading before this js code executed and resize them
if(window.loadedP5iframes !== undefined) window.loadedP5iframes.forEach(iframe => resizeP5iframe(iframe));



//------------ Scrollspy ------------
var scrollSpyElement = document.getElementById("chapter-content");
var scrollSpyTarget  = document;
var scrollSpyOffset  = document.getElementById("navbar").offsetHeight + 5;
var scrollSpy = new ScrollSpy(scrollSpyElement, {
    target: scrollSpyTarget,
    offset: scrollSpyOffset
});

// attach event listener
scrollSpyElement.addEventListener("activate.bs.scrollspy", event => {
    // remove active class from previous elements on same level
    event.relatedTarget.parentNode.parentNode.childNodes.forEach(child => {
        if(child.classList) child.classList.remove("active");
    });

    // set surrounding node to active
    event.relatedTarget.parentNode.classList.add("active");
});

// init scrollspy
scrollSpyTarget.querySelectorAll("a.active").forEach(element => {
    // add active class to all parentNodes of active a-elements, so their content gets extended
    element.parentNode.classList.add("active");
});



//------------ window load and resize handlers ------------
window.onload = () => {
    // trigger recalc of sticky sidebar dimensions
    stickySidebar.updateSticky();
}

var windowGotResized = false;
var resizeInterval;

window.onresize = () => {
    // set resize interval handler if not already set
    if(resizeInterval === undefined) {
        // init resize interval which checks if window got resized every 50ms to improve performance
        resizeInterval = setInterval(() => {
            // call functions if window got resized
            if(windowGotResized) {
                // resize all p5iframes on page
                resizeP5iframes(p5iframes);

                // reset windowGotResized flag
                windowGotResized = false;
            }
        }, 50);
    }

    // set windowGotResized flag
    windowGotResized = true;
}