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
    private $conf;
    private $last_db_name = null;

    
    // public static function getInstance($config)
    // {
    //     echo "aaa:".var_dump($config!=$this->conf);
    //     if(!(self::$_instance instanceof self) || $config!=$this->conf){
    
    //         self::$_instance = new self($config);
    //     }

    //     return self::$_instance;
    // }
    
    // public function __clone()
 //    {
 //        trigger_error('Clone is not allowed.', E_USER_ERROR);
 //    }

    

    public  function __construct($config)
    {

        $this->conf          = $config;
        $this->last_db_name  = $config['database'];
        $this->conn = mysql_connect($this->conf['host'].":".$this->conf['port'], $this->conf['username'],$this->conf['password']) or die('Could not connect: ' . mysql_error());
        // try
        // {
        //     $this->conn = mysql_connect($this->conf['host'].":".$this->conf['port'], $this->conf['username'],$this->conf['password']);
            
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
        //         $this->conf = $config['backnode'];
        //         $this->conn = mysql_connect($this->conf['host'].":".$this->conf['port'], $this->conf['username'],$this->conf['password']) or die('Could not connect: ' . mysql_error());
        //     }
        //     else
        //     {
        //         throw new Exception('Can not connect with your mysql and you don`t have an backnode !');
        //     }
        // }

        $dbname = $this->conf['database'];

        mysql_set_charset($this->conf['encoding'],$this->conn);

        mysql_select_db($dbname, $this->conn) or die("Could not set $dbname: " . mysql_error());

    }

    

    

    public function getConnection()
    {
        return $this->conn;
    }

    

    public function query($sql)
    {
        $result = mysql_query($sql, $this->conn);
    
        if($result){

            $data=false;

            while($row = mysql_fetch_assoc($result)){

                $data[] = $row;

            }

            return $data;

        }else {

        return false;

        }

    }

    public function execute($sql)
    {
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

    





}