<?php

/**
 * 作者：武哲
 *
 *
 * */
class MongoDriver
{


    private $conn;
    private static $_conf;
    private static $_instance;

    public static function getInstance($config)
    {
        if (!(self::$_instance instanceof self) || $config != self::$_conf) {
            self::$_conf = $config;
            self::$_instance = new self($config);
        }

        return self::$_instance;
    }

    public function __construct($config)
    {
        $this->conn = new MongoClient('mongodb://'.$config['username'].':'.$config['password'].'@'.$config['host'].':'.$config['port'].'/'.$config['database']);
    }

    public function getConnection(){
        return $this->conn;
    }

}
