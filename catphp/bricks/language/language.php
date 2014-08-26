<?php


/**
* 
*/
class Language
{
    private $lang;
    
    function __construct($language)
    {
        $this->lang = include  APP_PATH. '/languages/'.$language.'/lang.php';
    }

    public function __get($name){
        
        return $this->lang[$name];
    }
}
?>