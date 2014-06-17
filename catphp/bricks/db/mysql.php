<?php

/**
 * 作者：卢禹
 * 日期：2012.7.10
 * 功能：Singleton模式的Mysql类，负责连接数据库，执行数据库操作，由Db_factory类对此进行实例化
 * 
 * 
 * */

class MysqlDriver
{


    private $conn;
    private static $_conf;
    private $last_db_name;
    private static $_instance;

    public static function getInstance($config)
    {
         if(!(self::$_instance instanceof self) || $config != self::$_conf){
             self::$_conf = $config;
             self::$_instance = new self($config);
         }

         return self::$_instance;
     }

    // public function __clone()
    //    {
    //        trigger_error('Clone is not allowed.', E_USER_ERROR);
    //    }

    public  function __construct($config)
    {
        $this->last_db_name  = $config['database'];
        $this->conn = mysql_connect($config['host'] . ":" . $config['port'], $config['username'], $config['password']) or die('Could not connect: ' . mysql_error());
        // try
        // {
        //     $this->conn = mysql_connect($config['host'].":".$config['port'], $config['username'],$config['password']);

        //     if(!$this->conn)
        //     {
        //         throw new Exception('Can not connect with your mysql');
        //     }

        // }
        // catch(Exception $e)
        // {
        //     echo 'sdfadfas';
        //     if(isset($config['backnode']))
        //     {
        //         $config = $config['backnode'];
        //         $this->conn = mysql_connect($config['host'].":".$config['port'], $config['username'],$config['password']) or die('Could not connect: ' . mysql_error());
        //     }
        //     else
        //     {
        //         throw new Exception('Can not connect with your mysql and you don`t have an backnode !');
        //     }
        // }

        $dbname = $config['database'];

        mysql_set_charset($config['encoding'],$this->conn);

        mysql_select_db($dbname, $this->conn) or die("Could not set $dbname: " . mysql_error());
    }

    private function checkSql($sql)
    {
        return mysql_real_escape_string($sql);
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function query($sql)
    {
        $sql = $this->checkSql($sql);
        $result = mysql_query($sql, $this->conn);

        if ($result) {
            $data = false;
            while ($row = mysql_fetch_assoc($result)) { 
                $data[] = $row;
            }
            return $data;
        } else {
            return false;
        }
    }

    public function execute($sql)
    {
        $sql = $this->checkSql($sql);
        mysql_query($sql) or die("Invalid query: " . mysql_error());
    }

    public function close()
    {
        mysql_close($this->conn);
    }

    public function getInsertId()
    {
        return mysql_insert_id($this->conn);
    }

    public function getData($sql)
    {
        return $this->query($sql);
    }

    public function getRow($sql)
    {
        $result = mysql_query($sql, $this->conn);

        $data = false;
        if ($result) {
            $data = mysql_fetch_assoc($result);
        }

        return $data;
    }

}
