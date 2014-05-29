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
    }

    


}
