<?php

/**
 * SCSS Snippet
 * NOTE: modified so also index.css gets generated
 * @author    Bart van de Biezen <bart@bartvandebiezen.com>
 * @link      https://github.com/bartvandebiezen/kirby-v2-scssphp
 * @return    CSS and HTML
 * @version   1.0.1
 */

use Leafo\ScssPhp\Compiler;

// Using realpath seems to work best in different situations.
$root = realpath(__DIR__ . "/../..");

// index CSS path
$indexSCSS         = $root . "/assets/scss/index.scss";
$indexCSS          = $root . "/assets/css/index.css";
$indexCSSKirbyPath = "/assets/css/index.css";

// Set file paths. Used for checking and updating CSS file for current template.
$template     = $page->template();
$SCSS         = $root . "/assets/scss/templates/" . $template . ".scss";
$CSS          = $root . "/assets/css/templates/"  . $template . ".css";
$CSSKirbyPath = "assets/css/templates/" . $template . ".css";

// Set default SCSS if there is no SCSS for current template. If template is default, skip check.
if ($template == "default" or !file_exists($SCSS)) {
    $SCSS         = $root . "/assets/scss/templates/default.scss";
    $CSS          = $root . "/assets/css/templates/default.css";
    $CSSKirbyPath = "assets/css/templates/default.css";
}

// If the CSS file doesn't exist create it so we can write to it
if (!file_exists($CSS)) {
    if (!file_exists($root . "/assets/css/templates")) {
        mkdir($root . "/assets/css/templates", 0755, true);
    }
    touch($CSS,  mktime(0, 0, 0, date("m"), date("d"),  date("Y")-10));
}

// If the index CSS file doesn't exist create it so we can write to it
if (!file_exists($indexCSS)) {
    if (!file_exists($root . "/assets/css")) {
        mkdir($root . "/assets/css", 0755, true);
    }
    touch($indexCSS,  mktime(0, 0, 0, date("m"), date("d"),  date("Y")-10));
}

// For when the plugin should check if partials are changed. If any partial is newer than the main SCSS file, the main SCSS file will be "touched". This will trigger the compiler later on, on this server and also on another environment when synced.
if (c::get("scssNestedCheck")) {
    $SCSSDirectory = $root . "/assets/scss/partials";
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($SCSSDirectory));
    foreach ($files as $file) {
        if (pathinfo($file, PATHINFO_EXTENSION) == "scss" && (filemtime($file) > filemtime($SCSS) or filemtime($file) > filemtime($indexSCSS))) {
            if(filemtime($file) > filemtime($SCSS)) touch ($SCSS);
            if(filemtime($file) > filemtime($indexSCSS)) touch ($indexSCSS);
            clearstatcache();
            break;
        }
    }
}

// Get file modification times. Used for checking if update is required and as version number for caching.
$SCSSFileTime = filemtime($SCSS);
$CSSFileTime = filemtime($CSS);

// Update CSS when needed.
if (!file_exists($CSS) or $SCSSFileTime > $CSSFileTime ) {

    // Activate library.
    require_once $root . "/site/plugins/scssphp/scss.inc.php";
    $parser = new Compiler();

    // Setting compression provided by library.
    $parser->setFormatter("Leafo\ScssPhp\Formatter\Compressed");

    // Setting relative @import paths.
    $importPath = $root . "/assets/scss";
    $parser->addImportPath($importPath);

    // Place SCSS file in buffer.
    $buffer = file_get_contents($SCSS);

    // Compile content in buffer.
    $buffer = $parser->compile($buffer);

    // Minify the CSS even further.
    require_once $root . "/site/plugins/scssphp/minify.php";
    $buffer = minifyCSS($buffer);

    // Update CSS file.
    file_put_contents($CSS, $buffer);
}

// Get file modification times of index CSS. Used for checking if update is required and as version number for caching.
$indexSCSSFileTime = filemtime($indexSCSS);
$indexCSSFileTime = filemtime($indexCSS);

// Update index CSS when needed and scssCompile is activated.
if ((!file_exists($indexCSS) or $indexSCSSFileTime > $indexCSSFileTime) && c::get("scssCompile", false)) {

    // Activate library.
    require_once $root . "/site/plugins/scssphp/scss.inc.php";
    $parser = new Compiler();

    // Setting compression provided by library.
    $parser->setFormatter("Leafo\ScssPhp\Formatter\Compressed");

    // Setting relative @import paths.
    $importPath = $root . "/assets/scss/";
    $parser->addImportPath($importPath);

    // Place SCSS file in buffer.
    $buffer = file_get_contents($indexSCSS);

    // Compile content in buffer.
    $buffer = $parser->compile($buffer);

    // Minify the CSS even further.
    require_once $root . "/site/plugins/scssphp/minify.php";
    $buffer = minifyCSS($buffer);

    // Update CSS file.
    file_put_contents($indexCSS, $buffer);
}

?>

<?= css(url($indexCSSKirbyPath) . "?version=" . $indexCSSFileTime)?>
<?= css(url($CSSKirbyPath) . "?version=" . $CSSFileTime)?>
