<?php
$a = microtime(true);

require 'catphp/catphp.php';


Web::setRouter("^blog$","test");
Web::setRouter("^blog1$","test");
Web::setRouter("^blog2$","test");
Web::setRouter("^blog3$","test");
Web::setRouter("^blog4$","test");
Web::setRouter("^blog5$","test");

Web::start();

echo microtime(true)-$a;
?>