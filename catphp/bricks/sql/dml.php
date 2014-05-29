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

    function update_sql(&$d,$table,$where,$noquot='')
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
        return "update set $values where $where";
    }

    function insert_sql(&$d,$table,$noquot='')
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
        return "insert into $table ($columns) values($values)" ;


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

    public function set_inteligent($t=true)
    {
        $this->inteligent_type = $t;
    }

    public function delete_sql($table,$where)
    {
        return "delete from $table where $where";
    }

    public function to_string()
    {

    }

    
}

