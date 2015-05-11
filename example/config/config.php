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
    'route_rules'=>array(
        // 静态路由 和参数
            array(
            "rule" => "read/title/page",
            "controller" => 'book',
            "action" => 'test'
            ),
            array(
            "rule" => "read/:title/:page",
            "controller" => 'book',
            "action" => 'index'
            ),
            array(
            "rule" => "read/(\d{6})",
            "controller" => 'book',
            "action" => 'read',
            "reg_map"=>array('bookid')
            ),

        ),
    );

?>
