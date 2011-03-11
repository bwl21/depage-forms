<?php

namespace depage\htmlform\elements;

/**
 * HTML number input type.
 **/
class number extends text {
    /**
     * Minimum range HTML attribute.
     **/
    protected $min;
    /**
     * Maximum range HTML attribute.
     **/
    protected $max;
    /**
     * Step HTML attribute.
     **/
    protected $step;
    
   /**
     * @param $name input elements' name
     * @param $parameters array of input element parameters, HTML attributes, validator specs etc.
     * @param $formName name of the parent HTML form. Used to identify the element once it's rendered.
     **/
    public function __construct($name, $parameters, $formName) {
        parent::__construct($name, $parameters, $formName);
        
        $this->defaultValue = (isset($parameters['defaultValue'])) ? $parameters['defaultValue'] : 0;
        $this->min = (isset($parameters['min'])) ? $parameters['min'] : null;
        $this->max = (isset($parameters['max'])) ? $parameters['max'] : null;
        $this->step = (isset($parameters['step'])) ? $parameters['step'] : null;
        $this->errorMessage = (isset($parameters['errorMessage'])) ? $parameters['errorMessage'] : 'Please enter a valid number!';
    }

    /**
     * Renders element to HTML.
     *
     * @return string of HTML rendered element
     **/
    public function __toString() {
        /**
         * @todo temporary - remove once error reporting has been implemented
         **/

        $value = $this->getRenderedValue();

        if ($this->min !== null) {
            $min = " min=\"$this->min\"";
            if ($value < $this->min) {
                $value = $this->min;
            }
        } else {
            $min = "";
        }
        if ($this->max !== null) {
            $max = " max=\"$this->max\"";
            if ($value > $this->max) {
                $value = $this->max;
            }
        } else {
            $max = "";
        }
        $step = ($this->step !== null) ? " step=\"$this->step\"" : "";

        return "<p id=\"$this->formName-$this->name\" class=\"" . $this->getRenderedClasses() . "\">" .
            "<label>" . 
                "<span class=\"label\">" . $this->label . $this->getRenderedRequiredChar() . "</span>" . 
                "<input name=\"$this->name\" type=\"$this->type\"" . $max . $min . $step . $this->getRenderedAttributes() . " value=\"$value\">" .
            "</label>" .
        "</p>";
    }

    /**
     * Overrides parent method to add min and max values
     **/
    protected function validatorCall() {
        return $this->validator->validate($this->value, $this->min, $this->max);
    }

    /**
     * Converts value to element specific type.
     **/
    protected function typeCastValue() {
        $this->value = (float) $this->value;
    }
}
