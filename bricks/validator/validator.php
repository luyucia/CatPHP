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


    function __construct($data=null)
    {
        $this->data = $data;
    }
    // 设置数据
    public function set_data($data)
    {
        $this->data = $data;
    }
    // 设置模式
    public function set_mode($mode)
    {
        $this->mode = $mode;
    }

    public function check($key,$rule,$name=null)
    {
        if($name==null)
            $name = $key;
        // 如果模式为停止，并且已经发现错误本次不进行验证 直接返回false
        if ($this->mode=='stop' && isset($this->errors[0])) 
            return false;
        // 解析规则
        $rules  =  explode('|', $rule);
        // 按照规则判定
        foreach ($rules as $rule) {

            // 验证必要性
            if($rule=='required')
            {
                if(!$this->required(@$this->data[$key]))
                {
                    $this->errors[] = '请输入'.$name;
                    return false;
                };
            }
            // 验证类型
            else if(strpos($rule,'type')!==false)
            {
                preg_match('/type\((.*)\)/', $rule,$matchs);
                $rs = $this->type(@$this->data[$key],$matchs[1]);
                if(!$rs)
                {
                    $this->errors[] = $name.'的类型不是'.$matchs[1];
                    return false;
                }
            }
            // 验证取值范围
            else if(strpos($rule,'range')!==false)
            {
                preg_match('/range\((.*),(.*)\)/', $rule,$matchs);
                $rs = $this->range(@$this->data[$key],$matchs[1],$matchs[2]);
                if($rs===2)
                {
                    $this->errors[] = $name.'的取值超过'.$matchs[2];
                    return false;
                }else if($rs===0){
                    $this->errors[] = $name.'的取值小于'.$matchs[1];
                    return false;
                };
            }
            // 验证正则
            else if(strpos($rule,'reg')!==false)
            {
                preg_match('/reg\((.*)\)/', $rule,$matchs);
                $rs = $this->reg(@$this->data[$key],$matchs[1]);
                if(!$rs)
                {
                    $this->errors[] = $name.'的格式有误';
                    return false;
                }
            }
            

        }
        // 记录判定结果
        
    }
    // 判定必要性
    public function required($value)
    {
        if(!isset($value)) return false;
        if($value===null)  return false;
        if($value==='')    return false;
        return true;
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
        else if($type==='float')
        {
            return is_float($value);
        }

    }

    public function reg($value,$reg)
    {
        return preg_match('/^'.$reg.'$/', $value);
    }

    // 获取错误信息
    public function get_error($index=null)
    {
        if (is_numeric($index)) 
        {
            return $this->errors[$index];
        }
        else if(isset($this->errors[0]))
        {
            return $this->errors;
        }
        else
            return false;
    }
}



$data = array(
    'name'    => '555' ,
    'age'     => '25' ,
    'account' => '' ,
    'email'   => '' ,
    'account' => '' ,
    'content' => 'xxxxss' ,
    'sex'     => 'xxxxss' ,

 );

$v = new Validator($data);
// $v->set_data();
$v->set_mode('all');
$v->check('name','required|type(string)','用户名');
$v->check('sex','required','性别');
$v->check('age','required|type(int)|range(18,25)','年龄');
$v->check('content','required|len(20,50)|reg(\w*,)','内容');
echo $v->get_error(0);
// print_r($v->get_error(0));
// echo "string";
// var_dump();
// 验证数组
// 验证单一变量



?>