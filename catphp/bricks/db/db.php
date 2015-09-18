<?php

/**
 * @author 卢禹
 * @date 2012.7.10
 * @description 模型类，负责与数据库的交互
 * 数据访问对象模式
 *
 * */

define('CLASSPATH', dirname(__FILE__));

require CLASSPATH . '/db_factory.php';

class Db
{

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
        $this->db = DbFactory::getDb($db_config);
        $this->conn = $this->db->getConnection();
    }


    public function get_connection()
    {
        return $this->db->get_connection();
    }

    /**
     * 执行查询，select形式的查询，返回关联数组
     * model 0 关联数组 1 数字数组
     */
    public function getData($sql, $model = 0)
    {
        return $this->db->getData($sql, $model);
    }

    public function getRow($sql)
    {
        $rtn = $this->db->getRow($sql);

        return  $rtn;
    }

    public function query($sql)
    {
        return $this->db->query($sql);
    }

    public function queryOne($sql)
    {
        return $this->db->queryOne($sql);
    }

    public function fetch($rs, $model = 0)
    {
        return $this->db->fetch_array($rs, $model);
    }

    public function getLastError()
    {
        return $this->db->getLastError();
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
        $rtn =  $this->db->execute($sql);
        if (!$rtn) {
            if(isset($this->config['debug']) && $this->config['debug']===true){
                echo $this->getLastError();
                echo "\nError SQL--> ".$sql;
            }
        }

        return $rtn;
    }

    public function getInsertId()
    {
        return $this->db->getInsertId();
    }

    /**
     *
     * 提交事物
     *
     */
    public function commit(){
        return $this->db->commit();
    }



}
