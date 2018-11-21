<?php

require_once(__DIR__ . "/Highlight/Autoloader.php");
spl_autoload_register("\\Highlight\\Autoloader::load");

trait CustomHandlers {
    protected function rawHtml($text) {
        return $text;
    }
}


trait CustomSharedFunctions {
    // highlights code and wraps it inside code-accordion
    protected function generateHighlightedCodeAccordion($Block) {
        // init Highlighter
        $hl = new Highlight\Highlighter();
        $highlightedCode;

        // try to get language from code block definition
        $language = isset($Block["element"]["text"]["attributes"]["class"]) ? substr($Block["element"]["text"]["attributes"]["class"], strlen("language-")) : NULL;

        // if language is defined, highlight code based on defined language else try to autodetect language
        if(isset($language)) {
            try {
                // run highlighter based on defined language
                $highlightedCode = $hl->highlight($language, $Block["element"]["text"]["text"]);
            }
            catch (DomainException $e) {
                // This is thrown if the specified language does not exist
            }
        } else {
            // try to autodetect language
            $highlightedCode = $hl->highlightAuto($Block["element"]["text"]["text"]);

            // set language to autodetected language
            $language = $highlightedCode->language;
        }

        // update code block class
        $Block["element"]["text"]["attributes"]["class"] = "hljs " . $language;

        // replace element text if highlighter was successful
        if(isset($highlightedCode->value)) {
            $Block["element"]["text"]["text"] = $highlightedCode->value;
        }

        // create table structure to support line numbers
        $exploded = explode("\n", $Block["element"]["text"]["text"]);

        $Block["element"]["text"]["text"] = "<table><tbody>";
        foreach($exploded as $element) {
            $Block["element"]["text"]["text"] .= "<tr><td class=\"line-number\"></td><td class=\"line\"><span style=\"white-space: pre;\">" . $element . "</span></td></tr>";
        }
        $Block["element"]["text"]["text"] .= "</table></tbody>";

        // parse code element
        $Block["element"]["text"]["handler"] = "rawHtml";
        $code = self::element($Block["element"]["text"]);

        // generate code accordion
        $Block["element"] = array(
            "name"    => "code-accordion",
            "handler" => "rawHtml",
            "text"    => snippet("code-accordion", ["content" => $code], true)
        );

        return $Block;
    }
}

trait BlockCodeOverride {
    protected function blockCodeComplete($Block) {
        return self::generateHighlightedCodeAccordion($Block);
    }
}

trait BlockFencedCodeOverride {
    protected function blockFencedCodeComplete($Block) {
        return self::generateHighlightedCodeAccordion($Block);
    }
}

trait BlockImportantParagraphExtension {

    function __construct() {
        $this->BlockTypes["("][] = "ImportantParagraph";
    }

    protected function blockImportantParagraph($Line, $Block) {
        if(preg_match("/^\(important\)(.*?)\(endimportant\)/", $Line["text"], $matches)) {
            return array(
                "char" => $Line["text"][0],
                "element" => array(
                    "name"    => "p",
                    "attributes" => array(
                        "class" => "important"
                    ),
                    "handler" => "line",
                    "text"    => $matches[1]
                ),
                "complete" => true
            );
        }

        if(preg_match("/^\(important\)/", $Line["text"], $matches)) {
            return array(
                "char" => $Line["text"][0],
                "element" => array(
                    "name" => "p",
                    "attributes" => array(
                        "class" => "important"
                    ),
                    "handler" => "line",
                    "text"    => $Line["text"] === "(important)" ? "" : " " . substr($Line["text"], strlen("(important)"))
                )
            );
        }
    }

    protected function blockImportantParagraphContinue($Line, $Block) {
        if(isset($Block["complete"])) {
            return;
        }

        // A blank newline has occurred.
        if(isset($Block["interrupted"])) {
            $Block["element"]["text"] .= "\n";
            unset($Block["interrupted"]);
        }

        // Check for end of the block.
        if(preg_match("/(.*?)\(endimportant\)/", $Line["text"], $matches)) {
            $Block["element"]["text"] .= " " . $matches[1];
            $Block["element"]["text"] = substr($Block["element"]["text"], 1);

            // This will flag the block as "complete":
            // 1. The "continue" function will not be called again.
            // 2. The "complete" function will be called instead.
            $Block["complete"] = true;
            return $Block;
        }

        $Block["element"]["text"] .= "\n" . $Line["body"];

        return $Block;
    }

    protected function blockImportantParagraphComplete($Block) {
        return $Block;
    }
}

class ParsedownExtendedExtra extends ParsedownExtra {
    use CustomHandlers, CustomSharedFunctions, BlockCodeOverride, BlockFencedCodeOverride, BlockImportantParagraphExtension;
}

class ParsedownExtended extends Parsedown {
    use CustomHandlers, CustomSharedFunctions, BlockCodeOverride, BlockFencedCodeOverride, BlockImportantParagraphExtension;
}

class MarkdownExtended extends Kirby\Component\Markdown {
    public function parse($markdown, Field $field = null) {

        if(!$this->kirby->options["markdown"]) {
            return $markdown;
        } else {
            // initialize the right markdown class
            $parsedown = $this->kirby->options["markdown.extra"] ? new ParsedownExtendedExtra() : new ParsedownExtended();

            // set markdown auto-breaks
            $parsedown->setBreaksEnabled($this->kirby->options["markdown.breaks"]);

            // parse it!
            return $parsedown->text($markdown);
        }

    }
}

$kirby->set("component", "markdown", "MarkdownExtended");