<?php
/**
 * CatPHP
 *
 * An open source php development framework for PHP
 *
 * @package     CatPHP
 * @author      luyucia@gmail.com
 * @license     http://codeigniter.com/user_guide/license.html
 * @link        http://catphp.org
 * @since       Version 1.0
 * @filesource
 */

/*
 * ------------------------------------------------------
 *  加载核心配置文件
 * ------------------------------------------------------
 */
$config     = require CAT_BASE.'/config/core_class_config.php';
/*
 * ------------------------------------------------------
 *  加载第三方类库配置文件
 * ------------------------------------------------------
 */
$thr_config = require CAT_BASE.'/config/thr_class_config.php';


/*
 * ------------------------------------------------------
 *  注册catphp核心模块自动加载函数
 * ------------------------------------------------------
 * 不直接用autoload是因为要兼容其他框架
 * 路径都用绝对路径，因为节省系统调用，比相对路径效率高
 */
spl_autoload_register('cat_core_bricks_autoload');

/**
 * @name cat_core_bricks_autoload
 * @param 定义函数或者方法的参数信息
 * @return 定义函数或者方法的返回信息
 * @version v 0.1
 */
function cat_core_bricks_autoload($classname)
{
    global $config;
    global $thr_config;

    if (isset($config['core_class_path'][$classname])) {
        require CAT_BASE.$config['core_class_path'][$classname];
    }
    else if(isset($thr_config[$classname])) {
        require CAT_BASE.$thr_config[$classname];
    }
}


/**
* CatPHP内核类
*/
class CatPHP
{

    function __construct()
    {
    }

    static $paths = array();

    public static function addClassPath($path,$suffix,$file_suffix = false)
    {
        $config['path']   = $path;
        $config['suffix'] = $suffix;
        if ($file_suffix!==false) {
            $config['file_suffix'] = $file_suffix;
        }else{
            $config['file_suffix'] = $suffix;
        }
        self::$paths[]    = $config;
    }

    public static function auto_load($class)
    {
        // 遍历所有路径配置,遇到指定后缀则匹配触发加载
        foreach (self::$paths as $path) {
            if ($i = strpos($class, $path['suffix'])) {
                $pure_class = strtolower(substr($class, 0, $i));
                $file = $path['path'].'/'.$pure_class.$path['file_suffix'].'.php';

                if(file_exists($file))
                {
                    include $file;
                    return;
                }

            }
        }
    }

    public static function coreStart()
    {
        spl_autoload_register('CatPHP::auto_load');
    }

}

CatPHP::coreStart();


