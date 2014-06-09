<?php

/**
 * 
 */
class MvcWeb {

    function __construct() {
        
    }

    // 启动
    public static function start() {
        require 'config/config.php';
        // 判定路由解析方式,如果是rest风格则
        if ($WEB_CONFIG['router_rest']) {
            $rout = self::routerParseRest();
            // 将url中解析后的内容传给$_GET
            $_GET = array_merge($_GET, $rout);
        }
        // 如果不是rest风格
        else {
            $rout = self::routerParse($WEB_CONFIG['router_name']['c'], $WEB_CONFIG['router_name']['a']);
        }
        $controller_file = CONTROLLER_PATH . $rout['c'] . '.php';
        // 引入controller文件
        if (file_exists($controller_file)) {
            include $controller_file;
        } else {
            foreach ($WEB_CONFIG['controller_dirs'] as $dir) {
                $controller_file = CONTROLLER_PATH . $dir . $rout['c'] . '.php';
                if (file_exists($controller_file)) {
                    include $controller_file;
                    break;
                }
            }
        }

        $class = $rout['c'] . 'Controller';
        $methd = $rout['a'] . 'Action';

        $controller = new $class($rout);
        $controller->$methd();
    }

    // 路由解析
    // 测试 10万次执行时间<1s
    private static function routerParseRest() {
        $rtn = array(
            'c' => 'index',
            'a' => 'index'
        );

        $index = strpos($_SERVER['SCRIPT_NAME'], '/', 1) + 1;
        $url = substr($_SERVER['REQUEST_URI'], $index);
        $e = strpos($url, '?');
        if ($e) {
            $url = substr($url, 0, $e);
        }
        if ($url) {

            $r = explode('/', $url);
            $rtn['c'] = $r[0];
            // 如果设置了action
            if (isset($r[1])) {
                $rtn['a'] = $r[1];
                $l = count($r);
                if ($r % 2 != 0) {
                    $l-=1;
                }
                for ($i = 2; $i < $l; $i+=2) {
                    $rtn[$r[$i]] = $r[$i + 1];
                }
            }
            return $rtn;
        } else {
            return $rtn;
        }
    }

    private static function routerParse($cName, $aName) {

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

/**
 * 防注入获取请求参数-POST方法
 * @param type $key 键值
 * @param type $default 为空时的默认值
 * @return type
 */
function P($key, $default = null) {
    if ($default === null) {
        return addslashes($_GET[$key]);
    } else {
        if (isset($_GET[$key])) {
            return addslashes($_GET[$key]);
        } else {
            return $default;
        }
    }
}

/**
 * 防注入获取请求参数-GET方法
 * @param type $key 键值
 * @param type $default 为空时的默认值
 * @return type
 */
function G($key, $default = null) {
    if ($default === null) {
        return addslashes($_POST[$key]);
    } else {
        if (isset($_POST[$key])) {
            return addslashes($_POST[$key]);
        } else {
            return $default;
        }
    }
}

?>