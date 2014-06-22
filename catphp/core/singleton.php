<?php

/**
* 单例模式 基类 仅限PHP 5.3以上版本使用
*/
class Singleton
{
    public static $_instance;

    protected function __construct()
    {

    }

    public static function getInstance()
    {
        if (! (self::$_instance instanceof self)) {
            // self::$_instance = new self();
            // 仅限php5.3以上版本使用
            $class = get_called_class();
            self::$_instance = new $class();
        }else {
            return self::$_instance;
        }
    }

    private function __clone()
    {

    }
}
