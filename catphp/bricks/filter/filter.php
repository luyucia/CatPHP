<?php

/**
 * @author Limingze
 * @desc 利用php自带的过滤规则过滤(验证)字符串
 */
class filter {

    private $type;

    /**
     * 仅列举部分常用验证，更多规则请参考PHP Filter 函数
     */
    public function __construct() {
        $this->type = array(
            'cat_string' => FILTER_SANITIZE_STRING,         //去除标签，去除或编码特殊字符
            'cat_encode' => FILTER_SANITIZE_ENCODED,        //URL-encode 字符串，去除或编码特殊字符
            'cat_schars' => FILTER_SANITIZE_SPECIAL_CHARS,  //HTML 转义字符 '"<>& 以及 ASCII 值小于 32 的字符
            'cat_email'  => FILTER_SANITIZE_EMAIL,          //删除所有字符，除了字母、数字以及 !#$%&'*+-/=?^_`{|}~@.[]
            'cat_url'    => FILTER_SANITIZE_URL,            //删除所有字符，除了字母、数字以及 $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=
            'cat_regexp' => FILTER_VALIDATE_REGEXP,         //根据 regexp，兼容 Perl 的正则表达式来验证值
            'cat_vurl'   => FILTER_VALIDATE_URL,            //把值作为 URL 来验证
            'cat_vemail' => FILTER_VALIDATE_EMAIL,          //把值作为 e-mail 来验证
            'cat_vip'    => FILTER_VALIDATE_IP,             //把值作为 IP 地址来验证
        );
    }

    /**
     * 
     * @param mixed $data 要验证的数据
     * @param int   $type_str PHPFilters
     * @param type $option
     * @return mixed
     */
    public function filter($data, $type_str , $option = null) {
        if(isset($this->type[$type_str])){
            return filter_var($data, $this->type[$type_str], $option);
        }else{
            return filter_var($data, $type_str, $option);
        }
    }

    /**
     * 验证二维数组是否有空值，常用语表单提交
     * @param type $data
     * @return boolean
     */
    public function vArrEmpty($data){
        foreach ($data as $key => $value) {
            if(trim($value) == ''){
                return false;
            }
        }
        return true;
    }
}
