<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 列表控制
 */
class ListController extends Controller {


    function __construct()
    {
        $config = array(
            'type' => 'redis',
            'password' => '6KGz$1mub',
            'db' => 1,

            );
        $cache = new Cache($config);
        $this->redis = $cache->getInstance();
    }

    /** 
     * 默认动作
     */
    public function get() {
        // echo 'null';
        // phpinfo();
        $d = $this->redis->get('list:test');

        
        
        $d = '{
"xsa":[{"name":"xxx"}],
"xsa2":[
{"name":"xxx2"},
{"name":"xxx22"},
]
}';
        $rtn = array(
            'code'=>1,
            'data'=>$d
            );
        echo json_encode($rtn);
    }

    public function put() {
        $params = array();
        $params_str  =  urldecode(file_get_contents('php://input'));
        $ps  =  explode('&', $params_str);
        foreach ($ps as $param) {
            $p = explode('=', $param);
            $params[$p[0]] = $p[1];
        }
        $this->redis->set('list:test',$params['data']);
        echo "ok";
    }

    public function delete() {
        echo 'null';
    }

}
