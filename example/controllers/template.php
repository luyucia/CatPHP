<?php
/**
 * @name TemplateController
 * @author Luyu
 * @desc 默认控制器
 */
class TemplateController extends Controller {

    /**
     * 默认动作
     */
    public function index() {
        $title = 'catphp';
        $this->assign('title',$title);

        $data = array(
            array(1,'testA'),
            array(2,'testB'),
            );
        $htmlTable = new HtmlTable();
        $htmlTable->setData($data);
        $this->assign('result',$htmlTable->getHtml());

        $this->assign('items',array(1,2));
        $this->render('table.phtml');
    }

}
