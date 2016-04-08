<?php

/**
*
*/
class Cat_Table
{
    public $table   = null;
    public $prefix  = null;
    public $db      = null;
    public $dml     = null;

    function __construct($db,$table,$prefix='')
    {
        $this->table  = $table;
        $this->prefix = $prefix;

        $this->db  = $db;
        $this->dml = new Dml($prefix . $this->table);
        $this->select = new Select();
        $this->select->from($prefix . $this->table);

    }

    public function insert($data)
    {
        $sql = $this->dml->insertSql($data);
        $rtn = $this->db->execute($sql);
        if ($rtn>0) {
            return $this->db->getInsertId();
        }else{
            throw new Exception("Insert Failed. Sql:".$sql." Because:".$this->db->getLastError(), 1);
        }
    }

    public function delete($where)
    {
        $sql = $this->dml->deleteSql($where);
        $rs = $this->db->execute($sql);
        if ($rs===false) {
            throw new Exception("Delete Failed. Sql:".$sql." Because:".$this->db->getLastError(), 1);
        }else{
            return $rs;
        }

    }

    public function update($data,$where,$allow = false)
    {
        $handle_data = array();
        if (is_array($allow)) {
            foreach ($allow as $key) {
                if (isset($data[$key])) {
                    $handle_data[$key] = $data[$key];
                }
            }
        }else{
            $handle_data = $data;
        }
        $sql = $this->dml->updateSql($handle_data,$where);
        $rs = $this->db->execute($sql);
        if ($rs===false) {
            throw new Exception("Update Failed. Sql:".$sql." Because:".$this->db->getLastError(), 1);
        }else{
            return $rs;
        }
    }


    public function findOne($where,$column='*')
    {
        $sql = $this->_find($where,$column);
        return $this->db->getRow($sql);
    }

    public function findAll($where,$column='*')
    {
        $sql = $this->_find($where,$column);
        return $this->db->getData($sql);
    }

    public function query($sql){
        return $this->db->getData($sql);
    }

    public function queryOne($sql){
        return $this->db->getRow($sql);
    }

    private function _find($where,$column='*')
    {
        if (is_array($where)) {
            $this->select->column($column);
            foreach ($where as $key => $value) {
                $this->select->where($key,$value);
            }
            $sql =  $this->select->getSql();
        }else{
            $sql = "select $column from ".($this->prefix . $this->table)." where $where";
        }
        return $sql;
    }

}


?>
