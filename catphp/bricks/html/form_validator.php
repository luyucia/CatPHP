<?php
/**
* 
*/
class FormValidator
{
    protected $form_data = null;
    
    // 构造函数，参数为表单数组
    function __construct($form_data)
    {
        $this->form_data = $form_data;
    }

    // 判断表单数组中是否有空值
    // 有则返回true
    // 没有空值返回false
    public function has_empty()
    {
        foreach ($this->form_data as $value) {
            if($value=='' || $value==null)
                return true;
        }
        return false;
    }

    public function all_is_number()
    {

    }

    public function all_is_string()
    {
        
    }

    public function value_in($key,$data)
    {

    }

    public function value_is_string($key)
    {

    }

    public function value_is_number($key)
    {
        
    }

    public function value_is_email($key)
    {
        
    }

    // 取值范围
    public function value_in_range($key,$range_s,$range_e)
    {

    }

    // 值的长度
    public function value_length($key,$start,$end)
    {

    }
}


?>