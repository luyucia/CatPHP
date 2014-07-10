<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class AdminController extends Controller {




    /** 
     * 默认动作
     */
    public function get() {

         $config = array(
            'type' => 'redis',
            'password' => '6KGz$1mub',
            'db' => 1,

            );
        $cache = new Cache($config);
        
        $this->redis = $cache->getInstance();
        $d = $this->redis->get('list:test');
        if (isset($_GET['last'])) {
            $doc = $this->redis->get('doc:test:'.$_GET['a'].':'.$_GET['last']);
            $doc_name = $_GET['a'].':'.$_GET['last'];
        }else {
            $doc = $this->redis->get('doc:test:'.$_GET['a']);
            $doc_name = $_GET['a'];
        }

        if (!$doc) {
            $doc='';
        }
        $this->assign('menu',$d);
        $this->assign('doc',$doc);
        $this->assign('doc_name',$doc_name);
        echo $this->render('views/admin.html');
    }

    public function put(){
        $config = array(
            'type' => 'redis',
            'password' => '6KGz$1mub',
            'db' => 1,

            );
        $cache = new Cache($config);
        
        $this->redis = $cache->getInstance();
        $params = array();
        $params_str  =  urldecode(file_get_contents('php://input'));
        $ps  =  explode('&', $params_str);
        foreach ($ps as $param) {
            $p = explode('=', $param);
            $params[$p[0]] = $p[1];
        }
        $this->redis->set('doc:test:'.$params['name'],$params['data']);
        echo $params['name'];
    }

}
