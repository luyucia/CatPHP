CatPHP
======

CatPHP is a simple modularity PHP framework
------
CatPHP的理念

- 1、彻底的开源,思想层面上的开源<br>
开源不应该仅仅是代码的共享，更应该是思想的共享。很多很好的开源软件非常的优秀，只可惜阅读他的源代码是非常困难的，并不是因为代码本身写的不好或注释不完整，而是因为不了解整体架构和背景。我认为每一行代码都是有他的背景的，为何这样写而不那样写，这么写的作用是什么，优点是什么，缺点是什么

- 2、非侵入式的PHP框架<br>
多数PHP框架是为了WEB而生，但PHP却不仅仅可以用来做WEB，因此CatPHP希望任何的应用都可以使用本框架，并且希望在项目生命周期的任何阶段都可以引入CatPHP，这就意味着CatPHP是完全解耦与项目的，你可以使用CI、Zend等任何的框架，但需要CatPHP的时候随时可以加进来。


- 3、简单与性能优先<br>
在功能、性能、设计的权衡中，性能与简单优先。

- 动态
1. catphp.org域名已买下!官网建设中
2. 数据库操作类，及执行时间监控报告模块正在开发中
3. 多模板引擎支出正在完善中

- tasklist
1. 官网
2. html模块中form生成器
3. 安全检查工具类
4. 静态化工具类


- 编码规范
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
nginx设置：
```
if (!-e $request_filename) 
{
    rewrite ^.*$ /webtest/index.php last;
}
```
- 注意，要放在location ~ \.php${............}后面
--rewrite ^/(.*)$ /webtest/index.php last;
rewrite p1 p2 flag
将url中，匹配正则表达式p1的地方，替换成p2进行访问。
原理：将url请求解析到index.php，由框架中的路由解析功能，解析请求后加载指定的类


url规则

1、router_rest 
```

```


