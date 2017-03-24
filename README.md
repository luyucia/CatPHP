CatPHP
======

CatPHP is a simple modularity PHP framework
------
## 编码规范
1. 文件命名：小写加下划线
2. 类名：首字母大写，驼峰
3. 函数名：驼峰
4. 缩进：4空格
5. 大括号：非换行式，{ 之前要有1空格
样例：
```php
/**
 * @name 名字
 * @abstract 申明变量/类/方法
 * @access 指明这个变量、类、函数/方法的存取权限
 * @author 函数作者的名字和邮箱地址
 * @category 组织packages
 * @copyright 指明版权信息
 * @const 指明常量
 * @deprecate 指明不推荐或者是废弃的信息
 * @example 示例
 * @exclude 指明当前的注释将不进行分析，不出现在文挡中
 * @final 指明这是一个最终的类、方法、属性，禁止派生、修改。
 * @global 指明在此函数中引用的全局变量
 * @include 指明包含的文件的信息
 * @link 定义在线连接
 * @module 定义归属的模块信息
 * @modulegroup 定义归属的模块组
 * @package 定义归属的包的信息
 * @param 定义函数或者方法的参数信息
 * @return 定义函数或者方法的返回信息
 * @see 定义需要参考的函数、变量，并加入相应的超级连接。
 * @since 指明该api函数或者方法是从哪个版本开始引入的
 * @static 指明变量、类、函数是静态的。
 * @throws 指明此函数可能抛出的错误异常,极其发生的情况
 * @todo 指明应该改进或没有实现的地方
 * @var 定义说明变量/属性。
 * @version 定义版本信息
 */
class ExampleClass {

    function exampleFunction() {

        if ( isset($a) ) {
            echo 'a';
        }
    }
}

```

- 性能规范：
1. 任何函数执行10万次要在1s内完成

- 设计规范：
1. 任何类不依赖于框架，可单独使用

----------

# CatPHP


## 快速开始
#### 第一步：下载CatPHP

github地址 ：https://github.com/luyucia/CatPHP
```
git clone https://github.com/luyucia/CatPHP.git
```

#### 第二步：将样例工程复制到您的web服务器工作目录下

将example目录复制到您的工作目录下（nginx默认为html）

#### 第三步：配置Nginx或Apache的路由重写规则
nginx设置：
```
if (!-e $request_filename)
{
    rewrite ^.*$ /index.php last;
}
```
- 注意，要放在location ~ \.php${............}后面
- rewrite ^/(.*)$ /index.php last;
rewrite p1 p2 flag
将url中，匹配正则表达式p1的地方，替换成p2进行访问。
原理：将url请求解析到index.php，由框架中的路由解析功能，解析请求后加载指定的类

完成：现在您就可以在浏览器中访问您的项目了 exp：http://127.0.0.1
## 设计思想
### CatPHP的理念

为什么要开发CatPHP
目前已经有很多非常优秀的PHP框架，如国内的ThinkPHP，国外的CodeItenger，Zend，并且每个稍微大点的公司也都有自己开发的框架，有PHP层面的框架，还有扩展形式的框架，可谓是五花八门。这其实给PHP开发这带来了一些麻烦，那就是不能走到哪都用自己熟悉的东西，比如在上家公司用ThinkPHP，在这家公司就得用CI，又或者之前自己开发了框架，到了新公司又不让用了，这种不统一带来了很多麻烦，虽然说学习一个新框架对成手PHP还是一件很简单的事，但我觉得人生短暂，不应该为这种重复而没有意义的事情而浪费生命，因此我希望CatPHP可以统一PHP框架，可能是有些不现实，但我要解释一下理由，其实PHP框架大多针对web应用开发，而这类框架的核心无非是配置管理，目录规范，自动加载和路由功能，而开发者在写逻辑中打交道最多的还是各种功能类库的调用，而以往的框架通常难以做到在框架中用你最熟悉的类库，比如在thinkphp中调用ci的数据库操作类，并且通常框架的源码虽然开放，但是要想做二次开发还是要研究源码的，而大多的框架只有使用手册和API手册，对于源码的设计思想都是不开放的，要搞清楚一个整体的设计思路还是要花些时间的，那么CatPHP是怎么解决这些问题的呢，首先CatPHP的设计思路简单，明了，有详尽的文档来描述设计思路，并且所有功能都是模块化的，是真正的模块化，也就是说任何功能，不依赖于框架，可单独使用，这样设计的好处就是，任何人可以简单的开发某个功能模块，然后很轻松的集成到CatPHP当中，一个人的能力总是有限的，不可能开发出各种完美的功能，并且每个人所处的工作领域都是不同的，一个人设计的功能不可能满足各个领域的各种应用，就拿db模块来说，每个人需要的封装力度是不同的，有的人需要ORM，有的则需要自己写sql，那么以往的框架是不能做到这点的，而CatPHP的功能是模块化的，是可替换的，可根据具体项目需求选择不同的封装力度的模块，任何人都可以写这些模块，CatPHP是属于每一个人的，并且CatPHP是可以嵌入到任何其他框架当中的，这样就不用担心跳槽后再去学习新的框架。

- 1、彻底的开源,思想层面上的开源<br>
开源不应该仅仅是代码的共享，更应该是思想的共享。很多很好的开源软件非常的优秀，只可惜阅读他的源代码是非常困难的，并不是因为代码本身写的不好或注释不完整，而是因为不了解整体架构和背景。我认为每一行代码都是有他的背景的，为何这样写而不那样写，这么写的作用是什么，优点是什么，缺点是什么

- 2、非侵入式的PHP框架<br>
多数PHP框架是为了WEB而生，但PHP却不仅仅可以用来做WEB，因此CatPHP希望任何的应用都可以使用本框架，并且希望在项目生命周期的任何阶段都可以引入CatPHP，这就意味着CatPHP是完全解耦与项目的，你可以使用CI、Zend等任何的框架，但需要CatPHP的时候随时可以加进来。


- 3、简单与性能优先<br>
在功能、性能、设计的权衡中，性能与简单优先。
## 扩展框架
#### 目录结构
bricks catphp模块
bricks-v 第三方模块
core 内核
config 框架自身的配置文件与应用无关
catphp.php 框架入口文件

#### 添加自己写的模块

1. 开发一个类，放到bricks目录下
2. 在config中找到coreclassconfig.php 添加路径到这个文件中即可

## 配置
```php
return array(
    // 模板文件路径
    'view_path'=>'views/',
    // 控制器文件路径，controller都会在这个目录下查找
    'controller_path'=>'controllers/',
    // 控制器子目录，如果希望在控制器目录下放子目录可在此设置
    'controller_dirs' => array('test/','api/'),
    // 设置哪些controller为RESTfule形式
    'rest_controllers'=>array('product','api'),
    // 设置路由模式，router_rest为true时，采用Rest风格路由，为false则采用参数形式，router_name参数生效
    'router_rest'     => true,
    'router_name'     =>array(
            'c' => 'c',
            'a' => 'a',
        ),
    // 设置日志等级
    'log_level'       => Logging::ALL,
    // 模板引擎 可选（tenjin smarty php）
    'template_engine'=>array(
        'type'  => 'smarty',
        'debug' => false,
        'cache' => false,
        'cache_lifetime' => 120,
    ),
    // 设置路由规则，详细内容请参考文档中的路由专题
    'route_rules'=>array(
        // 静态路由 和参数
            array(
            "rule" => "read/title/page",
            "controller" => 'book',
            "action" => 'test'
            ),
            array(
            "rule" => "read/:title/:page",
            "controller" => 'book',
            "action" => 'index'
            ),
            array(
            "rule" => "read/(\d{6})",
            "controller" => 'book',
            "action" => 'read',
            "reg_map"=>array('bookid')
            ),

        ),
    );
```
## 路由
## 控制器

## 模板
在controller中
```php
$this->assign('title','hello');
$this->render('test.html');
或
$context  = ['title'=>'hello'];
$this->render('test.html',$context);
```
模板样例
```html
<html>
  <body>
    <h1>{=$title=}</h1>
    <table>
<?php $i = 0; ?><?php foreach ($items as $item) { ?><?php     $color = ++$i % 2 == 0 ? '#FFCCCC' : '#CCCCFF'; ?>
      <tr bgcolor="{==$color=}">
        <td>{==$i=}</td>
        <td>{=$item=}</td>
      </tr>
<?php } ?>
    </table>
  </body>
</html>
```

## 数据库
#### 连接
```php
$dbconfig    = require APP_PATH . '/config/dbconfig.php';
$db          = new CatDB($dbconfig['dbname']);
```

数据库配置文件 dbconfig.php:
```php
return array(

    'dbname'=>array(
        'type'=>'mysql',
        'host'=>'127.0.0.1',
        'username'=>'root',
        'password'=>'xxxxxx!',
        'database'=>'dbname',
        'port'=>' 3306',
        'charset'=>'utf8'
    ),
);
```
#### 查询
```php
// 直接sql语句查询
$rs = $db->query("select * from tablename");

// 利用sql自动创建查询
$rs = $db->table('user')->where('id',30,'>')->where('age',18,'>')->query();

```
#### 查询-分页
```php
第一页
$rs = $db->table('user')->where('id',30,'>')->where('age',18,'>')->page(1)->query();

设置每页50条
$rs = $db->table('user')->where('id',30,'>')->where('age',18,'>')->page(1,50)->query();

适用于app的分页
$rs = $db->table('user')->page(1,20,true)->query();



```
#### 查询-缓存
```php
$rs = $db->table('user')->where('id',30,'>')->where('age',18,'>')->page(1,20)->cache(600)->query();

$rs = $db->table('user')->where('id',30,'>')->where('age',18,'>')->page(1,20)->cache(600,'user')->query();
```
#### 插入
```php

$data['username'] = 'test';
$data['age']      = 18;

$db->table('user')->insert($data);
//获取插入的id
$db->lastInsertId();

```
#### 更新
```php
$data['age']      = 19;

$db->table('user')->where('username','test')->update($data);

```
#### 删除
```php
$db->table('user')->where('username','test')->delete();
```
#### 事务
```php
$db->beginTransaction();
do somthing
$db->commit();

```

