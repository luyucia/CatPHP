<?php
/**
 * @name TestController
 * @author Luyu
 */
class TestController extends Controller {


    public function indexAction() {
        header("Content-type: text/html; charset=utf-8"); 
        
        $dbconfig = array(
        'type'=>'  mysql',
        'host'=>'e 192.168.106.22',
        'username'=>'  Jason @ ',
        'password'=>'689"3',
        'database'=>'tes t',
        'port'=>' 33\'06 ',
        'encoding'=>'utf8   !'
        );
        
        $tool = new Tools($dbconfig);
        D($dbconfig);
        $data = $tool->F("e")->R()->GetArr();
        D($data);
    }

    public function testAction() {
        echo 'hello cat';
        $dbconfig = array(
        'type'=>'mysql',
        'host'=>'192.168.106.22',
        'username'=>'Jason',
        'password'=>'6893',
        'database'=>'test',
        'port'=>'3306',
        'encoding'=>'utf8'
        );

        $db = new Db($dbconfig);
        $data = $db->query("select * from users");

        $table = new HtmlTable();
        $table->setData($data);
        echo $table->getHtml();

    }

    


}
