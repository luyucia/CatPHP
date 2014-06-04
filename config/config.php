<?php

define('CONTROLLER_PATH', 'controllers/');
define('VIEW_PATH', 'views/');

$WEB_CONFIG = array(
    'controller_dirs' => array('test/','api/'),
    'router_rest'     => true, 
    'router_name'     =>array(
            'c' => 'c',
            'a' => 'a',
        ), 
    );
?>