<?php
/**
* Html Table
*/
class HtmlUl
{
    private $li = array();
    private $ul_attr;
    function __construct($attrs='')
    {
        $this->ul_attr = $attrs;
    }

    public function getHtml() {
        $lists = implode($this->li, "\n");
        $html = "<ul ".$this->ul_attr." >$lists</ul>";
        return $html;
    }

    public function addli($content,$attrs = '') {
        $this->li[] = '<li '.$attrs.' >'.$content.'</li>';
    }
}


// $ul = new HtmlUl('class="sdfsd"');
// $ul->addLi('<a>ssss</a>');
// $ul->addLi('<a>sssdfss</a>','class="active"');
// $ul->addLi('<a>s222sss</a>');
// // $ul->getUl('1')
// // $ul->addLi($ul->getHtml());
// echo $ul->getHtml();