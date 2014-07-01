<?php

class select{

    private $from      = '';
    private $columns   = ' * ';
    private $join      = '';
    private $groupby   = '';
    private $orderby   = '';
    public  $where_arr = array();

    public function from($s)
    {
        $this->from = $s;
    }


    public function column($columns)
    {
        if (is_array($columns)) 
        {
            if (count($columns)) {
                $this->columns = implode(',', $columns);
            }
        }
        else
        {
            $this->columns = $columns;
        }
    }

    // like in 
    public function where($column,$value,$cond='=',$quot=null,$logic='and')
    {
        // 如果quor为null则调用type自动判断是否加单引号
        if($quot===null)
        {
            $value = $this->type($value);
        }
        else if($quot==true)
        {
            $value = "'$value'";
        }

        $this->where_arr[$column][]=" $logic $column $cond $value";
    }
    // 删除条件
    public function remove_where($column)
    {
        unset($this->where_arr[$column]);
    }
    // 获取条件
    public function get_where($column)
    {
        return implode(' ', $this->where_arr[$column]) ;
    }
    // 根据类型判定是否加单引号
    private function type($v)
    {
        if(is_string($v))
        {
            return "'$v'";
        }
        else if(is_array($v))
        {
            if(is_numeric($v[0]))
            {
                return '('.implode(",", $v).')';
            }
            else
            {
                return '(\''.implode("','", $v).'\')';
            }
            
        }
        else
        {
            return $v;
        }
    }

    public function join($str)
    {
        $this->join = ' '.$str;
    }

    public function groupby($columns)
    {
        
        if (is_array($columns)) 
        {
            $this->groupby = ' group by '.implode(',', $columns);
        }
        else
        {
            $this->groupby = ' group by '.$columns;
        }
    }

    public function orderBy($columns)
    {
        
        if (is_array($columns)) 
        {
            $this->orderby = ' order by ';
            foreach ($columns as $key => $value) {
                $this->orderby .= "$key $value,";
            }
            $this->orderby = rtrim($this->orderby,",");
        }
        else
        {
            $this->orderby = ' order by '.$columns;
        }
    }

    public function getSql()
    {
        $where ='';
        $t     = '';
        foreach ($this->where_arr as $where) {
            $t.= implode(' ', $where);
        }
        if($t!='')
            $where = ' where '.substr($t, 4);
        
        return 'select '.$this->columns.
               ' from ' .$this->from.$this->join.
               $where.$this->groupby.$this->orderby;


        ;
    }


    public function page_sql($page,$per_age,$dbtype='mysql')
    {
        $low = $page*$per_age;
        $ori_sql = $this->to_string();
        if($dbtype=='mysql' or $dbtype=='postgresql')
        {
            return $ori_sql." limit $low $per_age";
        }
        else if($dbtype='oracle')
        {
            return $ori_sql;
        }
    }


}