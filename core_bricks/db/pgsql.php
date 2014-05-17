<?php
/**
 * 作者：卢禹
 * 日期：2012.7.10
 * 功能：Singleton模式的Pgsql模块，负责连接数据库，执行数据库操作，由Db_factory类对此进行实例化
 * 
 * 
 * */

class Pgsql{

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
		
		//连接到postgresql数据库
		try{
			$this->conn = pg_connect("host=".$this->conf['host']." port=".$this->conf['port']." dbname=".$this->conf['dbname']." user=".$this->conf['username']." password=".$this->conf['password']);
			
			if(!$this->conn)throw new Exception('your master is dead!now is backup database');
		}catch(Exception $ee){
			
			$this->conf = Conf::$dbconfig[$db_name.'_bak'];
			$this->conn = pg_connect("host=".$this->conf['host']." port=".$this->conf['port']." dbname=".$this->conf['dbname']." user=".$this->conf['username']." password=".$this->conf['password']);
			if (!$this->conn) 
			{
			}
		}


	}
	
	/**
     * 执行查询，返回关联数组
     *
     * @param str sql
	 * sql语句
     * @return array
	 * 查询成功返回关联数组，失败返回false
     */
	public function query($sql,$model=1)
	{
		$pst = pg_query($this->conn, $sql);

		//$this->statement=$pst;
		//如果查询成功	
		if($pst)
			{
				$ncols = pg_num_fields($pst);//取得字段数量
			
			    for ($i = 0; $i < $ncols; $i++) {
			        $column_name[]  = pg_field_name($pst, $i);//存入字段名
			        $column_type[]  = pg_field_type($pst, $i);//存入字段类型
			        $column_size[]  = pg_field_size($pst, $i);//存入字段大小
			    }
				$this->column_name =  $column_name;	
				$this->column_type =  $column_type;
				$this->column_size =  $column_size;	
				$data=false;
				if($model==1)
				{
					while($row = pg_fetch_assoc($pst))
					{
						$data[] = $row;
					}
				}else
				{
					while($row = pg_fetch_array($pst))
					{
						$data[] = $row;
					}
				}
				
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

		$r = pg_query($this->conn,$sql);
		// $st = pg_prepare($this->conn, "nns_exe_sql", $sql);


		// $r = pg_execute($this->conn,'nns_exe_sql',array());

		if (!$r) {
		  echo "error";
		}
		
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
		pg_close($this->conn);
	} 
	
	/**
     * 提交事物
     */
	public function commit()
	{
		
		
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