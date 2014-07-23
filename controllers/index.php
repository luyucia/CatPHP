<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class IndexController extends Controller {

    /** 
     * 默认动作
     */
    public function cache($name = "Stranger") {
        phpinfo();
        $config = array(
            'type' => 'redis',
            'password' => '6KGz$1mub',
            'db' => 1,

            );
        $cache = new Cache($config);

        $d = $cache->get('list:test');

        echo "设置缓存";
        $cache->set('user','ffff');
        echo "获取缓存";
        var_dump($cache->get('user'));
    }

    public function index($name = "Stranger") {

//         $text = 
// <<<EOT
// # 欢迎使用CatPHP !
// ---------
// EOT;
//         $result = Parsedown::instance()->parse($text);
//         // echo $result;
//         $ip = new IpLocation();
//         $loc = $ip->getlocation('61.148.17.34');
//         echo $loc['country'];

//         $this->assign('result',$result);
//         $this->assign('items',array('<AAA>', 'B&B', '"CCC"'));
//         $this->assign('title','hello');

        $config = array(
            'type' => 'redis',
            'password' => '6KGz$1mub',
            'db' => 1,

            );
        $cache = new Cache($config);
        // $cache->clean();
        $this->redis = $cache->getInstance();
        $d = $this->redis->get('list:test');
        // var_dump($d);
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
        echo $this->render('views/index.html');
        // $this->staticize('index.html');
    }

}
