<?php



abstract class Database
{
	abstract public static function getInstance($db_name='default');	abstract public function query($sql);	abstract public function execute($sql);	abstract public function getFieldName();	abstract public function getFieldType();	abstract public function getFieldSize();		abstract public function close();		abstract public function commit();		abstract public function getStatement();	abstract public function __clone();

} 