<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class BaseController extends Controller {

    function __construct(){
        parent::__construct();
        $config = array(
            'username'=>'catdoc',
            'password'=>'catdoc',
            'host'=>'127.0.0.1',
            'port'=>27017,
            'database'=>'catdoc',
            );
        $this->mongo = MongoDriver::getInstance($config);
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Headers:Origin, X-Requested-With, Content-Type, Accept");
        
    }

    public function echoJson($code,$info=array()){
        $data = array();
        $data['code'] = $code;
        $data['info'] = $info;
        echo json_encode($data);
    }

}
