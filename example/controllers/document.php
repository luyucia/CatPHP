<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class documentController extends BaseController {


    public function createProject(){
        $data['name']         = Request::input('name');
        $data['user_id']      = 1;
        $data['created_time'] = date('Y-m-d H:i:s');
        $rs =  $this->mongo->insert('projects',$data);
        $this->echoJson(1,$rs);
    }

    public function listProject(){
        $condition['user_id'] = 1;
        $rs = $this->mongo->find('projects',$condition);
        foreach ($rs as &$row) {
            $row['_id'] = (string)$row['_id'];
        }
        $this->echoJson(1,$rs);
    }

    /**
     * 添加章节
     */
    public function addChapter() {
//         echo "<h1>Welcome to Use CatPHP</h1>";
//         $text = <<<EOF

// ### 第一步：下载CatPHP
// 下载地址：https://github.com/luyucia/CatPHP
// ### 第二步：将样例工程复制到您的web服务器工作目录下
// 将example目录复制到您的工作目录下（nginx默认为html）
// ### 第三步：配置Nginx或Apache的路由重写规则
// 假如您使用Nginx则：
// 请在nginx目录下找到conf/nginx.conf文件，并添加重写规则：

//     if (!-e \$request_filename) {
//             rewrite ^.*$ /index.php last;
//         }

// 这样nginx会将所有请求不到的url请求发送到 index。php

// 完成：现在您就可以在浏览器中访问您的项目了 exp：http://127.0.0.1

// EOF;
//         // $this->assign("title",'Welcome');
//         // $this->render('views/index.tpl');
//         $mk = new Parsedown();
//         echo $mk->parse($text);
        $data['project_id']   = Request::input('project_id');
        $data['user_id']      = 1;
        $data['name']         = Request::input('name');
        $data['content']      = '';
        $data['created_time'] = date('Y-m-d H:i:s');
        $rs = $this->mongo->insert('document',$data);
        $this->echoJson(1,$rs);

    }


    // 加载列表
    public function lists(){
        $condition['project_id'] = Request::input('project_id');
        $fields = array('name','_id');
        $rs = $this->mongo->find('document',$condition,array(),$fields);
        foreach ($rs as &$row) {
            $row['_id'] = (string)$row['_id'];
        }
        $this->echoJson(1,$rs);

    }

    // 保存文章
    public function save(){
        $condition['_id']    = new MongoId(Request::input('doc_id')) ;
        $new_data['content'] = Request::input('content','','default',false);
        $rs = $this->mongo->update('document',$condition,array('$set'=>$new_data));
        $this->echoJson(1,$rs);

    }

    // 读取文章
    public function read(){
        $condition['_id']    = new MongoId(Request::input('doc_id')) ;
        $rs = $this->mongo->findOne('document',$condition);
        $this->echoJson(1,$rs);
    }

    // 删除文章
    public function delete(){
        $condition['_id'] = Request::input('doc_id');
        $rs = $this->mongo->remove('document',$condition);
        $this->echoJson(1,$rs);
    }


    public function export(){
        $condition['project_id'] = Request::input('project_id');
        $fields = array('name','_id','content');
        $rs = $this->mongo->find('document',$condition,array(),$fields);

        $mk = new Parsedown();
        $docs = array();
        foreach ($rs as $row) {
            $doc            = array();
            $doc['name']    = $row['name'];
            $doc['content'] = $mk->parse($row['content']);
            $docs[] = $doc;
        }

        $this->assign("docs",$docs);
        $this->render('views/document.html');

        $this->staticize('runtime/document.html');

    }

}
