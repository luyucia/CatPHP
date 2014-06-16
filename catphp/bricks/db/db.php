<?php

/**
 * @author 卢禹
 * @date 2012.7.10
 * @description 模型类，负责与数据库的交互
 * 数据访问对象模式
 * 
 * */

define('CLASSPATH', dirname(__FILE__));

require CLASSPATH.'/db_factory.php';
class Db{

    private $db_name;
    private $db;
    private $db_type;
    private $db_schema_name;
    /**
     * 
     * 构造函数，在dbfactory中根据数据库描述符取得数据库实例，如果没填数据库描述符默认的描述符为default。
     * @param str $dbname
     * 数据库描述符，就是在配置文件中写的数据库配置的键名
     */
    function __construct($db_config)
    {
            $this->config = $db_config;
            $this->db     = DbFactory::getDb($db_config);
            $this->conn   = $this->db->getConnection();
    }


    public function get_connection()
    {
        return $this->db->get_connection();
    }
    
     /**
     * 执行查询，select形式的查询，返回关联数组
     * model 0 关联数组 1 数字数组
     */
    public function getData($sql,$model=0)
    {
        return $this->db->getData($sql,$model);
    }

    public function getRow($sql)
    {
        return $this->db->getRow($sql);
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function fetch($rs,$model=0)
    {
        return $this->db->fetch_array($rs,$model);
    }

    /**
     * 
     * 取得字段名数组
     */
    public function getFieldName()
    {
        return $this->db->getFieldName();
    }
    /**
     * 
     * 取得字段的类型
     */
    public function getFieldType()
    {
        return $this->db->getFieldType();
    }
    /**
     * 
     * 取得字段大小
     */
    public function getFieldSize()
    {
        return $this->db->getFieldSize();
    }
    public function get_db_type()
    {
        return $this->db_type;
    }
    
    /**
     * 
     * 取得数据库名称
     */    
    public function get_db_schema_name()
    {
        return $this->db_schema_name;
    }
    /**
     * 
     * 关闭数据库连接
     */
    public function close()
    {
        $this->db->close();
    }
    /**
     * 
     * 提交事务
     */
    public function commit()
    {
        $this->db->commit();
    }
    /**
     * 
     * 取得statement对象
     */
    public function getStatement()
    {
        return $this->db->getStatement();
    }
    /**
     * 
     * 执行增删改类型的查询,比如update，insert，delete等
     * @param str $sql
     */
    public function execute($sql)
    {
        return $this->db->execute($sql);
    }
    
    /**
     *插入数据
     *@param str table
     *表名
     *@param array data
     *数据
     *@uses 例如：$data['username'] = 'luyu'; $data['age'] = 23;add('user',$data);
     */
    // public function add(&$data,$table)
 //    {
 //        $colums = '';
    //     $values = '';
    //     foreach ($data as $key => $value) {
    //         $colums .= $key.",";
    //         $values .= $this->type_change($value).",";
    //     }
    //     $colums = rtrim($colums,',');
    //     $values = rtrim($values,',');
    //     $sql = "insert into $table ($colums) values($values)";
    //     if (Conf::$DEBUG)
    //         echo $sql;
 //        return $this->execute($sql);
 //    }
    
 //    private function type_change(&$value)
    // {
    //     if (is_numeric($value) || preg_match('#^[\w\d_]*\(.*\)#' , $value)) {
    //         if(preg_match('#^0\d+#' , $value))
    //         {
    //             return "'$value'";
    //         }else
    //         {
    //             return $value;
    //         }
    //     }
    //     else if ($value=='' || $value==null) {
    //         return 'NULL';
    //     }
    //     else if(stripos($value, '.nextval')){
    //         return $value;
    //     }
    //     else
    //     {
    //         return "'$value'";
    //     }
         
    // }

    /**
     *更新数据
     *@param str table
     *表名
     *@param array data
     *数据
     *@param str where
     *条件
     *@uses 例如：$data['username'] = 'luyu'; $data['age'] = 23;add('user',$data);
     */
    // public function update($table,$data,$where)
    // {

    //     $sql = "UPDATE $table SET ";

    //     foreach ($data as $key => $value) {
    //         $sql.= "$key = ".$this->type_change($value).",";
    //     }

    //     $sql = rtrim($sql,',');
    //     $sql.=" WHERE ".$where;
    //     if (Conf::$DEBUG)
    //         echo $sql;
    //     return $this->execute($sql);
    // }
    
}
