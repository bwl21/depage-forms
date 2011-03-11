<?php

namespace depage\htmlform\elements;

/**
 * HTML textarea element.
 **/
class textarea extends text {
    /**
     * @param $name input elements' name
     * @param $parameters array of input element parameters, HTML attributes, validator specs etc.
     * @param $formName name of the parent HTML form. Used to identify the element once it's rendered.
     **/
    public function __construct($name, $parameters, $formName) {
        parent::__construct($name, $parameters, $formName);
        
        $this->rows = (isset($parameters['rows'])) ? $parameters['rows'] : null;
        $this->cols = (isset($parameters['cols'])) ? $parameters['cols'] : null;
    }

    /**
     * Renders element to HTML.
     *
     * @return string of HTML rendered element
     **/
    public function __toString() {
        $rows = ($this->rows !== null) ? " rows=\"$this->rows\"" : "";
        $cols = ($this->cols !== null) ? " cols=\"$this->cols\"" : "";

        return "<p id=\"$this->formName-$this->name\" class=\"" . $this->getRenderedClasses() . "\">" .
            "<label>" .
                "<span class=\"label\">" . $this->label . $this->getRenderedRequiredChar() . "</span>" .
                "<textarea name=\"$this->name\"" . $this->getRenderedAttributes() . $rows . $cols . ">" . $this->getRenderedValue() . "</textarea>" .
            "</label>" .
        "</p>\n";
    }
}
