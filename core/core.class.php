<?php





// 加载核心类库
// 核心库均通过require直接加载
// require CAT_BASE.'/core/config/config.class.php';

// 加载核心配置文件
// $config = Config::getInstance('php',CAT_BASE.'/config/core_config.php');
// $core_class_path = $config->get('core_class_path');

// 加载核心配置文件
$config = require CAT_BASE.'/config/core_config.php';
// $core_class_path = $config['core_class_path'];

// 注册catphp核心模块自动加载函数
spl_autoload_register('cat_core_bricks_autoload');

function cat_core_bricks_autoload($classname)
{
	
	global $config;
	require CAT_BASE.$config['core_class_path'][$classname];
	// global $core_class_path;
	// require CAT_BASE.$core_class_path[$classname];
}

// require ''
// require APP_BASE.'/bricks/sql/select.php';
// function __autoload($class)
// {
// 	require APP_BASE.'/bricks/sql/select.php';
// 	require APP_BASE.'/bricks/sql/dml.php';
// }

// function au()
// {
// 	spl_autoload(class_name)
// }