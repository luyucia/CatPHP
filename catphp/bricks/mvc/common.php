<?php

/**
 * 防注入获取请求参数-POST方法
 * @param type $key 键值
 * @param type $default 为空时的默认值
 * @return type
 */
function P($key, $default = null) {
    if ($default === null) {
        return addslashes($_GET[$key]);
    } else {
        if (isset($_GET[$key])) {
            return addslashes($_GET[$key]);
        } else {
            return $default;
        }
    }
}

/**
 * 防注入获取请求参数-GET方法
 * @param type $key 键值
 * @param type $default 为空时的默认值
 * @return type
 */
function G($key, $default = null) {
    if ($default === null) {
        return addslashes($_POST[$key]);
    } else {
        if (isset($_POST[$key])) {
            return addslashes($_POST[$key]);
        } else {
            return $default;
        }
    }
}

function L($filemame,$content,$logdir){
    $handle = fopen($filemame, "w+");
    D($handle);
}

function D($para){
    echo "<pre>";
    echo "<h2>print_r:</h2><br>";
    print_r($para);
    echo "<br>";
    echo "<h2>var_dump:</h2><br>";
    var_dump($para);
    echo "</pre>";
    
    return print_r($para,true);
}