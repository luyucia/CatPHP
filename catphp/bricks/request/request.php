<?php
/**
 * CatPHP
 *
 * An open source php development framework for PHP
 *
 * @package     CatPHP
 * @author      luyucia@gmail.com
 * @link        http://www.catphp.org
 * @since       Version 1.0
 * @filesource
 */
/**
*
*/
class Request
{
    static $method     = 'all';
    static $body_input = null;
    static $filter_map = array(
            'default' => FILTER_DEFAULT,                 //默认
            'regexp'  => FILTER_VALIDATE_REGEXP,         //根据 regexp，兼容 Perl 的正则表达式来验证值
            'url'     => FILTER_VALIDATE_URL,            //把值作为 URL 来验证
            'email'   => FILTER_VALIDATE_EMAIL,          //把值作为 e-mail 来验证
            'ip'      => FILTER_VALIDATE_IP,             //把值作为 IP 地址来验证
            'int'     => FILTER_VALIDATE_INT,            //把值作为 IP 地址来验证
            'float'   => FILTER_VALIDATE_FLOAT,          //把值作为 IP 地址来验证
        );

    function __construct($method = 'all')
    {
        self::$method = $method;
    }
    // 处理单变量
    private static function handle_input_var($var,$filter,$safe) {
        $var = filter_var($var,$filter);
        // 安全过滤
        if($safe && !get_magic_quotes_gpc()) {
            $var = addslashes($var);
        }
        // 类型转换
        if ($filter==FILTER_VALIDATE_INT) {
            $var = intval($var);
        }else if ($filter==FILTER_VALIDATE_FLOAT) {
            $var = floatval($var);
        }

        return $var;

    }
    /**
     * 获取输入参数 GET POST
     * @param  变量名
     * @param  默认值
     * @param  过滤器
     * @param  是否进行安全过滤
     * @return [type]
     */
    public static function input($var_name,$default=false,$filter='default',$safe=true )
    {
        $filter = self::$filter_map[$filter];
        // 先判断变量在get中还是post中,并取出变量
        if (isset($_POST[$var_name]) && (self::$method == 'all' || self::$method == 'post') ) {
            $var = $_POST[$var_name];
        }elseif (isset($_GET[$var_name])  && (self::$method == 'all' || self::$method == 'get') ) {
            $var = $_GET[$var_name];
        }elseif (isset($_COOKIE[$var_name])  && (self::$method == 'all') ) {
            $var = $_COOKIE[$var_name];
        }elseif (isset(self::$body_input[$var_name])  && (self::$method == 'all')) {
            $var = self::$body_input[$var_name];
        }
        else{
           $headers =  getallheaders();
           if(isset($headers['Content-Type']) && stripos($headers['Content-Type'], "application/json")!==false){
                $request_body = file_get_contents('php://input');
                self::$body_input = json_decode($request_body,true);
                $var = self::$body_input[$var_name];
           }
        }


        // 如果没有值则取默认值
        if (!isset($var) || $var==NULL) {
           return $default;
        }

        // 如果是数组遍历并进行过滤
        if (is_array($var)) {
            foreach ($var as &$v) {
                $v = self::handle_input_var($v,$filter,$safe);
            }
            return $var;
        }else{
            return self::handle_input_var($var,$filter,$safe);
        }


    }




}

// $_GET['aa'] = 123;
// $req = new Request();

// var_dump($req->input('aa',3,FILTER_VALIDATE_INT));



?>
