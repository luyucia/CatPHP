<?php

require 'catphp.php';

// $s = new select();
// echo $s->to_string();

// $kint = new Kint();
// Kint::trace();




// 连接测试
// 1、连接主数据库，配置正确
$dbconfig = array(
	'type'=>'mysql',
	'host'=>'127.0.0.1',
	'username'=>'root',
	'password'=>'',
	'database'=>'pet',
	'port'=>'3306',
	'encoding'=>'utf8'
	);

$db = new Db($dbconfig);
$data = $db->getData('select * from users');
if($data)
{
	echo 'ok';
}
// 2、连接主数据库，配置错误
// $dbconfig = array(
// 	'type'=>'mysql',
// 	'host'=>'127.0.0.1',
// 	'username'=>'root2',
// 	'password'=>'',
// 	'database'=>'pet',
// 	'port'=>'3306',
// 	'encoding'=>'utf8'
// 	);
// try {
// $db = new Db($dbconfig);
// $data = $db->query('select * from users');
// if($data)
// {
// 	echo 'ok';
// }
// } catch (Exception $e) {
// 	echo $e;
// }

// 3、连接主数据库失败，连接从数据库
// $backdb = array(
// 	'type'=>'mysql',
// 	'host'=>'127.0.0.1',
// 	'username'=>'root',
// 	'password'=>'',
// 	'database'=>'pet',
// 	'port'=>'3306',
// 	'encoding'=>'utf8'
// 	);
// $dbconfig = array(
// 	'type'=>'mysql',
// 	'host'=>'127.0.0.1',
// 	'username'=>'root2',
// 	'password'=>'',
// 	'database'=>'pet',
// 	'port'=>'3306',
// 	'encoding'=>'utf8',
// 	'backnode'=>$backdb 
// 	);

// $db = new Db($dbconfig);
// $data = $db->query('select * from users');
// if($data)
// {
// 	echo 'ok';
// }
// 4、同时连接两个数据库，并查询
$dbconfig = array(
	'type'=>'mysql',
	'host'=>'127.0.0.1',
	'username'=>'root',
	'password'=>'',
	'database'=>'pet',
	'port'=>'3306',
	'encoding'=>'utf8'
	);
$dbconfig2 = array(
	'type'=>'mysql',
	'host'=>'127.0.0.1',
	'username'=>'root',
	'password'=>'',
	'database'=>'pet2',
	'port'=>'3306',
	'encoding'=>'utf8'
	);
$db1 = new Db($dbconfig);
$data = $db1->getData('select * from users');
// var_dump($data);
// 需要连不同库才行
$db2 = new Db($dbconfig2);
$data = $db2->getData('select * from users');
// var_dump($data);


// 操作测试
// 1、查询数据，关联数组形式
$db = new Db($dbconfig);
$data = $db->getData('select * from users');
// var_dump($data);
// 2、查询数据，数组形式
$data = $db->getData('select * from users',1);
// var_dump($data);

$rs = $db->query('select * from users');

while ($row = $db->fetch($rs)) {
	echo $row['id'];
}
// 3、插入操作
// 4、大量插入操作
// 5、关闭数据库
$db->close();


// $data = $db->query('select * from users');
// $db->execute("insert into users (username) values('luyu')");
// var_dump($data);
// print_r($data);

?>