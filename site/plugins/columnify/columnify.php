<?php

/**
 * Wraps all direct children of passed field that match one of the element descriptions in
 * config into a container with their respective class and adds placeholder if defined,
 * else wraps element into container with default element and placeholder classes.
 */
field::$methods["columnify"] = function($field) {
    // columnify if there is content
    if(strlen($field) > 0) {
        $doc = new DOMDocument();

        // parse field to html with utf-8 encoding
        $encoding = "<?xml encoding=\"utf-8\" ?>";
        @$doc->loadHtml($encoding . $field, LIBXML_HTML_NOIMPLIED + LIBXML_HTML_NODEFDTD + LIBXML_NOERROR);

        // define elements that should be columnified
        $columnifingElements = c::get("columnify.elements", array());

        // iterate over all childNodes and save matches
        $matches = array();

        foreach($doc->childNodes as $child) {
            // check if element type combined with classes is defined in $columnifingElements
            if(is_a($child, "DOMElement")) {
                // get nodeName from child
                $childDefinition = array($child->nodeName);

                // add classList to childDefinition
                $childDefinition = array_merge($childDefinition, explode(" ", $child->getAttribute("class")));

                // check if class list and element type matches one of the elements defined in columnifingElements list
                foreach($columnifingElements as $key => $element) {
                    // use key for element definition if element has own class and placeholder definition, else use element for element definition
                    $key = is_array($element) ? $key : $element;

                    $elementDefinition = explode(".", $key);

                    // check if child and element definition match and break out of loop if they do
                    if(count(array_intersect($childDefinition, $elementDefinition)) == count($elementDefinition)) {
                        array_push($matches, ["key" => $key, "node" => $child]);
                        break;
                    }
                }
            }
        }

        // iterate over all matches and columnify them
        foreach($matches as $key => $match) {
            // wrap element inside container
            $container = $doc->createElement("div");
                // set container class, if element has own class definition use it else use default class definition
                if(isset($columnifingElements[$match["key"]]) && isset($columnifingElements[$match["key"]]["element_class"])) {
                    $container->setAttribute("class", $columnifingElements[$match["key"]]["element_class"]);
                } else {
                    $container->setAttribute("class", c::get("columnify.default")["element_class"]);
                }
                $container->appendChild($match["node"]->cloneNode(true));

            // replace previous element with new wrapped element
            $doc->replaceChild($container, $match["node"]);

            // get for element defined placeholder classes
            $placeholderClasses = "";
            if(isset($columnifingElements[$match["key"]]) && isset($columnifingElements[$match["key"]]["placeholder_classes"])) {
                // skip element if placeholder shouldn't be set
                if($columnifingElements[$match["key"]]["placeholder_classes"] === false) continue;

                $placeholderClasses = $columnifingElements[$match["key"]]["placeholder_classes"];
                $placeholderClasses = is_array($placeholderClasses) ? $placeholderClasses : [$placeholderClasses];
            } else {
                $placeholderClasses = c::get("columnify.default")["placeholder_classes"];
                $placeholderClasses = is_array($placeholderClasses) ? $placeholderClasses : [$placeholderClasses];
            }

            // add placeholder column(s)
            foreach(array_reverse($placeholderClasses) as $placeholderClass) {
                $placeholderColumn = $doc->createElement("div");
                    $placeholderColumn->setAttribute("class", $placeholderClass);
                    $placeholderColumn->appendChild($doc->createComment("PLACEHOLDER COLUMN"));

                // insert placeholder column after container
                $doc->insertBefore($placeholderColumn, $container->nextSibling);
            }
        }

        // get html as string and replace field with it
        $field = str_replace($encoding, "", $doc->saveHtml());
    }

    return $field;
};