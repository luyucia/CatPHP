<?php
/**
 * 作者：卢禹
 * 日期：2012.7.10
 * 功能：Singleton模式的oracle模块，负责连接数据库，执行数据库操作，由Db_factory类对此进行实例化
 * 
 * 
 * */

class Oracle{

	protected static $_instance = null;
	
	private  $conf;
	private  $conn;
	private  $column_name;
	private  $column_type;
	private  $column_size;
	private  $statement;
	private static $db_name;
	
	public static function getInstance($db_name='default')
	{
		
		if(!self::$_instance instanceof self || self::$db_name!=$db_name){
			self::$_instance = new self($db_name);
		}else if(self::$_instance->conf!=Conf::$dbconfig[$db_name]){
			self::$_instance = new self($db_name);
		}
		return self::$_instance;
		
	}
	
	/**
     * 私有化的构造函数，根据配置信息创建了到Oracle数据库的持久连接
     *
     * @param str db_name
     * @return void
     */
	protected  function __construct($db_name)
	{
		//加载数据库配置信息
		$this->conf = Conf::$dbconfig[$db_name];
		$this->db_name=$db_name;
		putenv("ORACLE_HOME=/usr/lib/oracle/11.1/client64/lib");
		
		//连接到oracle数据库
		try{
			
			$this->conn = oci_new_connect($this->conf['username'],$this->conf['password'],$this->conf['host'].'/'.$this->conf['dbname'],$this->conf['encoding']) ;
			if(!$this->conn)throw new Exception('your master is dead!now is backup database');
		}catch(Exception $ee){
			
			$this->conf = Conf::$dbconfig[$db_name.'_bak'];
			$this->conn = oci_new_connect($this->conf['username'],$this->conf['password'],$this->conf['host'].'/'.$this->conf['dbname'],$this->conf['encoding']) ;
			if (!$this->conn) 
			{
			 $e = oci_error();
			 trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			}
		}
		
		


		
			//throw new Exception("Value must be 1 or below");
			//$this->conn = oci_pconnect($this->conf['username'],$this->conf['password'],$this->conf['host'].'/'.$this->conf['dbname'],$this->conf['encoding']) ;
			//如果连接失败则显示出错信息
				// if (!$this->conn) {
			 //    $e = oci_error();
			 //    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
			//}
		


	}
	
	/**
     * 执行查询，返回关联数组
     *
     * @param str sql
	 * sql语句
     * @return array
	 * 查询成功返回关联数组，失败返回false
     */
	public function query($sql,$model=5)
	{
		// ini_set('memory_limit', '4024m');
		$pst = oci_parse($this->conn, $sql);
		if (!$pst) {
		    $e = oci_error($this->conn);
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}

		$r = oci_execute($pst);
		if (!$r) {
		    $e = oci_error($pst);
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$this->statement=$pst;
		//如果查询成功	
		if($r)
			{
				$ncols = oci_num_fields($pst);//取得字段数量
			
			    for ($i = 1; $i <= $ncols; $i++) {
			        $column_name[]  = oci_field_name($pst, $i);//存入字段名
			        $column_type[]  = oci_field_type($pst, $i);//存入字段类型
			        $column_size[]  = oci_field_size($pst, $i);//存入字段大小
			    }
				$this->column_name =  $column_name;	
				$this->column_type =  $column_type;
				$this->column_size =  $column_size;	
				$data=false;
				while($row = oci_fetch_array($pst,$model))
				{
					$data[] = $row;
				}
				// $nrows = oci_fetch_all($pst, $data,0,-1,$flag);
				oci_free_statement($pst);
				return $data;
			}else 
			{
			return false;
			}

	}
	/**
     * 执行sql语句，不返回结果集
     *
     * @param str sql
	 * sql语句
     * @return array
     */
	public function execute($sql)
	{
		$pst = oci_parse($this->conn, $sql);
		if (!$pst) {
		    $e = oci_error($this->conn);
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		
		$r = oci_execute($pst);
		if (!$r) {
		    $e = oci_error($pst);
		    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
		}
		$this->statement=$pst;
		return $r;
	}
	
	/**
     * 得到字段名称
     */
	public function getFieldName()
	{
		return $this->column_name;
	}
	
	/**
     * 得到字段类型
     */
	public function getFieldType()
	{
		return $this->column_type;
	}
	
	/**
     * 得到字段大小
     */
	public function getFieldSize()
	{
		return $this->column_size;
	}
	
	/**
     * 关闭数据库连接
     */
	public function close(){
		oci_close($this->conn);
	} 
	
	/**
     * 提交事物
     */
	public function commit()
	{
		$committed = oci_commit($this->conn);
		if (!$committed) {
        $error = oci_error($conn);
        echo 'Commit failed. Oracle reports: ' . $error['message'];
    	}
		
	}
	/**
     * 获取statement对象
     */
	public function getStatement()
	{
		return $this->statement;
	}

	public function __clone()
    {
        trigger_error('Clone is not allowed.', E_USER_ERROR);

    }

    public function get_connection()
    {
    	return $this->conn;
    }

}