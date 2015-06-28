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
    public function index() {
        $userModel = new userModel();

        $s = microtime(true);
        $c = '';
        $WEB_CONFIG   = CatConfig::getInstance(APP_PATH.'/config/config.php');
        for ($i=0; $i < 1000000; $i++) {
            // $c = model_path;
            $c = $WEB_CONFIG->get('model_path');
            // $c = $WEB_CONFIG->model_path;
        }

        echo microtime(true)-$s;
    }

}
