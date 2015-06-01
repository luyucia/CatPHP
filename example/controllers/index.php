<?php
/**
 * @name IndexController
 * @author Luyu
 * @desc 默认控制器
 */
class indexController extends Controller {

    /**
     * 默认动作
     */
    public function index($name = "Stranger") {
        // phpinfo();
        header("Content-Type: text/html;charset=utf-8");
        echo "<h1>Welcome to Use CatPHP</h1>";
        $text = <<<EOF

### 第一步：下载CatPHP
下载地址：https://github.com/luyucia/CatPHP
### 第二步：将样例工程复制到您的web服务器工作目录下
将example目录复制到您的工作目录下（nginx默认为html）
### 第三步：配置Nginx或Apache的路由重写规则
假如您使用Nginx则：
请在nginx目录下找到conf/nginx.conf文件，并添加重写规则：

    if (!-e \$request_filename) {
            rewrite ^.*$ /index.php last;
        }

这样nginx会将所有请求不到的url请求发送到 index。php

完成：现在您就可以在浏览器中访问您的项目了 exp：http://127.0.0.1

EOF;
        // $this->assign("title",'Welcome');
        // $this->render('views/index.tpl');
		$mk = new Parsedown();
		echo $mk->parse($text);


    }

}
