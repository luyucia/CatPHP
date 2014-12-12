<?php

/**
 * @author Limingze
 * @desc 利用php自带的过滤规则过滤(验证)字符串
 */
class convert {
    
    public function __construct() {

    }

    /**
     * 防止在windows下中文文件名的乱码文件名
     * @param type $str
     * @return type
     */
    public function u2g($str){
        if (strstr(PHP_OS, 'WIN')) {
            $str = iconv('UTF-8', 'GBK//IGNORE', $str);
        }
        return $str;
    }
    
    public function g2u($str){
        if (strstr(PHP_OS, 'WIN')) {
            $str = iconv('GBK', 'UTF-8', $str);
        }
        return $str;
    }
}
