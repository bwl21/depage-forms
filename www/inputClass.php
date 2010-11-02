<?php

require_once('validator.php');
require_once('textClass.php');
require_once('checkboxClass.php');

/**
 * The abstract class inputClass holds the intersections of all implemented
 * HTML input element types. It handles validation, manual value manipulation
 * and constructor parameter checks.
 **/
abstract class inputClass {
    /** 
     * Input element type - HTML input type attribute.
     **/
    protected $type;
    /**
     * Input element name.
     **/
    protected $name;
    /**
     * Input element - HTML label
     **/
    protected $label;
    /**
     * True if the input element is required to hold a value to be valid.
     **/
    protected $required;
    /**
     * Name of the parent HTML form. Used to identify the element once it's rendered.
     **/
    protected $formName;
    /**
     * Input elements's value.
     **/
    protected $value;
    /**
     * Holds validator object reference.
     **/
    protected $validator;
    /**
     * Contains current input elements' validation status.
     **/
    protected $valid;
    /**
     * HTML classes attribute for rendering the input element.
     **/
    protected $classes;
    /**
     * Input elements default parameter values.
     **/
    protected $defaults = array();

    /**
     * @param $name input elements' name
     * @param $parameters array of input element parameters, HTML attributes, validator specs etc.
     * @param $formName name of the parent HTML form. Used to identify the element once it's rendered.
     **/
     public function __construct($name, $parameters, $formName) {
        $this->_checkInputName($name);
        $this->_checkInputParameters($parameters);

        $this->type = get_class($this);
        $this->name = $name;
        // so it doesn't show errors on initial display (when its still empty)
        $this->valid = true;
        $this->formName = $formName;

        // loads default attributes from $this->defaults array
        $this->setDefaults();
        foreach ($this->defaults as $parameter => $default) {
            $this->$parameter = isset($parameters[$parameter]) ? $parameters[$parameter] : $default;
        }

        $this->validator = (isset($parameters['validator'])) ? new validator($parameters['validator']) : new validator($this->type);
    }

    /**
     * Hook method to be overridden by children in order to specify default
     * values.
     *
     * @return void
     **/
    protected function setDefaults() {
        $this->defaults = array(
            'label' => $this->name,
            'required' => false,
            'requiredChar' => ' *'
        );
    }

    /**
     * Returns the name of current input element.
     *
     * @return $this->name
     **/
    public function getName() {
        return $this->name;
    }

    /**
     * Checks if the value the current input element holds is valid according
     * to it's validator object.
     *
     * @return void
     **/
    public function validate() {
        $this->valid = (($this->validator->match($this->value) || empty($this->value)) && (!empty($this->value) || !$this->required));
    }

    /**
     * Returns the value of the current input elements' valid variable.
     *
     * @return $this->valid
     **/
    public function isValid() {
        return $this->valid;
    }

    /**
     * Allows to manually set the current input elements value.
     *
     * @param $newValue contains the new value
     * @return void
     **/
    public function setValue($newValue) {
        $this->value = $newValue;
    }

    /**
     * Returns the current input elements' value.
     *
     * @return $this->value
     **/
    public function getValue() {
        return $this->value;
    }

    /**
     * Sets the HTML required attribute of the current input element.
     *
     * @return void
     **/
    public function setRequired() {
        $this->required = true;
    }

    /**
     * Unsets the the HTML required attribute of the current input element.
     *
     * @return void
     **/
    public function setNotRequired() {
        $this->required = false;
    }

    protected function getClasses() {
        $classes = 'input-' . $this->type;
        
        if ($this->required) {
            $classes .= ' required';
        }
        if (!$this->isValid()) {
            $classes .= ' error';
        }
        return $classes;
    }

    protected function getRequiredChar() {
        return ($this->required) ? $this->requiredChar : '';
    }

    private function _checkInputParameters($parameters) {
        if ((isset($parameters)) && (!is_array($parameters))) {
            throw new inputParametersNoArrayException();
        }
    }
    
    private function _checkInputName($name) {
        if (!is_string($name)) {
            throw new inputNameNoStringException();
        }
        if (trim($name) === '') {
            throw new invalidInputNameException();
        }
    }
}
