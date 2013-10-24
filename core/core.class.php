<?php


// 加载核心配置文件
$config     = require CAT_BASE.'/config/core_config.php';
// 加载第三方类库配置文件
$thr_config = require CAT_BASE.'/config/thr_class_config.php';

// 注册catphp核心模块自动加载函数
spl_autoload_register('cat_core_bricks_autoload');

function cat_core_bricks_autoload($classname)
{
	
	global $config;
	global $thr_config;

	if (isset($config['core_class_path'][$classname])) 
	{
		require CAT_BASE.$config['core_class_path'][$classname];
	}
	else if(isset($thr_config[$classname]))
	{
		require CAT_BASE.$thr_config[$classname];
	}

}

