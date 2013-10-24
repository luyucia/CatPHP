<?php

/**
 * 作者：卢禹
 * 日期：2012.7.10
 * 功能：Singleton模式的Mysql类，负责连接数据库，执行数据库操作，由Db_factory类对此进行实例化
 * 
 * 
 * */

class Mysql
{

	private $conn;
	private $conf;
	private static $last_db_name=null;
	protected static $_instance = null;
	
	public static function getInstance($db_name='default')
	{
		if(!(self::$_instance instanceof self)||$db_name!=self::$last_db_name){
	
			self::$_instance = new self($db_name);
		}

		return self::$_instance;
	}
	
	public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

	

	protected  function __construct($db_name)
	{

		$this->conf = Conf::$dbconfig[$db_name];
		$this->last_db_name=$db_name;
		try{
			$this->conn = mysql_connect($this->conf['host'].":".$this->conf['port'], $this->conf['username'],$this->conf['password']) or die('Could not connect: ' . mysql_error());
			if(!$this->conn)throw new Exception('your master is dead!now is backup database');
		}catch(Exception $e){
			$this->conf = Conf::$dbconfig[$db_name.'_bak'];
			$this->conn = mysql_connect($this->conf['host'].":".$this->conf['port'], $this->conf['username'],$this->conf['password']) or die('Could not connect: ' . mysql_error());
		}

		$dbname = $this->conf['dbname'];

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

	}

	

	public function getInsertId()
	{
		return mysql_insert_id($this->conn);
	}

	





}