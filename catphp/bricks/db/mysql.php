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
        if (!(self::$_instance instanceof self) || $config != self::$_conf) {
            self::$_conf = $config;
            self::$_instance = new self($config);
        }

        return self::$_instance;
    }

    public function __construct($config)
    {
        $this->last_db_name = $config['database'];
        $this->conn = mysqli_connect($config['host'] , $config['username'], $config['password'], $config['database'],$config['port']) or die('Could not connect: ' . mysqli_connect_error());
        mysqli_set_charset($this->conn, $config['encoding']);
    }

    // private function checkSql($sql)
    // {
    //     return mysqli_real_escape_string($sql);
    // }

    public function getConnection()
    {
        return $this->conn;
    }

    public function query($sql, $mode = 0)
    {
        // $sql = $this->checkSql($sql);
        $result = mysqli_query($this->conn, $sql);
        $data   = array();
        if ($result && !is_bool($result)) {

            if ($mode == 0)
                $modeType = MYSQLI_ASSOC;
            else
                $modeType = MYSQLI_NUM;

            while ($row = mysqli_fetch_array($result, $modeType)) {
                $data[] = $row;
            }
            mysqli_free_result($result);
            return $data;
        }

        return $data;
    }

    public function execute($sql)
    {
        //$sql = $this->checkSql($sql);
        $rtn = false;
        $stmt = mysqli_prepare($this->conn, $sql);
        if ($stmt) {
            $rtn =  mysqli_stmt_execute($stmt);
        }
        $this->lastError = mysqli_error($this->conn);

        return $rtn;
        // return mysqli_query($this->conn, $sql);
        // or die("Invalid query: " . mysql_error());
    }

    public function close()
    {
        mysqli_close($this->conn);
    }

    public function getInsertId()
    {
        return mysqli_insert_id($this->conn);
    }

    public function getData($sql, $mode = 0)
    {
        return $this->query($sql, $mode);
    }

    public function getRow($sql)
    {
        $result = mysqli_query($this->conn, $sql);

        $data = false;
        // 查询错误
        if( $result === false && is_bool($result) ){
            if(isset(self::$_conf['debug']) && self::$_conf['debug']===true){
                $this->lastError = mysqli_error($this->conn);
                echo $this->lastError ;
            }
        }
        elseif ($result->num_rows != 0) {
            $data = mysqli_fetch_assoc($result);
        }

        return $data;
    }

    public function commit()
    {
        mysqli_commit($this->conn);
    }

    public function getLastError()
    {
        return $this->lastError;
    }

}
