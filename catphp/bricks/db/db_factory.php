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
    static public function getDb($config)
    {
        $db_type = strtolower($config['type']);

        if($db_type=='mysql')
        {
            require_once CLASSPATH.'/mysql.php';
            //return new MysqlDriver($config);
            return MysqlDriver::getInstance($config);
        }
        else if($db_type=='oracle')
        {
            require_once CLASSPATH.'/oracle.php';
            return Oracle::getInstance($config);
        }
        else if($db_type=='postgre')
        {
            require_once CLASSPATH.'/pgsql.php';
            return Pgsql::getInstance($config);
        }
        else 
        {
            echo "The type $db_type is not support by catPHP yet,sorry ^_^";
            return null;
        }
        
    }

    
}
