<?php


/**
* 
*/
class MvcWeb
{
	
	function __construct()
	{

	}

	// 启动
	public static function start()
	{
		require 'config/config.php';
		$rout = self::routerParse();
		print_r($WEB_CONFIG);
		$controller_file = CONTROLLER_PATH.$rout['c'].'.php';
		// 引入controller文件
		if (file_exists($controller_file))
		{
			include  $controller_file;
		}
		else
		{
			foreach ($WEB_CONFIG['controller_dirs'] as $dir) 
			{
				$controller_file = CONTROLLER_PATH.$dir.$rout['c'].'.php';
				if (file_exists($controller_file)) 
				{
					include  $controller_file;
					break;
				}
			}
		}

		
		$class = $rout['c'].'Controller';
		$methd = $rout['a'].'Action';
		$controller = new $class($rout);
		$controller->$methd();

	}

	// 路由解析
	private static function routerParse()
	{
		$rtn = array(
		'c' => 'index', 
		'a' => 'index'
		);

		$index = strpos($_SERVER['SCRIPT_NAME'],'/',1)+1;
		$url   = substr($_SERVER['REQUEST_URI'],$index);
		$e     = strpos($url, '?');
		if ($e) 
		{
			$url   = substr($url, 0, $e);
		}
		if($url)
		{

			$r = explode('/', $url );
			$rtn['c'] = $r[0];
			// 如果设置了action
			if (isset($r[1])) 
			{
				$rtn['a'] = $r[1];
				$l = count($r);
				if ($r%2!=0) {
					$l-=1;
				}
				for ($i=2; $i < $l; $i+=2) 
				{
					$rtn[$r[$i]] = $r[$i];
				}
			}
			return $rtn;
		}
		else
		{
			return $rtn;
		}
	}
}

?>