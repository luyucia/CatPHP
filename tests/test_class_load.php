<?php


require '../catphp/catphp.php';
CatPHP::addClassPath('models','Model');
CatPHP::addClassPath('models/test','Model');

$s = microtime(true);
new TestModel();
new TestModel();
new TestaModel();

// require 'models/testModel.php';
// require 'models/test/testaModel.php';

// new TestModel();
// new TestaModel();

echo microtime(true)-$s;
// new CCModel();

?>
