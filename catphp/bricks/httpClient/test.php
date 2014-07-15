<?php
include 'httpClient.php';

$http = new HttpClient();

$http->setAgent("hi");
echo $http->get("http://api.cube.xoyo.com/operate_analysis_consume", array('table' => 'consume_dist', 'field[]' => 'percentage'));

echo "post to cube";
echo $http->post("http://api.cube.xoyo.com/operate_analysis_consume", array('null'));

echo "get to cube";
echo $http->get("http://api.cube.xoyo.com/operate_analysis_consume", array('table' => 'consume_dist', 'field[]' => 'percentage'));

echo "性能测试";
echo "10次get请求";
$a = microtime(true);
for ($i=0; $i < 10; $i++) {
    $http->get("http://api.cube.xoyo.com/operate_analysis_consume", array('table' => 'consume_dist', 'field[]' => 'percentage'));
}
echo microtime(true)-$a;
?>