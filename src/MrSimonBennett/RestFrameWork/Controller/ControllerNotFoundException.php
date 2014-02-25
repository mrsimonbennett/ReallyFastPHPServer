<?php

namespace MrSimonBennett\RestFramework\Controller;

class ControllerNotFoundException extends \Exception
{
    
    // custom string representation of object
    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function customFunction() {
        echo "A custom function for this type of exception\n";
    }
}
