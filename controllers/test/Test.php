<?php
/**
 * @name TestController
 * @author Luyu
 */
class TestController extends Controller {


    public function indexAction() {
        header("Content-type: text/html; charset=utf-8"); 
        echo "<a href='test/test'>请点击</a>";
        echo P("8d",6);
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
