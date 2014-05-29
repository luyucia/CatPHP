<?php
$a = microtime(true);

require 'catphp/catphp.php';
MvcWeb::start();

echo microtime(true)-$a;
?>