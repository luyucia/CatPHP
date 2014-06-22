<?php

/**
 * 
 */


class Controller {

    private $context = array();
    private $engine;

    function __construct($rout) {
        ob_start();
        $this->rout   = $rout;
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

    public function assign($key, $value) {
        $this->context[$key] = $value;
    }

    public function render($tpl) {
        $output = $this->engine->render($tpl, $this->context);
        return $output;
    }

    protected function getRequest() {
        
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