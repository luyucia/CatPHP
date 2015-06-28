<?php

class select{

    private $from      = '';
    private $columns   = ' * ';
    private $join      = '';
    private $having    = array();
    private $groupby   = '';
    private $orderby   = '';
    public  $where_arr = array();

    public function __construct(){
        // error_reporting(E_ALL ^ E_STRICT);
    }

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

    public function having($column, $value, $cond = '=',$quot = null, $logic = 'and'){
        if(!isset($value) || $value==='')
            return;
        if($quot===null)
        {
            $value = $this->type($value);
        }
        else if($quot==true)
        {
            $value = "'$value'";
        }

        $this->having[$column][] = " $logic $column $cond $value";
    }

    // like in
    public function where($column,&$value,$cond='=',$quot=null,$logic='and')
    {
        if(!isset($value) || $value==='' || $value===false)
            return;
        if (is_array($value) && $cond=='=') {
            $cond = 'in';
        }
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
        $this->join .= ' '.$str;
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
            if(count($columns)) {

                $this->orderby = ' order by ';
                foreach ($columns as $key => $value) {
                    $this->orderby .= "$key $value,";
                }
                $this->orderby = rtrim($this->orderby,",");
            }
        }
        else if($columns!='' && is_string($columns))
        {
            $this->orderby = ' order by '.$columns;
        }
    }

    public function getSql()
    {
        $where = $having = '';
        $t = $h = '';
        foreach ($this->where_arr as $where) {
            $t.= implode(' ', $where);
        }
        if($t!='')
            $where = ' where '.substr($t, 4);

        foreach($this->having as $hav){
            $h .= implode(' ', $hav);
        }
        if($h != ''){
            $having = 'having '.substr($h, 4);
        }

        return 'select '.$this->columns.
               ' from ' .$this->from.$this->join.
               $this->securityFilter($where.$this->groupby.$having.$this->orderby);


        ;
    }


    public function pageSql($page,$per_age,$dbtype='mysql')
    {
        $low = ($page-1)*$per_age;
        $ori_sql = $this->getSql();
        if($dbtype=='mysql' or $dbtype=='postgresql')
        {
            return $ori_sql." limit $low,$per_age";
        }
        else if($dbtype='oracle')
        {
            return $ori_sql;
        }
    }

    public function totalPageSql($dbtype='mysql')
    {
        $ori_sql = $this->getSql();
        if($dbtype=='mysql' or $dbtype=='postgresql')
        {
            // return str_replace($this->columns, ' count(*) as total', $ori_sql );
            // 在有group by的情况下会有问题.所以改成子查询方式
            return 'select count(*) as total from ('.$ori_sql.') as tmp';
        }
        else if($dbtype='oracle')
        {
            return $ori_sql;
        }
    }


    private function securityFilter($str){
        return preg_replace('/if\(|select |update |insert |union|/', '', $str);
    }


}

// $s = new Select();
// // 设置表名
// $s->from('tablename a');
// // 设置要选择的字段
// $s->column('a.id,a.name,a.city,a.time');
// // 添加条件
// // foreach ($condition as $key => $value) {
// //     $s->where($key,$value);
// // }
// // 排序
// $s->orderBy(urldecode('a.time desc ,if(116>115),1,(select%201%20from%20information_schema.tables))') );
// echo $s->getSql();
// // 获取带分页的sql语句
// // $sql = $s->PageSql($page,$pagesize);
// // 获取总数的sql语句
// // $total_sql = $s->totalPageSql();
