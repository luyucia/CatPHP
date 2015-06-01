<?php
$a = microtime(true);
require '../catphp/catphp.php';


// Web::setRouter("^\w*\/","api",'index');
// Web::setRouter("^blog1$","test",'index');
// Web::setRouter("^blog2$","test",'index');
// Web::setRouter("^blog3$","test",'index');
// Web::setRouter("^blog4$","test",'index');
// Web::setRouter("^blog5$","test",'index');

Web::start();

// echo "cost : ".(microtime(true)-$a)." ms";
?>
