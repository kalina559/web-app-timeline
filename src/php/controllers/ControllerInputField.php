<?php

class ControllerInputField
{
    private $value;
    function __construct($fieldName)
    {
        // validating if the input field was actually provided
        $data = $_POST['arguments'];
        if (!isset($data[$fieldName])) {
            throw new Exception("Controller field $fieldName is required.");
        } else {
            $this->value = $data[$fieldName];
        }
    }

    function get()
    {
        return $this->value;
    }
}
