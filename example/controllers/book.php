<?php
/**
 * @name bookController
 * @author Luyu
 * @desc 默认控制器
 */
class bookController extends Controller {

    /**
     * 默认动作
     */
    public function index() {

        echo '<h1>Title:'.$this->getParam('title').'</h1>';
        echo '<h1>Page:'.$this->getParam('page').'</h1>';

    }

    public function test(){
        echo "OK";
    }

    public function read(){
        echo "<h1>BookId:".$this->getParam('bookid');
    }

}
