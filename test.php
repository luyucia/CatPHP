<?php

require 'catphp.php';

$s = new select();
echo $s->to_string();

$kint = new Kint();
Kint::trace();

?>