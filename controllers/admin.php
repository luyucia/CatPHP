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
        var_dump($d);
        if ($d=='null' || $d==false) {
            $d = '{}';
        }

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
        $this->redis->set('doc:test:'.$this->getRequest('name'),$this->getRequest('data'));
        // print_r($this->getRequest());
        echo 'ok';
    }

}
