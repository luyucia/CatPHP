<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Config {

    
    private $config_manager;
    private $config_array;
    private static $instance;
            
    private function __construct($type="php",$path='config.php') {
        
        if($type=='php')
        {
            require_once 'PhpConfigManager.class.php';
            $this->config_manager = new PhpConfigManager($path);
            $this->config_array   = $this->config_manager->get_config_array(); 
        }
    }
    
    public static function getInstance($type="php",$path='config.php')
    {
        if(self::$instance instanceof self)
        {
            return self::$instance;
        }
        else
        {
            self::$instance = new self($type,$path);
            return self::$instance;
        }
    }
            
    
    function get($param) {
        return $this->config_array[$param];
    }
    
    function set($key,$value) {
        $this->config_array[$key] = $value;
    }
    
    function keys()
    {
        return array_keys($this->config_array);
    }
    
    function entries()
    {
        return $this->config_array;
    }
    
    function has($key)
    {
        return array_key_exists($key,$this->config_array);
    }
    
    function save()
    {
        $this->$config_manager->save($this->config_array);
    }

}

?>
