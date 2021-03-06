<?php

/**
* 单例模式的Config类,比每次都require_once效率高3倍
*/
class CatConfig
{
    public static $instance;
    public static $configFile;
    public static $config;

    private function __construct($configFile)
    {
        self::$configFile = $configFile;
        self::$config     = require $configFile;
    }

    public static function getInstance($configFile)
    {
        if (! (self::$instance instanceof self) || $configFile!=self::$configFile) {
            self::$instance = new self($configFile);
        }
        return self::$instance;

    }

    public function get($key)
    {
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }else{
            return null;
        }
    }

    public static function getConfig()
    {
        return self::$config;
    }

    public function __get($key)
    {
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }else{
            return null;
        }
    }

    private function __clone()
    {

    }
}


