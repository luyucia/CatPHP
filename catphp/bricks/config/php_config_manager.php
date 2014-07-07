<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PhpConfigManager
 *
 * @author luyu
 */
require_once  'ConfigManger.interface.php';

class PhpConfigManager implements ConfigManger{
    //put your code here
    private $config;


    public function __construct($path) {
        $this->config = require $path;
        
    }

    public function get_config_array() {
        return $this->config;
    }

    public function save() {
        echo "You can not save php file config!";
    }    
}

?>
