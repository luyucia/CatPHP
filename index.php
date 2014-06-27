<?php
$a = microtime(true);

require 'catphp/catphp.php';
Web::start();

echo microtime(true)-$a;
?>