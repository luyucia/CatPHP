<?php

return array(
    'view_path'=>'views/',
    'controller_path'=>'controllers/',
    'controller_dirs' => array('test/','api/'),
    'rest_controllers'=>array('product','api'),
    'router_rest'     => true, 
    'router_name'     =>array(
            'c' => 'c',
            'a' => 'a',
        ), 
    // 模板引擎 可选（tenjin smarty php）
    'template_engine'=>'tenjin',
    'route_regular'=>array(
            // array('p'=>'^\w*\/','c'=>'api','a'=>'index')
        )
    );

?>
