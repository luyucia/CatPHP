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
    public function lookAction($name = "Stranger") {
        echo "<pre>";
        print_r($_GET);
    }

    public function indexAction($name = "Stranger") {

        $text = 
<<<EOT
# 欢迎使用CatPHP !
---------
EOT;
        $result = Parsedown::instance()->parse($text);
        // echo $result;
        $ip = new IpLocation();
        $loc = $ip->getlocation('61.148.17.34');
        echo $loc['country'];

        $this->assign('result',$result);
        $this->assign('items',array('<AAA>', 'B&B', '"CCC"'));
        $this->assign('title','hello');
        echo $this->render('views/table.phtml');
        $this->staticize('index.html');
    }

}
