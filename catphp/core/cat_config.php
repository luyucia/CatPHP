<?php

/**
* 
*/
class CatConfig
{
    public static $instance;
    public static $configFile;
    public static $config;
    
    private function __construct($configFile)
    {
        self::$configFile = $configFile;
        self::$config     = require 'config/config.php';
    }

    public static function getInstance($configFile)
    {
        if (! (self::$instance instanceof self) || $configFile!=self::$configFile) {
            self::$instance = new self($configFile);
        }
        return self::$instance;
        
    }

    public function getConfig()
    {
        return self::$config;
    }

    public function __get($key)
    {
        return self::$config[$key];
    }

    private function __clone()
    {

    }
}


