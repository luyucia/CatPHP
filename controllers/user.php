<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class UserController extends Controller {

    /** 
     * 默认动作
     */
    public function index($name = "Stranger") {
        D($_GET);
    }

}
