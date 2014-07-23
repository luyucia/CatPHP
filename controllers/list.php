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
        $d = $this->redis->get('list:test');
        $rtn = array(
            'code'=>1,
            'data'=>$d
            );
        echo json_encode($rtn);
    }

    public function put() {

        $this->redis->set('list:test',$this->getRequest('data'));
        echo $this->getRequest('data');
        echo "ok";
    }

    public function delete() {
        $this->redis->delete('list:test:',$this->getRequest('name'));
        echo "ok";
    }

}
