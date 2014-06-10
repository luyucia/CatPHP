<?php
/**
 * 工具类
 * Author:JasonLee
 * Email:limingze610@gmail.com
 */
class Tools {

    public $filter_arr = array();

    public function __construct($array) {
        $this->filter_arr = $array;
    }

    /**
     * 将传入的数组每个元素两端的空格或指定的字符串过滤掉
     * @return \Tools
     */
    public function F($character_mask = null) {
        if(empty($character_mask)){
            foreach ($this->filter_arr as $key => $value) {
                $this->filter_arr[$key] = trim($value);
            }
        }else{
            foreach ($this->filter_arr as $key => $value) {
                $this->filter_arr[$key] = trim($value,$character_mask);
            }
        }
        
        return $this;
    }

    /**
     * 使用addslashes函数对指定的array进行转义
     * @return \Tools
     */
    public function R() {
        foreach ($this->filter_arr as $key => $value) {
            $this->filter_arr[$key] = addslashes($value);
        }
        return $this;
    }

    public function GetArr() {
        return $this->filter_arr;
    }
}
