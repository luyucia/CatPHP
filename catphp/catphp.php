<?php
// 此处定义了两个常量，保存初始时刻的时间和内存使用
define('CAT_START_TIME'  , microtime(true)    );
define('CAT_START_MEMORY', memory_get_usage() );
// CAT_BASE为框架根目录，定义这个的目的是方便后面使用绝对地址载入php文件。
define('CAT_BASE'        , dirname(__FILE__)  );
require CAT_BASE.'/core/core.php';


