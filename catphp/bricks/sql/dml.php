<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of select
 *
 * @author luyu
 */

include_once 'sql.interface.php';
class dml implements sql{

    private $inteligent_type = false;
    private $dbtype;
    private $table;
    private $oracle_buff = '';
    private $mysql_buff  = '';
    private $mysql_parse_buff  = array();

    function __construct($table,$dbtype = 'mysql') {
        $this->dbtype     =  $dbtype;
        $this->table  =  $table;
    }

    function updateSql(&$d,$table,$where,$noquot='')
    {
        $noquot  = explode(',', $noquot);
        $values  = '';
        foreach ($d as $key => $value)
        {

            if($this->inteligent_type)
            {
                $values.="$key=".$this->type($value).',';
                continue;
            }

            if(is_string($value))
            {
                if(in_array($key, $noquot))
                {
                    $values.="$key=$value,";
                }
                else
                {
                    $values.="$key='$value',";
                }
            }
            else
            {
                $values.="$key=$value,";
            }
        }
        $values     = rtrim($values,',');
        return "update $table set $values where $where";
    }

    function insertSql(&$d,$noquot='')
    {
        $noquot  = explode(',', $noquot);
        $columns = '';
        $values  = '';
        foreach ($d as $key => $value) {
            $columns.="$key,";
            // 开启智能类型判定
            if($this->inteligent_type)
            {
                $values.= $this->type($value).',';
                continue;
            }

            if(is_string($value))
            {
                if(in_array($key, $noquot))
                {
                    $values.="$value,";
                }
                else
                {
                    $values.="'$value',";
                }
            }
            else
            {
                $values.="$value,";
            }
        }
        $columns    = rtrim($columns,',');
        $values     = rtrim($values,',');
        return "insert into {$this->table} ($columns) values($values)" ;


    }
    // 智能数据类型判定函数
    public function type($v)
    {
        if(is_string($v))
        {
            // 如果是字符类型的数字
            if (is_numeric($v))
            {
                if($v[0]=='0')
                {
                    return "'$v'";
                }
                else
                {
                    return $v;
                }
            }
            else // 如果是字符串
            {
                if(stripos($v,'.nextval' )!==false|| stripos($v,'sysdate' )!==false || preg_match('#\w+\(.*\)#', $v,$match)   )
                {
                    return $v;
                }
                else
                {
                    return "'$v'";
                }

            }
        }
        else
        {
            return $v;
        }

    }

    public function setInteligent($t=true)
    {
        $this->inteligent_type = $t;
    }

    public function deleteSql($table,$where)
    {
        return "delete from $table where $where";
    }

    public function to_string()
    {

    }

    public function add(&$data,$noquot='')
    {
        if ($this->dbtype=='oracle') {
           $this->oracle_buff.=$this->insertSql($data,$this->table,$noquot).';';
        }else {
            // 首次添加，解析数据类型，判定是否加引号
            if($this->mysql_buff=='') {
                $noquot  = explode(',', $noquot);
                $columns = '';
                $values  = '';
                foreach ($data as $key => $value) {
                    $columns.="$key,";

                    // 开启智能类型判定
                    if($this->inteligent_type) {
                        $values.= $this->type($value).',';
                        if($value[0]=="'") {
                            $this->mysql_parse_buff[$key] = true;
                        }else {
                            $this->mysql_parse_buff[$key] = false;
                        }
                    }
                    else {
                        if(is_string($value)) {
                            if(in_array($key, $noquot)) {
                                $values.="$value,";
                                $this->mysql_parse_buff[$key] = false;
                            }
                            else {
                                $values.="'$value',";
                                $this->mysql_parse_buff[$key] = true;
                            }
                        }
                        else {
                            $values.="$value,";
                            $this->mysql_parse_buff[$key] = false;
                        }
                    }
                }
                $columns    = rtrim($columns,',');
                $values     = rtrim($values,',');
                $this->mysql_buff =  "insert into {$this->table} ($columns) values($values)" ;
                } //非首次添加
                else {
                    $values = '';
                    foreach ($data as $key => $value) {
                        if($this->mysql_parse_buff[$key])
                            $values[] = "'$value'";
                        else
                            $values[] = "$value";
                    }
                    $this->mysql_buff.= ',('.implode(",", $values).')';
                }
            }

    }

    public function getInsert()
    {
        if ($this->dbtype=='oracle') {
            $sql = $this->oracle_buff;
            return $sql;
        }else {
            return $this->mysql_buff;
        }
    }


}


// $d1 = array(
//     'username' => 'luyu',
//     'password' => 'xxx',
//     'id' => 1,
//     'age' => '15',
//      );

// $d2 = array(
//     'username' => 'luyu',
//     'password' => 'xxx',
//     'id' => 2,
//     'age' => '14',
//      );



// $s = new Dml('user','mysql');
// // 设置智能解析
// // $s->setInteligent();
// $s->add($d1);
// $s->add($d2);
// echo $s->getInsert();
