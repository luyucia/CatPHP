<?php

/**
 * Html Form 生成器
 * Author Limingze
 */
class HtmlForm {

    private $_form;

    public function __construct() {
        $this->_form = array();
    }
    
    //生成一个form表单域
    public function initForm($name = "",$id = "",$class = "",$other = ""){
        
        if(!empty($name)){$name = " name = '".$name."'";}
        if(!empty($id)){$id = " id = '".$id."'";}
        if(!empty($class)){$class = " class = '".$class."'";}
        
        $this->_form[] = "<form $name $id $class $other >";
    }
    
    //向表单中追加一个label
    public function addLabel($id = "",$class = "",$title = "",$for = "",$value = "",$other = ""){
        
        if(!empty($id)){$id = " id = '".$id."'";}
        if(!empty($class)){$class = " class = '".$class."'";}
        if(!empty($title)){$title = " title = '".$title."'";}
        if(!empty($for)){$for = " for = '".$for."'";}
        
        $this->_form[] = "<label $id $class $title $for $other >$value";
    }
    public function addEndLabel(){
        $this->_form[] = "</label>";
    }
    
    //向表单中追加一个div
    public function addDiv($id = "",$class = "",$title = "",$value = "",$other = ""){
        
        if(!empty($id)){$id = " id = '".$id."'";}
        if(!empty($class)){$class = " class = '".$class."'";}
        if(!empty($title)){$title = " title = '".$title."'";}
        
        $this->_form[] = "<div $id $class $title $other >$value";
    }
    public function addEnDiv(){
        $this->_form[] = "</div>";
    }
    
    //向表单中追加一个span
    public function addSpan($id = "",$class = "",$title = "",$value = "",$other = ""){
        
        if(!empty($id)){$id = " id = '".$id."'";}
        if(!empty($class)){$class = " class = '".$class."'";}
        if(!empty($title)){$title = " title = '".$title."'";}
        
        $this->_form[] = "<span $id $class $title $other >$value";
    }
    public function addEndSpan(){
        $this->_form[] = "</span>";
    }
    
    //向表单中追加一个input text
    public function addInputText($name = "",$id = "",$class = "",$value = "",$title = "",$placeholder = "",$other = "",$surround = array("","")){
        
        if(!empty($name)){$name = " name = '".$name."'";}
        if(!empty($id)){$id = " id = '".$id."'";}
        if(!empty($class)){$class = " class = '".$class."'";}
        if(!empty($value)){$value = " value = '".$value."'";}
        if(!empty($title)){$title = " title = '".$title."'";}
        if(!empty($placeholder)){$placeholder = " placeholder = '".$placeholder."'";}
        
        $this->_form[] = "{$surround[0]}<input type='text' $name $id $class $value $title $placeholder $other />{$surround[1]}";
    }
    
    //向表单中追加一个input radio
    public function addInputRadio($arrInput,$name = "",$class = "",$default = "",$surround = array("","")){
        
        if(!empty($name)){$name = " name = '".$name."'";}
        if(!empty($class)){$class = " class = '".$class."'";}
        
        foreach ($arrInput as $key => $input) {
            $tmp = "";
            if(isset($input['id'])){$tmp .= " id = '".$input['id']."'";}
            if(isset($input['class'])){$tmp .= " class = '".$input['class']."'";}
            if(isset($input['value'])){
                $tmp .= " value = '".$input['value']."'";
                if($default == $input['value']){
                    $tmp .= " checked";
                }
            }
            if(isset($input['title'])){$tmp .= " title = '".$input['title']."'";}
            if(isset($input['other'])){$tmp .= " {$input['other']}";}
            $text = "";
            if(isset($input['text'])){$text = " {$input['text']}";}
            
            $this->_form[] = "{$surround[0]}<input type='radio' $name $class $tmp />$text {$surround[1]}";
        }
    }
    
    //向表单中追加一个select
    public function addSelect($arrSelect,$name = "",$id = "",$class = "",$surround = array("","")){
        
        if(!empty($name)){$name = " name = '".$name."'";}
        if(!empty($id)){$id = " id = '".$id."'";}
        if(!empty($class)){$class = " class = '".$class."'";}
        $this->_form[] = "{$surround[0]}<select $name $id $class >";
        
        foreach ($arrSelect as $key => $option) {
            $tmp = "";
            if(isset($option['id'])){$tmp .= " id = '".$option['id']."'";}
            if(isset($option['class'])){$tmp .= " class = '".$option['class']."'";}
            if(isset($option['value'])){$tmp .= " value = '".$option['value']."'";}
            if(isset($option['title'])){$tmp .= " title = '".$option['title']."'";}
            if(isset($option['other'])){$tmp .= " {$option['other']}";}
            $this->_form[] = "<option $tmp />";
        }
        
        $this->_form[] = "</select>{$surround[1]}";
    }
    
    //输出表单
    public function getHtml($tagForm = true,$outStr = true){
        if(empty($this->_form)){
            return false;
        }
        
        if($tagForm){
            $this->_form[] = "</form>";
        }
        if($outStr){
            $html = "";
            foreach ($this->_form as $value) {
                $html .= $value;
            }
            return $html;
        }else{
            return $this->_form;
        }
    }
}
