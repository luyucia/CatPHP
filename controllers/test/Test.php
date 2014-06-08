<?php
/**
 * @name TestController
 * @author Luyu
 */
class TestController extends Controller {


    public function indexAction() {
        echo "<a href='test/test'>请点击</a>";
    }

    public function testAction() {
        echo 'hello cat';
        $dbconfig = array(
        'type'=>'mysql',
        'host'=>'192.168.31.216',
        'username'=>'root',
        'password'=>'root',
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
