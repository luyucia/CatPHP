<?php

/**
 *
 */
require 'common.php';
require 'router.php';


class Web {

    static $rout_rules;

    // 启动
    public static function start() {

        if (!defined("APP_PATH")) {
            define("APP_PATH", $_SERVER['DOCUMENT_ROOT']);
        }

        $configFilePath = APP_PATH.'/config/config.php';
        // spl_autoload_register('web_autoload');
        $WEB_CONFIG   = CatConfig::getInstance($configFilePath);

        $controller_name = '';
        $action_name     = '';
        $url_params      = null;

        // 判定路由解析方式,如果是rest风格则,则构造路由解析对象
        if ($WEB_CONFIG->router_rest) {
            $index = strpos($_SERVER['SCRIPT_NAME'], '/', 0) + 1;
            $url = rtrim( substr($_SERVER['REQUEST_URI'] , $index),"/");
            $global = $WEB_CONFIG->router_global;
            $r = new router($url,$global);
            // 加载路由设置
            if (count($WEB_CONFIG->route_rules)) {
                foreach ($WEB_CONFIG->route_rules as $rule) {
                    $r->addRoute($rule);
                }
            }
            $r->getRouting();

            if ($global) {
                CatPHP::addClassPath(CAT_CONTROLLER_PATH.$r->module,'Controller',CAT_CONTROLLER_FILE_SUFFIX);
            }
            $url_params      = $r->getUrlParam();
            $controller_name = $r->getController();
            $action_name     = $r->getAction();
        }
        // 否则直接解析
        else {
            $rout = self::routeParse($WEB_CONFIG->router_name['c'], $WEB_CONFIG->router_name['a']);
            $controller_name = $rout['c'];
            $action_name     = $rout['a'];
        }
        // 如果该控制器属于rest
        if( in_array($controller_name, $WEB_CONFIG->rest_controllers) ) {
            $restVerb  = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
            $action_name  = $restVerb;
        }
        // 解析非GET POST请求
        if ($_SERVER['REQUEST_METHOD']!='GET' && $_SERVER['REQUEST_METHOD']!='POST'){
            $ps  =  explode('&', file_get_contents('php://input'));
            foreach ($ps as $param) {
                $param  = urldecode($param);
                $eq_pos = strpos($param, "=");
                $pkey   = substr($param, 0,$eq_pos);
                $pvalue = substr($param,$eq_pos+1);
                $url_params[$pkey] = $pvalue;
            }
        }

        // 调用内核自动加载功能
        CatPHP::addClassPath(CAT_CONTROLLER_PATH,'Controller',CAT_CONTROLLER_FILE_SUFFIX);
        CatPHP::addClassPath(CAT_MODEL_PATH,'Model',CAT_MODEL_FILE_SUFFIX);
        // foreach ($WEB_CONFIG->controller_dirs as $dir){
        //     CatPHP::addClassPath(CAT_CONTROLLER_PATH.$dir,'Controller',CAT_CONTROLLER_FILE_SUFFIX);
        // }
        foreach ($WEB_CONFIG->model_dirs as $dir) {
            CatPHP::addClassPath(CAT_MODEL_PATH.$dir,'Model',CAT_MODEL_FILE_SUFFIX);
        }


        // 路由到指定controller的指定action
        $class = $controller_name . 'Controller';
        // try {
            $controller = new $class();
        // } catch (Exception $exc) {
        //     header("HTTP/1.0 404 Not Found");
        //     echo "</h2>404</h2>";
        //     exit();
        // }


        $controller->setActionName($action_name);
        $controller->setControllerName($controller_name);
        if (isset($url_params)) {
            $controller->setParam($url_params);
        }
        // $controller->setRoute($rout);
        $controller->$action_name();


    }

    private static function routeParse($cName, $aName) {

        $rtn = array(
            'c' => 'index',
            'a' => 'index'
        );
        if (isset($_GET[$cName]) && !empty($_GET[$cName])) {
            $rtn['c'] = $_GET[$cName];
        }
        if (isset($_GET[$aName]) && !empty($_GET[$aName])) {
            $rtn['a'] = $_GET[$aName];
        }

        return $rtn;
    }

}

// function web_autoload($class)
// {
//     $WEB_CONFIG   = CatConfig::getInstance(APP_PATH.'/config/config.php');
//     $success = false;
//     // 判断是controller还是model
//     if(stripos($class, "Controller")) {
//         $class = str_replace("Controller", "", $class);
//         $controller_file = CAT_CONTROLLER_PATH.strtolower($class).CAT_CONTROLLER_FILE_SUFFIX.'.php';
//         if (file_exists($controller_file)) {
//             require $controller_file;
//             $success = true;
//         }else{
//             foreach ($WEB_CONFIG->controller_dirs as $dir) {
//                     $controller_file = CAT_CONTROLLER_PATH . $dir . $class .CAT_CONTROLLER_FILE_SUFFIX. '.php';
//                     if (file_exists($controller_file)) {
//                         require $controller_file;
//                         $success = true;
//                         break;
//                     }
//                 }
//         }
//     } else if(stripos($class, "Model")) {
//         $class = str_replace("Model", "", $class);
//         $modelFile = CAT_MODEL_PATH.strtolower($class).CAT_MODEL_FILE_SUFFIX.'.php';
//         if(file_exists($modelFile))
//         {
//             require $modelFile;
//             $success = true;

//         }else{
//             foreach ($WEB_CONFIG->model_dirs as $dir) {
//                 $modelFile = CAT_MODEL_PATH. $dir .strtolower($class).CAT_MODEL_FILE_SUFFIX.'.php';
//                 if (file_exists($modelFile)) {
//                     include $modelFile;
//                     $success = true;
//                     break;
//                 }
//             }
//         }

//     } else {
//         // 加载用户自定义类库
//         if (isset($WEB_CONFIG->libs)) {
//             foreach ($WEB_CONFIG->libs as $key => $value) {
//                 if($p = stripos($class, $key )){
//                     $class = substr($class, 0, $p);
//                     $incFile = APP_PATH.'/'.$value.'/'.$class.'.php';
//                     if (file_exists($incFile)) {
//                         include $incFile;
//                         $success = true;
//                         break;
//                     }
//                 }
//             }
//         }
//     }
//     // if(!$success){
//     //     throw new Exception("class $class not found", 1);
//     // }
// }


?>
