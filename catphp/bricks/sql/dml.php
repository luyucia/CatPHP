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

class dml{

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

    function updateSql(&$d,$where,$noquot='')
    {
        $noquot  = explode(',', $noquot);
        $values  = '';
        foreach ($d as $key => $value)
        {
            if ($value===false) {
                continue;
            }

            if($this->inteligent_type)
            {
                $values.="`$key`=".$this->type($value).',';
                continue;
            }

            if ($value===null) {
                $values.="`$key`= null,";
                continue;
            }

            if(is_string($value))
            {
                if(in_array($key, $noquot))
                {
                    $values.="`$key`=$value,";
                }
                else
                {
                    $values.="`$key`='$value',";
                }
            }
            else
            {
                $values.="`$key`=$value,";
            }
        }
        $values     = rtrim($values,',');
        if (is_array($where)) {
            return "update {$this->table} set $values where ".$this->handleWhere($where);
        }else{
            return "update {$this->table} set $values where $where";
        }
    }

    function insertSql(&$d,$noquot='')
    {
        $noquot  = explode(',', $noquot);
        $columns = '';
        $values  = '';
        foreach ($d as $key => $value) {

            if ($value===false) {
                continue;
            }
            $columns.="`$key`,";
            // 开启智能类型判定
            if($this->inteligent_type)
            {
                $values.= $this->type($value).',';
                continue;
            }

            if ($value===null) {
                $values.="`$key`= null,";
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

    public function deleteSql($where)
    {
        return "delete from {$this->table} where $where";
    }

    public function handleWhere($where)
    {
        $where_arr = array();
        foreach ($where as $key => $value) {
            if (is_array($value)) {
                $where_arr[] = "$key in ('".implode("','", $value)."')";
            }else{
                $where_arr[] = "$key=".$this->type($value);
            }
        }
        return implode(' and ', $where_arr);
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

    public function BatchInsertSql()
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

// // 构造insert语句
// echo $s->insertSql($d1);
// // insert into user (username,password,id,age) values('luyu','xxx',1,'15')

// // 构造update语句
// echo $s->updateSql($d1,'id=1');
// // update user set username='luyu',password='xxx',id=1,age='15' where id=1

// // 构造delete语句
// echo $s->deleteSql('id=1');
// // delete from user where id=1

// // 构造批量插入sql
// $s->add($d1);
// $s->add($d2);
// echo $s->BatchInsertSql();
// // insert into user (username,password,id,age) values('luyu','xxx',1,'15'),('luyu','xxx',2,'14')
