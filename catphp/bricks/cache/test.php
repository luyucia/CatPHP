<?php

include 'cache.php';

$config = array(
    'type' => 'redis',
    'password' => '6KGz$1mub',
    'db' => 1,

    );
$cache = new Cache($config);

echo "设置缓存";
$cache->set('user','ffff');
echo "获取缓存";
var_dump($cache->get('user'));
echo "删除缓存";
$cache->delete('user');
echo "查看所有key";
print_r($cache->keys());
$cache->set('user1','111');
$cache->set('user2','222');
echo "设置缓存后查看所有key";
print_r($cache->keys());
$cache->clean();
echo "清除缓存后后查看所有key";
print_r($cache->keys());

echo "性能测试";
echo "10万次写入";
$a = microtime(true);
for ($i=0; $i < 100000; $i++) { 
    $cache->set($i,'ffff');
}
echo microtime(true)-$a;
echo "\n";
echo 100000/(microtime(true)-$a);
echo "\n";

echo "10万次读取";
$a = microtime(true);
for ($i=0; $i < 100000; $i++) { 
    $cache->get($i);
}
echo microtime(true)-$a;
echo "\n";
echo 100000/(microtime(true)-$a);
echo "\n";
$cache->clean();

?>