<?php

/**
*
*/
class Cat_Table
{
    private $table   = null;
    private $prefix  = null;
    private $db      = null;
    private $dml     = null;

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
        return $this->db->execute($sql);
    }

    public function delete($where)
    {
        $sql = $this->dml->deleteSql($where);
        return $this->db->execute($sql);
    }

    public function update($data,$where)
    {
        $sql = $this->dml->updateSql($data,$where);
        return $this->db->execute($sql);
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
