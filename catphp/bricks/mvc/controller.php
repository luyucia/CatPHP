<?php

/**
 * 
 */


class Controller {

    private $context = array();
    private $engine;

    function __construct($rout) {
        $this->rout = $rout;
        $this->engine = new Tenjin_Engine();
    }

    public function assign($key, $value) {
        $this->context[$key] = $value;
    }

    public function render($tpl) {
        $output = $this->engine->render($tpl, $this->context);
        return $output;
    }

    protected function getRequest() {
        
    }

    protected function getRoutRequest($key) {
        return $this->rout[$key];
    }

    // public function 

    public function __call($name, $arguments) {
        echo $name . ' is not defined!';
    }



}

?>