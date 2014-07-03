<?php

include 'cache.php';

$config = array(
    'type' => 'memcache'

    );
$cache = new Cache($config);

$cache->get('user');


?>