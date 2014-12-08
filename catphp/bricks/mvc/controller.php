<?php

/**
 * 
 */


class Controller {

    private    $_config;
    private    $context = array();
    private    $engine;
    public     $controllerName;
    public     $actionName;
    protected  $request;

    function __construct() {
        session_start();
        ob_start();
        $WEB_CONFIG   = CatConfig::getInstance('config/config.php');
        $this->_config   = CatConfig::getConfig();
        $template     = $WEB_CONFIG->template_engine;
        if ($template === 'tenjin' ) {
            $properties = array('cache' => false);
            $this->engine = new Tenjin_Engine($properties);
        }
        elseif ($template === 'smarty') {
                echo "暂时不支持smarty";
                exit();
            }
        
        // D($WEB_CONFIG);
    }

    public function setRoute($rout) {
        $this->rout   = $rout;
    }

    public function assign($key, $value) {
        $this->context[$key] = $value;
    }

    public function render($tpl) {
        $output = $this->engine->render($tpl, $this->context);
        return $output;
    }

    public function getParam($key='',$default = false,$incect_check = true) {
        if ($key==='') {
            return $this->request;
        }
        if (isset($this->request[$key])) {
            if ($incect_check) {
                return $this->filter($this->request[$key]);
            }else{
                return $this->request[$key];
            }
        }
        else if(isset($_POST[$key])) {
            if ($incect_check) {
                return $this->filter($_POST[$key]);
            }else {
                return $_POST[$key];
            }
        }
        else if(isset($_GET[$key])) {
            if ($incect_check) {
                return $this->filter($_GET[$key]);
            }else {
                return $_GET[$key];
            }
        }
        else {
            return $default;
        }
    }

    private function filter($s) {
        if(is_array($s)){
            return $s;
        }else{
            return addslashes($s);
        }
    }

    public function setParam($req)
    {
        $this->request = $req;
    }

    protected function getUrlRequest($key) {
        return $this->rout[$key];
    }

    public function __call($name, $arguments) {
        header("HTTP/1.0 404 Not Found");
        $tpl = $this->_config;
        if(isset($tpl['404page'])){
            echo $this->render($tpl['404page']);
        }else{
            echo '<h2>404 not found</h2>';
        }
        exit();
        // echo $name . ' is not defined!';
    }
    
    public function go404() {
        header("HTTP/1.0 404 Not Found");
        $tpl = $this->_config;
        if(isset($tpl['404page'])){
            echo $this->render($tpl['404page']);
        }else{
            echo '<h2>404 not found</h2>';
        }
        exit();
        // echo $name . ' is not defined!';
    }

    public function setControllerName($name) {
        $this->controllerName = $name;
    }

    public function setActionName($name) {
        $this->actionName = $name;
    }

    public function staticize($file)
    {
        $fp = fopen($file, "w+");
        fwrite($fp, ob_get_contents());
        fclose($fp);
    }



}

?>