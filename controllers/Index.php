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
        echo $result;
        // $engine = new Tenjin_Engine();
        // $context = array(
        //     'title'=>'Bordered Table Example',
        //     'items'=>array('<AAA>', 'B&B', '"CCC"')
        //     ,'result'=>$result
        //          );
        // $output = $engine->render('table.phtml', $context);
        // echo $output;
    }
}
