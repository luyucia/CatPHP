<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class indexController extends Controller {

    /** 
     * 默认动作
     */
    public function index($name = "Stranger") {
        echo "<h1>Welcome to Use CatPHP</h1>";
        $this->assign("title",'Welcome');
        $this->render('views/index.tpl');


    }

}
