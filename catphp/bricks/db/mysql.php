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
        $this->conn = mysqli_connect($config['host'] . ":" . $config['port'], $config['username'], $config['password'], $config['database']) or die('Could not connect: ' . mysql_error());
        mysqli_set_charset($this->conn, $config['encoding']);
    }

    private function checkSql($sql)
    {
        return mysql_real_escape_string($sql);
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function query($sql, $mode)
    {
        // $sql = $this->checkSql($sql);
        $result = mysqli_query($this->conn, $sql);

        if ($result) {
            $data = false;

            if ($mode == 0)
                $modeType = MYSQLI_ASSOC;
            else
                $modeType = MYSQLI_NUM;

            while ($row = mysqli_fetch_array($result, $modeType)) {
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
        mysqli_query($this->conn, $sql) or die("Invalid query: " . mysql_error());
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
        if ($result) {
            $data = mysqli_fetch_assoc($result);
        }

        return $data;
    }

}
