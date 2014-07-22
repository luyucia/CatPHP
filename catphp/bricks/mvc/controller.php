<?php

/**
 * 
 */


class Controller {

    private    $context = array();
    private    $engine;
    protected  $request;

    function __construct() {
        session_start();
        ob_start();
        $WEB_CONFIG   = CatConfig::getInstance('config/config.php');
        $template     = $WEB_CONFIG->template_engine;
        if ($template === 'tenjin' ) {
            $this->engine = new Tenjin_Engine();
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

    public function getRequest($key,$default = false) {
        if (isset($this->request[$key])) {
            return $this->request[$key];
        }else {
            return $default;
        }
    }

    public function setRequest($req)
    {
        $this->request = $req;
    }

    protected function getRoutRequest($key) {
        return $this->rout[$key];
    }

    public function __call($name, $arguments) {
        echo $name . ' is not defined!';
    }

    public function staticize($file)
    {
        $fp = fopen($file, "w+");
        fwrite($fp, ob_get_contents());
        fclose($fp);
    }



}

?>