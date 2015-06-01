<?php

/**
 *
 */
require 'common.php';
require 'router.php';


class Web {

    static $rout_rules;

    // function __construct() {

    // }

    // public function setRouter($pattern,$controller,$action)
    // {
    //     self::$rout_rules[] = array('p' => $pattern, 'c'=>$controller ,'a'=>$action);
    // }


    // 启动
    public static function start() {

        if (!defined("APP_PATH")) {
            define("APP_PATH", $_SERVER['DOCUMENT_ROOT']);
        }

        spl_autoload_register('web_autoload');
        $WEB_CONFIG   = CatConfig::getInstance(APP_PATH.'/config/config.php');

        $controller_name = '';
        $action_name     = '';
        $url_params      = null;

        // 判定路由解析方式,如果是rest风格则,则构造路由解析对象
        if ($WEB_CONFIG->router_rest) {
            $index = strpos($_SERVER['SCRIPT_NAME'], '/', 0) + 1;

            $url = rtrim( substr($_SERVER['REQUEST_URI'] , $index),"/");
            $r = new router($url);
            // 加载路由设置
            if (count($WEB_CONFIG->route_rules)) {
                foreach ($WEB_CONFIG->route_rules as $rule) {
                    $r->addRoute($rule);
                }
            }
            $r->getRouting();

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


        // 路由到指定controller的指定action
        $class = $controller_name . 'Controller';
        try {
            $controller = new $class();
        } catch (Exception $exc) {
            header("HTTP/1.0 404 Not Found");
            echo "</h2>404</h2>";
            exit();
        }



        $controller->setActionName($action_name);
        $controller->setControllerName($controller_name);
        if (isset($url_params)) {
            $controller->setParam($url_params);
        }
        // $controller->setRoute($rout);
        $controller->$action_name();

        // exit();

        // self::$rout_rules = $WEB_CONFIG->route_regular;

        // 判定路由解析方式,如果是rest风格则
        // if ($WEB_CONFIG->router_rest) {
        //     $rout = self::routeParseReg();
        //     if ( !is_array($rout) ) {
        //         $rout = self::routeParseRest();
        //     }
            // 将url中解析后的内容传给$_GET
            // $_GET = array_merge($_GET, $rout);
            // $_GET = $_GET + $rout;
        // }
        // 如果不是rest风格
        // else {
        //     $rout = self::routeParse($WEB_CONFIG->router_name['c'], $WEB_CONFIG->router_name['a']);
        // }
        // $controller_file = APP_PATH.'/'.$WEB_CONFIG->controller_path . $rout['c'] . '.php';
        // 引入controller文件
        // if (file_exists($controller_file)) {
        //     include $controller_file;
        // } else {
        //     foreach ($WEB_CONFIG->controller_dirs as $dir) {
        //         $controller_file = APP_PATH.'/'.$WEB_CONFIG->controller_path . $dir . $rout['c'] . '.php';
        //         if (file_exists($controller_file)) {
        //             include $controller_file;
        //             break;
        //         }
        //     }
        // }
        // 如果控制器属于rest
        // if( in_array($rout['c'], $WEB_CONFIG->rest_controllers) )
        // {
        //     $restVerb  = ucfirst(strtolower($_SERVER['REQUEST_METHOD']));
        //     $methd     = $restVerb;
        //     $params = array();
        //     foreach ($rout as $key => $value) {
        //         if($key==='c' || $key==='last')
        //             continue;
        //         elseif ( $key==='a') {
        //             if ($value!=='' && $value!=='index')
        //                 $params[] = $value;
        //         }
        //         else {
        //             $params[] = $key;
        //             $params[] = $value;
        //         }
        //     }
        //     if (isset($rout['last'])) {
        //         $params[] = $rout['last'];
        //     }
        //     // 解析非GET POST请求
        //     $ps  =  explode('&', file_get_contents('php://input'));
        //     foreach ($ps as $param) {
        //         $param = urldecode($param);
        //         $eq_pos = strpos($param, "=");
        //         $pkey   = substr($param, 0,$eq_pos);
        //         $pvalue = substr($param,$eq_pos+1);
        //         $params[$pkey] = $pvalue;
        //     }
        // }
        // else {
        //     $methd = $rout['a'];
        // }

        // $class = $rout['c'] . 'Controller';
        // $controller = new $class();
        // if (isset($params)) {
        //     $controller->setParam($params);
        // }
        // $controller->setRoute($rout);
        // $controller->$methd();
    }



    // 解析正则规则
    // private static function routeParseReg()
    // {
    //     if (count(self::$rout_rules)=== 0) {
    //         return false;
    //     } else {
    //         $index = strpos($_SERVER['SCRIPT_NAME'], '/', 1) + 1;
    //         $url = rtrim( substr($_SERVER['REQUEST_URI'] , $index),"/");
    //         $rtn = array(
    //         'c' => 'index',
    //         'a' => 'index'
    //         );
    //         foreach (self::$rout_rules as $rules) {
    //             if (preg_match('#'.$rules['p'].'#', $url,$matchs))
    //             {
    //                 $rtn['c'] = $rules['c'];
    //                 $rtn['a'] = $rules['a'];
    //                 $url = str_replace($matchs[0], "", $url);
    //                 $e   = strpos($url, '?');
    //                 if ($e) {
    //                     $url = substr($url, 0, $e);
    //                 }
    //                 $r = explode('/', $url);
    //                 $l = count($r);
    //                 if ($l % 2 != 0) {
    //                     $l-=1;
    //                     $hasLast = true;
    //                 }
    //                 for ($i = 0; $i < $l; $i+=2) {
    //                     if($r[$i]==='c' || $r[$i]==='a')
    //                         continue;
    //                     $rtn[$r[$i]] = $r[$i + 1];
    //                 }
    //                 if(isset($hasLast))
    //                     $rtn['last'] = $r[$l];
    //                 return $rtn;
    //             }
    //         }
    //         return false;
    //     }
    // }



    // 路由解析
    // 测试 10万次执行时间<1s
    // private static function routeParseRest() {
    //     $rtn = array(
    //         'c' => 'index',
    //         'a' => 'index'
    //     );

    //     $index = strpos($_SERVER['SCRIPT_NAME'], '/', 1) + 1;
    //     $url = rtrim( substr($_SERVER['REQUEST_URI'] , $index),"/");
    //     $e = strpos($url, '?');
    //     if ($e) {
    //         $url = substr($url, 0, $e);
    //     }
    //     if ($url) {
    //         $r = explode('/', $url);
    //         $rtn['c'] = $r[0];

    //         // 如果设置了action
    //         if (isset($r[1])) {
    //             $rtn['a'] = $r[1]==''?'index':$r[1];
    //             $l = count($r);
    //             if ($l>2 && $l % 2 != 0) {
    //                 $l-=1;
    //                 $hasLast = true;
    //             }
    //             for ($i = 2; $i < $l; $i+=2) {
    //                 if($r[$i]==='c' || $r[$i]==='a')
    //                     continue;
    //                 $rtn[$r[$i]] = $r[$i + 1];
    //             }
    //             if(isset($hasLast))
    //                 $rtn['last'] = $r[$l];
    //         }
    //         return $rtn;
    //     } else {
    //         return $rtn;
    //     }
    // }

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

function web_autoload($class)
{
    $WEB_CONFIG   = CatConfig::getInstance(APP_PATH.'/config/config.php');
    $success = false;
    // 判断是controller还是model
    if(stripos($class, "Controller")) {
        $class = str_replace("Controller", "", $class);
        $controller_file = APP_PATH.'/'.$WEB_CONFIG->controller_path.strtolower($class).'.php';
        if (file_exists($controller_file)) {
            require $controller_file;
            $success = true;
        }else{
            foreach ($WEB_CONFIG->controller_dirs as $dir) {
                    $controller_file = APP_PATH.'/'.$WEB_CONFIG->controller_path . $dir . $class . '.php';
                    if (file_exists($controller_file)) {
                        require $controller_file;
                        $success = true;
                        break;
                    }
                }
        }
    } else if(stripos($class, "Model")) {
        $class = str_replace("Model", "", $class);
        $modelFile = APP_PATH.'/'.$WEB_CONFIG->model_path.strtolower($class).'.php';
        if(file_exists($modelFile))
        {
            require $modelFile;
            $success = true;

        }else{
            foreach ($WEB_CONFIG->model_dirs as $dir) {
                $modelFile = APP_PATH.'/'.$WEB_CONFIG->model_path. $dir .strtolower($class).'.php';
                if (file_exists($modelFile)) {
                    include $modelFile;
                    $success = true;
                    break;
                }
            }
        }

    } else {
        // 加载用户自定义类库
        if (isset($WEB_CONFIG->libs)) {
            foreach ($WEB_CONFIG->libs as $key => $value) {
                if($p = stripos($class, $key )){
                    $class = substr($class, 0, $p);
                    $incFile = APP_PATH.'/'.$value.'/'.$class.'.php';
                    if (file_exists($incFile)) {
                        include $incFile;
                        $success = true;
                        break;
                    }
                }
            }
        }
    }
    // if(!$success){
    //     throw new Exception("class $class not found", 1);
    // }
}


?>
