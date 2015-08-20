<?php

/**
* 路由
*/
class router
{
    public $module;
    public $controller;
    public $action;
    private $url_param;
    private $rout_arr;
    private $rout_len;
    private $rule_arr;
    private $global;

    function __construct($url,$global = false)
    {
        $this->global = $global;
        $this->urlParse($url);
    }

    private function urlParse($url)
    {
        // 去除开头结尾'/',和url中的参数(?后面的东西)
        $question_mark_pos = stripos($url, '?');
        if($question_mark_pos) {
            $url = trim( substr($url, 0 ,  $question_mark_pos),"/");
        } else {
            $url = trim( $url,"/");
        }
        // 忽略index.php的影响
        if (stripos($url,'index.php')===0) {
            $url = substr($url, 10);
        }
        $this->rout_arr = explode('/', $url);
        if (!isset($this->rout_arr[0]) || $this->rout_arr[0]==='') {
            $this->rout_arr[0] = 'index';
        }
        if (!isset($this->rout_arr[1])) {
            $this->rout_arr[1] = 'index';
        }
        $this->rout_len = count($this->rout_arr);
    }

    // 路由解析
    // 测试 10万次执行时间<1s
    // private static function routeParseRest() {
    //     $rtn = array(
    //         'c' => 'index',
    //         'a' => 'index'
    //     );

    //     $index = strpos($_SERVER['SCRIPT_NAME'], '/', 1) + 1;
    //     $url = rtrim( substr($_SERVER['REQUEST_URI'] , $index),"/");
    //     $e = strpos($url, '?');
    //     if ($e) {
    //         $url = substr($url, 0, $e);
    //     }
    //     if ($url) {
    //         $r = explode('/', $url);
    //         $rtn['c'] = $r[0];

    //         // 如果设置了action
    //         if (isset($r[1])) {
    //             $rtn['a'] = $r[1]==''?'index':$r[1];
    //             $l = count($r);
    //             if ($l>2 && $l % 2 != 0) {
    //                 $l-=1;
    //                 $hasLast = true;
    //             }
    //             for ($i = 2; $i < $l; $i+=2) {
    //                 if($r[$i]==='c' || $r[$i]==='a')
    //                     continue;
    //                 $rtn[$r[$i]] = $r[$i + 1];
    //             }
    //             if(isset($hasLast))
    //                 $rtn['last'] = $r[$l];
    //         }
    //         return $rtn;
    //     } else {
    //         return $rtn;
    //     }
    // }

    // private static function routeParse($cName, $aName) {

    //     $rtn = array(
    //         'c' => 'index',
    //         'a' => 'index'
    //     );
    //     if (isset($_GET[$cName]) && !empty($_GET[$cName])) {
    //         $rtn['c'] = $_GET[$cName];
    //     }
    //     if (isset($_GET[$aName]) && !empty($_GET[$aName])) {
    //         $rtn['a'] = $_GET[$aName];
    //     }

    //     return $rtn;
    // }

    public function getRouting()
    {
        $match    = false;
        // 按照路由规则解析路由
        if (isset($this->rule_arr)) {
            foreach ($this->rule_arr as $rule) {
                $rtn_data = array();
                $ri  = 0;
                for ($i=0; $i < $this->rout_len; $i++) {
                    // 如果是:开头，直接匹配
                    if(isset($rule['rule'][$i]) && $rule['rule'][$i][0]===':') {
                        $rtn_data[substr($rule['rule'][$i], 1)] = $this->rout_arr[$i];
                        $match = true;
                    } //如果静态匹配直接略过
                    else if(isset($rule['rule'][$i]) && $rule['rule'][$i]===$this->rout_arr[$i]) {
                        $match = true;
                        continue;
                    }
                    else if(isset($rule['rule'][$i]) && $rule['rule'][$i]==='*') {
                        for ($j=$i; $j < $this->rout_len; $j++) {
                            if (isset($this->rout_arr[$j+1])) {
                                $rtn_data[$this->rout_arr[$j]] = $this->rout_arr[$j+1];
                                $j++;
                            }
                        }
                    }else if(isset($rule['rule'][$i]) && $rule['rule'][$i][0]==='(') {
                        $reg = rtrim(substr($rule['rule'][$i], 1) ,')');
                        if(preg_match('/'.$reg.'/',$this->rout_arr[$i])){
                            $match = true;
                            $rtn_data[$rule['reg_map'][$ri]] = $this->rout_arr[$i];
                            $ri++;
                        }else{
                            $match = false;
                            break;
                        }
                    }
                    // 如果路由规则中没配第二个参数比如 :xxx 则认为第二个参数为index并且已经匹配[2014-09-18]
                    else if($match==true && $this->rout_arr[1]=='index') {
                        $match = true;
                    }
                    else {
                        $match = false;
                        break;
                    }
                }
                if($match) {
                    $this->controller = $rule['controller'];
                    $this->action     = $rule['action'];
                    break;

                }
            }
        }
        // 如果规则没有匹配的执行默认解析
        if(!$match) {
            // 如果设置全局路由则解析模块名 url规则为：模块名/控制器/方法
            if($this->global && isset($this->rout_arr[2]))
            {
                $this->module     = $this->rout_arr[0];
                $this->controller = $this->rout_arr[1];
                $this->action     = $this->rout_arr[2];
                for ($j=3; $j < $this->rout_len; $j++) {
                    if (isset($this->rout_arr[$j+1])) {
                        $rtn_data[$this->rout_arr[$j]] = $this->rout_arr[$j+1];
                        $j++;
                    }
                }
            }
            else
            {
                $this->controller = $this->rout_arr[0];
                $this->action     = $this->rout_arr[1];
                for ($j=2; $j < $this->rout_len; $j++) {
                    if (isset($this->rout_arr[$j+1])) {
                        $rtn_data[$this->rout_arr[$j]] = $this->rout_arr[$j+1];
                        $j++;
                    }
                }
            }
        }
        if (isset($rtn_data)) {
            $this->url_param = $rtn_data;
        }

    }

    public function getUrlParam()
    {
        return $this->url_param;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function addRoute($rule)
    {
        $rule['rule'] = explode('/', $rule['rule']);
        $this->rule_arr[] = $rule;
    }
}


// $r = new router('/product/30335f?aa=22');
// $r = new router('news/2014/06/01?aa=22');
// $r = new router('help');
// $r = new router('/product/30309/');
// $r = new router('/product/read/pid/999/ggg/2/d');

// $rule0 =array(
//     "rule"=>":user/:project",
//     "controller"=>'project',
//     "action"=>'show',
//     );
// $rule1 =array(
//     "rule"=>"product/(^\d{5}$)",
//     "controller"=>'product',
//     "action"=>'show',
//     "reg_map"=>array('productid')
//     );
// $rule2 =array(
//     "rule"=>"product/(^\d{4}$)",
//     "controller"=>'product',
//     "action"=>'showCategory',
//     "reg_map"=>array('categoryid')
//     );
// $rule3 =array(
//     "rule"=>"news/:year/:month/:day",
//     "controller"=>'news',
//     "action"=>'showCategory',
//     "reg_map"=>array('categoryid')
//     );
// $rule4 =array(
//     "rule"=>"blog/(\d)",
//     "controller"=>'blog',
//     "action"=>'showCategory',
//     "reg_map"=>array('blogid')
//     );
// $rule5 =array(
//     "rule"=>"help",
//     "controller"=>'help',
//     "action"=>'showCategory',
//     "reg_map"=>array('blogid')
//     );

// $r->addRoute($rule0);
// $r->addRoute($rule1);
// $r->addRoute($rule2);
// $r->addRoute($rule3);
// $r->addRoute($rule4);
// $r->addRoute($rule5);

// for ($i=0; $i < 100000; $i++) {
//     $r->getRouting();
// }
// $r->getRouting();

// print_r( $r->getUrlParam() );
// echo $r->getController()."\n";
// echo $r->getAction();
?>
