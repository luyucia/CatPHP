<?php

/**
 * 防注入获取请求参数-POST方法
 * @param type $key 键值
 * @param type $default 为空时的默认值
 * @return type
 */
function P($key, $default = null) {
    if (isset($_POST[$key])) {
        if (is_array($_POST[$key])) {
            return $_POST[$key];
        }else{
            return addslashes($_POST[$key]);
        }
    } else {
        return $default;
    }
}

/**
 * 防注入获取请求参数-GET方法
 * @param type $key 键值
 * @param type $default 为空时的默认值
 * @return type
 */
function G($key, $default = null) {
    if (isset($_GET[$key])) {
        return addslashes($_GET[$key]);
    } else {
        return $default;
    }
}

/**
 * 防注入获取请求参数-REQUEST方法
 * @param type $key 键值
 * @param type $default 为空时的默认值
 * @return type
 */
function R($key, $default = null) {
    if (isset($_REQUEST[$key])) {
        return addslashes($_REQUEST[$key]);
    } else {
        return $default;
    }
}

function L($filemame,$content,$logdir){
    $handle = fopen($filemame, "w+");
    D($handle);
}

function D($para){
    echo '<xmp>';
    echo "<h2>print_r:</h2><br>";
    print_r($para);
    echo "<br>";
    echo "<h2>var_dump:</h2><br>";
    var_dump($para);

    return print_r($para,true);
}
