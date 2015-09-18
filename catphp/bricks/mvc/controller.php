<?php

/**
 *
 */


class Controller {

    private    $app_config;
    private    $context = array();
    private    $engine_name;
    private    $engine;
    public     $controllerName;
    public     $actionName;
    protected  $request;
    protected  $logger;

    function __construct() {
        session_start();
        ob_start();
        $this->app_config   = CatConfig::getInstance(APP_PATH.'/config/config.php');
        $log_level    = $this->app_config->log_level;
        if($log_level){
            $this->logger = new Logging();
            $this->logger->setLevel($log_level);
        }


        // D($this->app_config);
    }

    public function setRoute($rout) {
        $this->rout   = $rout;
    }

    public function assign($key, $value) {
        $this->context[$key] = $value;
    }

    // 绘制页面
    public function render($tpl,$out=true) {
        $this->engine_name     = $this->app_config->template_engine['type'];
        if ($this->engine_name === 'tenjin' ) {
            $properties = array('cache' => false);
            $this->engine = new Tenjin_Engine($properties);
            $output = $this->engine->render(CAT_VIEW_PATH.$tpl, $this->context);
            if ($out) {
                echo $output;
            }
            return $output;
        }
        elseif ($this->engine_name === 'smarty') {
            $this->engine = new Smarty();
            $this->engine->debugging      = $this->app_config->template_engine['debug'];
            $this->engine->caching        = $this->app_config->template_engine['cache'];
            $this->engine->cache_lifetime = $this->app_config->template_engine['cache_lifetime'];
            foreach ($this->context as $key => $value) {
                $this->engine->assign($key,$value);
            }
            // return $this->engine->display($tpl);
            $output = $this->engine->fetch(CAT_VIEW_PATH.$tpl);
            if ($out) {
                echo $output;
            }
            return $output;
        }else{
            extract($this->context);
            include CAT_VIEW_PATH.$tpl;
        }
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
        throw new Exception("Error Method '$name' Not Found", 1);
    }

    public function go404() {
        header("HTTP/1.0 404 Not Found");
        if(isset($this->app_config->error_page)){
            echo $this->render($this->app_config->error_page);
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
