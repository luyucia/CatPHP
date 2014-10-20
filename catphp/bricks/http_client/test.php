<?php
include 'http_client.php';

$http = new HttpClient();

$http->setAgent("hi");
// echo $http->get("http://api.cube.xoyo.com/operate_analysis_consume", array('table' => 'consume_dist', 'field[]' => 'percentage'));

// echo "post to cube";
// echo $http->post("http://api.cube.xoyo.com/operate_analysis_consume", array('null'));

// echo "get to cube";
// echo $http->get("http://api.cube.xoyo.com/operate_analysis_consume", array('table' => 'consume_dist', 'field[]' => 'percentage'));

$data['token'] = md5('a23h7!'.date('YmdH'));
$data['gameid'] = 11;

echo $http->get('api.center.xoyo.com/dim/server',$data);
echo $http->post('api.center.xoyo.com/dim/server',$data);
$data['gameid'] = 1;
echo $http->get('api.center.xoyo.com/dim/server',$data);
echo $http->post('api.center.xoyo.com/dim/server',$data);
echo "性能测试";
echo "1000次get请求";
$a = microtime(true);
for ($i=0; $i < 100; $i++) {
    // $http->get("http://api.cube.xoyo.com/operate_analysis_consume", array('table' => 'consume_dist', 'field[]' => 'percentage'));
    $http->get('api.center.xoyo.com/dim/server',$data);
    $http->post('api.center.xoyo.com/dim/server',$data);
}
echo microtime(true)-$a;





        // for ($i=0; $i < 10000; $i++) { 
        // $ch = curl_init();
        // $timeout = 10;
        //     # code...
        //     curl_setopt($ch, CURLOPT_URL, '10.234.10.17');
        //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //     curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);

        //     // if ($this->_method == 'post') {
        //     //     curl_setopt($ch, CURLOPT_POST, 1);
        //     //     curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->_data));
        //     // }

            
        //     $result = curl_exec($ch);
        //     if ($result === false) {
        //         throw new Exception(curl_error($ch));
        //     }
        // curl_close($ch);
        // }
?>