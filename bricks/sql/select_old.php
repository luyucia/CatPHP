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

include 'sql.interface.php';
class select implements sql{
    
    private $table = NULL;
    private $columns = '*';
    private $join  = NULL;
    private $where  = NULL;     
    
    function from($table) 
    {
        $this->table = $table;
        return $this;
    }
    
    function join($join) 
    {
        if($this->join===NULL)
        {
             $this->join = $join;
        }else
        {
            $this->join .= '  '.$join;
        }
        return $this;
       
    }
    
    function column($columns) 
    {
        
        if (empty($columns)) 
        {
            return $this;
        }
        // empty the columns
        if ($this->columns=='*') {
            $this->columns = '';
        }
        // if is the second add columns add the ','
        if(!empty($this->columns)){
            $this->columns.=',';
        }
        // if is array
        if(is_array($columns))
        {
            $this->columns.= implode(',', $columns);
        }  
        else 
        {
            $this->columns.= $columns;
        }
        
        
        return $this;
    }
    
    function where($col,$val,$cond='=',$logic='and',$quot='default') 
    {
        
        if(is_array($val))
        {
            if(empty($val)) return $this;
            else
            {
                if (is_string($val[0])) 
                {
                    $val = ' (\''.implode("','", $val).'\') ';
                }
                else
                {
                    $val = ' ('.implode(",", $val).') ';
                }
                $quot = false;
            }
            
        }

        // 处理单引号
        if($quot===true)
        {
            $val = "'$val'";
        }
        else if($quot===false)
        {

        }
        else{
            $val = $this->dect_type($val);
            if(!$val)return $this;
        }
            
        
        if($this->where==NULL)
        {
            $this->where = "$col $cond $val";
        }  
        else
        {
            $this->where .= " $logic $col $cond $val";
        }
        return $this;
    }
    
    private function dect_type($var)
    {
        if($var==''|| !isset($var))
        {
            return false;
        }
        
        if(is_string($var))
        {
            return "'$var'";
        }
        return $var;
    }
            
    function groupby() 
    {
        
    }
    
    function having() 
    {
        
    }
    
    function orderby() 
    {
        
    }
    
    function to_string() 
    {
        $sql = 'select '.$this->columns.' from '.$this->table;
        $this->join === NULL?$sql:$sql.=' '.$this->join;
        $this->where===NULL?$sql:$sql.=' where '.$this->where;
        return $sql;
    }
    
    function page()
    {
        
    }
}

?>
