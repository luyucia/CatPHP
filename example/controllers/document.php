<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class documentController extends BaseController {

    function __construct(){
        parent::__construct();
        date_default_timezone_set('Asia/Shanghai');
        $_SESSION['project_id'] = '555ede4815d55cdc2900003f';
    }


    public function createProject(){
        $data['name']         = Request::input('name');
        if ($data['name']) {
            $data['user_id']      = 1;
            $data['created_time'] = date('Y-m-d H:i:s');
            $rs =  $this->mongo->insert('projects',$data);
            $this->echoJson(1,$rs);
        }else{
            $this->echoJson(2);
        }
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
        $rs = $this->mongo->find('document',$condition,array('sort'=>array('sort'=>1)),$fields);
        foreach ($rs as &$row) {
            $row['_id'] = (string)$row['_id'];
        }
        $this->echoJson(1,$rs);

    }

    // 保存文章
    public function save(){
        $condition['_id']    = new MongoId(Request::input('doc_id')) ;
        $new_data['content'] = $_POST['content'];
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
        $condition['_id'] = new MongoId(Request::input('doc_id')) ;
        $rs = $this->mongo->remove('document',$condition);
        $this->echoJson(1,$rs);
    }

    // 重命名
    public function rename(){
        $condition['_id'] = new MongoId(Request::input('doc_id')) ;
        $new_data['name'] = Request::input('newname','','default',false);
        $rs = $this->mongo->update('document',$condition,array('$set'=>$new_data));
        $this->echoJson(1,$rs);
    }

    // 菜单排序
    public function menuSort(){
        $ids = Request::input('doc_ids');
        foreach ($ids as $i => $id) {
            $condition['_id'] = new MongoId($id) ;
            $new_data['sort'] = $i;
            $rs = $this->mongo->update('document',$condition,array('$set'=>$new_data));
        }
        $this->echoJson(1,$rs);
    }


    public function export(){
        $condition['project_id'] = Request::input('project_id');
        //todo 
        $projectName = 'catphp';

        $fields = array('name','_id','content');
        $rs = $this->mongo->find('document',$condition,array('sort'=>array('sort'=>1)),$fields);

        $mk = new Parsedown();
        $docs = array();
        foreach ($rs as $row) {
            $doc            = array();
            $doc['name']    = $row['name'];
            $doc['content'] = $mk->parse($row['content']);
            $docs[] = $doc;
        }

        $this->assign("docs",$docs);
        $content = $this->render('views/document.html',false);
        // $this->staticize('runtime/index.html');
        $download_config   = CatConfig::getInstance(APP_PATH.'/config/download.conf.php');
        $download_zip_name = APP_PATH.'/runtime/'.$projectName.date('YmdHis').'.zip';
        $zip = new Zip($download_zip_name, ZipArchive::OVERWRITE);
        $zip->addContent($content,'index.html');
        foreach ($download_config->get('default') as $file) {
            $zip->addFile(APP_PATH.'/runtime/'.$file,$file);
        }
        $zip->close();
        $this->download($download_zip_name,$projectName.'.zip');
        // $this->zip($this->render('views/document.html'));
        // var_dump($download_config->get('default'));

    }

    private function download($file_path,$file_name){
        if(!file_exists($file_path)){ 
            echo "export failed!"; 
            return ; 
        } 
        $fp=fopen($file_path,"r"); 
        $file_size=filesize($file_path); 
        //下载文件需要用到的头 
        Header("Content-type: application/octet-stream"); 
        Header("Accept-Ranges: bytes"); 
        Header("Accept-Length:".$file_size); 
        Header("Content-Disposition: attachment; filename=".$file_name); 
        $buffer=1024; 
        $file_count=0; 
        //向浏览器返回数据 
        while(!feof($fp) && $file_count<$file_size)
        { 
            $file_con=fread($fp,$buffer); 
            $file_count+=$buffer; 
            echo $file_con; 
        }
    }

    // private function zip($indexContent){
    //     $zip = new ZipArchive;
    //     $res = $zip->open(APP_PATH.'/runtime/test.zip', ZipArchive::OVERWRITE);
    //     if ($res === TRUE) {
    //         $zip->addFromString('index.html', $indexContent);
    //         $zip->close();
    //         echo 'ok';
    //     } else {
    //         echo 'failed';
    //     }
    // }

}
