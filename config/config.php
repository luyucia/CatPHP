<?php

return array(
    'view_path'=>'views/',
    'controller_path'=>'controllers/',
    'controller_dirs' => array('test/','api/'),
    'router_rest'     => true, 
    'router_name'     =>array(
            'c' => 'c',
            'a' => 'a',
        ), 
    // 模板引擎 可选（tenjin smarty php）
    'template_engine'=>'tenjin'
    );



?>