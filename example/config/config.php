<?php

return array(
    'view_path'=>'views/',
    'controller_path'=>'controllers/',
    'controller_dirs' => array('test/','api/'),
    'rest_controllers'=>array('product','api'),
    'router_rest'     => true, 
    'log_level'       => Logging::ALL, 
    'router_name'     =>array(
            'c' => 'c',
            'a' => 'a',
        ), 
    // 模板引擎 可选（tenjin smarty php）
    'template_engine'=>array(
        'type'  => 'smarty',
        'debug' => false,
        'cache' => false,
        'cache_lifetime' => 120,
    ),
    'route_regular'=>array(
            array('p'=>'^\w{6}\/','c'=>'api','a'=>'index')
        ),
    );

?>
