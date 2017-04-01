<?php

/**
*
*
* 变量有无
* 取值范围> < = in
* 长度
* 类型（整数，小数，字符串，日期）
* 其他（邮箱，IP，手机，正则）
*
*
*
*
*
*
*
*/
class Validator
{

    private $data;
    private $mode   = 'stop';
    private $errors = array();
    private $config = array();
    private $error_format = array(
        'required'     =>':field is required',
        'type'         =>'the type of :field is wrong!',
        'more than'    =>':field is too large',
        'less than'    =>':field is too small',
        'more len'     =>':field is too length',
        'less len'     =>':field is too short',
        'wrong format' =>':field is wrong format',
        );


    function __construct($data=null,$config=array())
    {
        $this->data   = $data;
        $this->config = $config;

        $this->doValidate();
    }

    public function setErrorFormat($error,$format)
    {
        $this->error_format[$error] = $format;
    }
    // 设置数据
    public function setData($data,$config)
    {
        $this->data   = $data;
        $this->config = $config;
        $this->doValidate();
    }
    // 设置模式
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    private function doValidate(){
        foreach ($this->config as $key => $value) {
            if($this->check($key,$value)===false)
            {
                break;
            }
        }
    }

    public function check($key,$rule_str,$name=null)
    {
        if($name==null)
            $name = $key;
        // 如果模式为停止，并且已经发现错误本次不进行验证 直接返回false
        if ($this->mode==='stop' && isset($this->errors[0]))
            return false;
        // 解析规则
        $rules  =  explode('|', $rule_str);
        // 按照规则判定
        foreach ($rules as $rule) {
            // 验证必要性
            if($rule=='required')
            {
                if(!$this->required(@$this->data[$key]))
                {
                    $this->errors[$key]['error'] = 'required';
                    break;
                };
            }
            // 验证类型
            else if(strpos($rule,'type')!==false)
            {
                preg_match('/type:(.*)/', $rule,$matchs);
                $rs = $this->type(@$this->data[$key],$matchs[1]);
                if(!$rs)
                {
                    $this->errors[$key]['error']  = 'type';
                    break;
                }
            }
            // 验证取值范围
            else if(strpos($rule,'range')!==false)
            {
                preg_match('/range:(.*)-(.*)/', $rule,$matchs);
                $rs = $this->range(@$this->data[$key],$matchs[1],$matchs[2]);
                if($rs===2)
                {
                    $this->errors[$key]['error'] = 'more than';
                    break;
                }else if($rs===0){
                    $this->errors[$key]['error'] = 'less than';
                    break;
                };
            }
            // 验证正则
            else if(strpos($rule,'reg')!==false)
            {
                preg_match('/reg:(.*)/', $rule,$matchs);
                $rs = $this->reg(@$this->data[$key],$matchs[1]);
                if(!$rs)
                {
                    $this->errors[$key]['error'] = 'wrong format';
                    break;
                }
            }
            // 长度
            else if(strpos($rule,'len')!==false)
            {
                preg_match('/len:(.*)-(.*)/', $rule,$matchs);
                $rs = $this->len(@$this->data[$key],$matchs[1],$matchs[2]);
                if($rs===2)
                {
                    $this->errors[$key]['error'] = 'more len';
                    break;
                }else if($rs===0){
                    $this->errors[$key]['error'] = 'less len';
                    break;
                };
            }
        }

        // 记录判定结果

    }
    // 判定必要性
    public function required($value)
    {
        if(isset($value) && $value!==null && $value!=='' && $value!==false) {
            return true;
        }else{
            return false;
        }
    }
    // 范围判定，小于最小返回0，大于最大返回2，正常返回1
    public function range($value,$min,$max)
    {
        if($min == 'min')
        {
            if($value > $max) return 2;
        }
        if($max == 'max')
        {
            if($value < $min) return 0;
        }
        if(is_numeric($min) && is_numeric($max))
        {
            if($value < $min) return 0;
            if($value > $max) return 2;
        }
        return true;
    }

    // 长度判定，小于最小返回0，大于最大返回2，正常返回1
    public function len($value,$min,$max)
    {
        $len  =  strlen($value);
        if($min == 'min')
        {
            if($len > $max) return 2;
        }
        if($max == 'max')
        {
            if($len < $min) return 0;
        }
        if(is_numeric($min) && is_numeric($max))
        {
            if($len < $min) return 0;
            if($len > $max) return 2;
        }
        return true;
    }

    // 判定数据类型
    public function type($value,$type)
    {
        if($type==='string')
        {
            return is_string($value);
        }
        else if($type==='int')
        {
            return preg_match('/^\d*$/', $value);
        }
        else if($type==='numeric')
        {
            return is_numeric($value);
        }

    }

    public function reg($value,$reg)
    {
        return preg_match('/^'.$reg.'$/', $value);
    }

    // 获取错误信息
    public function getError($key)
    {
        $format = $this->error_format[$this->errors[$key]['error']];

        if(strpos($this->config[$key],'name')!==false)
        {
            preg_match('/name:(.*)/', $this->config[$key],$matchs);
            $this->errors[$key]['field_name'] = $matchs[1];
            $field_name = $matchs[1];
        }else{
            $field_name = $key;
        }
        return preg_replace('#:field#', $field_name, $format);
    }

    public function getAllError()
    {
        $errors = array();
        foreach ($this->errors as $key => $value) {
            $errors[] = $this->getError($key);
        }
        return $errors;
    }
}



// $data = array(
//     'name'    => '' ,
//     'age'     => '25' ,
//     'account' => '' ,
//     'email'   => '' ,
//     'account' => '' ,
//     'content' => 'xxxxss' ,
//     'sex'     => 'xxx' ,

//  );

// $config = array(
//     'name'    => 'required|type:string|name:姓名',
//     'sex'     => 'required|type:int',
//     'age'     => 'required|type:int|range:18-25',
//     'content' => 'required|len:20-50|reg:\w*,',
//     );

// $v = new Validator($data,$config);
// $v->setErrorFormat('required',"请输入:field");
// $v->setErrorFormat('less len',"请输入:field");
// $v->setMode('all');
// print_r($v->getAllError());




?>
