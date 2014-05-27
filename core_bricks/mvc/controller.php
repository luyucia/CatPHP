<?php


/**
* 
*/
class Controller
{
    
    function __construct($rout)
    {
        $this->rout = $rout;
    }

    protected function getRequest()
    {

    }

    protected function getRoutRequest($key)
    {
        return $this->rout[$key];
    }

    // public function 


    
}

?>