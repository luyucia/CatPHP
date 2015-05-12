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
    static $filter_map = array(
            'default' => FILTER_DEFAULT,                 //默认
            'regexp'  => FILTER_VALIDATE_REGEXP,         //根据 regexp，兼容 Perl 的正则表达式来验证值
            'vurl'    => FILTER_VALIDATE_URL,            //把值作为 URL 来验证
            'vemail'  => FILTER_VALIDATE_EMAIL,          //把值作为 e-mail 来验证
            'vip'     => FILTER_VALIDATE_IP,             //把值作为 IP 地址来验证
            'int'     => FILTER_VALIDATE_INT,            //把值作为 IP 地址来验证
            'float'   => FILTER_VALIDATE_FLOAT,          //把值作为 IP 地址来验证
        );

    function __construct($method = 'all')
    {
        self::$method = $method;
    }

    public function input($var_name,$default=false,$filter='default',$safe=true )
    {
        $filter = self::$filter_map[$filter];

        if (self::$method == 'all') {
            $var = filter_input(INPUT_POST, $var_name ,$filter);
            if ($var==NULL) {
                $var = filter_input(INPUT_GET, $var_name ,$filter);
            }
        }else if(self::$method == 'post') {
            $var = filter_input(INPUT_POST, $var_name ,$filter);
        }else if(self::$method == 'get') {
            $var = filter_input(INPUT_GET, $var_name ,$filter);
        }

        if($safe && !get_magic_quotes_gpc()) {
            $var = addslashes($var);
        }
        // 如果没有值则取默认值
        if ($var!=NULL) {
            return $var;
        }else{
            return $default;
        }
    }




}

// $_GET['aa'] = 123;
// $req = new Request();

// var_dump($req->input('aa',3,FILTER_VALIDATE_INT));



?>
