<?php

/**
 * 作者：卢禹
 * 日期：2012.7.10
 * 功能：数据库工厂方法，负责实例化不同类型的数据库类
 * 
 * 
 * */


class DbFactory{

	/**
	 * 
	 * 根据数据库描述符生成相应的数据库对象
	 * @param str $dbname
	 */
	static public function getDb($dbname)
	{

		if(isset(Conf::$dbconfig[$dbname]['type'])){
			$db_type = Conf::$dbconfig[$dbname]['type'];
			$db_type = strtolower($db_type);
			if($db_type=='oracle'){
				return Oracle::getInstance($dbname);
			}
			else if($db_type=='mysql'){
				return Mysql::getInstance($dbname);
				}
			else if($db_type=='postgre'){
				return Pgsql::getInstance($dbname);
			}
			else {
				echo "The type $db_type is not support by catPHP yet,sorry ^_^";
				return null;
			}
		}
		
	}

	
}