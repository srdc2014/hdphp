# HDPHP 

* 后盾网HDPHP框架是一个为用PHP程序语言编写网络应用程序的人员提供的软件包。 提供强大的、完整的类库包,满足开发中的项目需求,可以将需要完成的任务代码量最小化，大大提高项目开发效率与质量。高效的核心编译处理机制让系统运行更快。
* 做为优秀的框架产品,在系统性能上做的大量的优化处理,只为让程序员使用HDPHP框架强悍的功能同时,用最短的时间完成项目的开发。

----
# 环境要求
* PHP版本需要5.1+

----
# 100%免费
* HDPHP是完全免费的，你不用担心任何版权问题 
* 你可以用在任意网站（包括商业网站）你不需要支付任何费用

----
# 交流
* 后盾网论坛： [http://bbs.houdunwang.com](http://bbs.houdunwang.com/forum-105-1.html "后盾网论坛")

* HDPHP官网： [http://www.hdphp.com ](http://www.hdphp.com "HDPHP官网")

![后盾网  人人做后盾](https://git.oschina.net/houdunwang/hdphp/raw/master/hdphp/Data/Image/houdunwang.jpg)  

----
# 全面的WEB开发特性支持
* HDPHP是否完全免费的，你不用担心任何版权问题
* 提供多项优化策略，速度非常快
* 采用 MVC 设计模式
* URL全站路由控制
* 支持Memcached、Redis等NoSql
* 高效的HDView模板引擎
* 拥有全范围的类库
* 通过自定义类库、辅助函数来实现框架的扩展
* JS前端自动验证
* PHP自动验证、自动完成、字段映射、表单令牌
* 高级扩展模型
* 全站缓存控制
* 中文分词
* 商城购物车处理
* RBAC角色控制
* 完整的错误处理机制
* 集成前端常用库（编辑器、文件上传、图片缩放等等）
* 对象关系映射(ORM)
* 与后盾网hdjs完美整合

----
#安全性
框架在系统层面提供了众多的安全特性，确保你的网站和产品安全无忧。这些特性包括：

* COOKIE加密处理
* 数据预处理机制
* XSS安全防护
* 表单自动验证
* 强制数据类型转换
* 输入数据过滤
* 表单令牌验证
* 防SQL注入
* 图像上传检测

----
#商业友好的开源协议
HDPHP遵循Apache2开源协议发布。Apache Licence是著名的非盈利开源组织Apache采用的协议。该协议和BSD类似，鼓励代码共享和尊重原作者的著作权，同样允许代码修改，再作为开源或商业软件发布。

----

# 更新日志 #

### 2014-10-02
1. 修复在PHP 5.2版本时，SESSION值错误问题
2. 修改JSCONST标签错误

### 2014-09-29
1. 修复入口文件不设置DEBUG是不创建模块目录错误
2. 修复多对多关联时RelationModel错误的问题
3. 修复PHPEXCEL一处变量定义在低版本PHP环境下造成的错误问题
4. 修复在php5.3以下Controller类iscache方法报错问题

### 2014-09-25
1. 修复relation关联删除失败问题
2. 修复hd_submit当不设置form表单action属性时无法提交的问题

### 2014-09-23

1. 增加Data::hasChild()方法，检测是否有子栏目
2. 修复IE8下部分的HDJS错误
3. 修改模型table()方法更换表后，字段不还原问题


### 2014-09-21

1. hdjs拆分为hdvalidate、hdslide、hdjs三部分
2. upload标签将message与water等属性独立设置
3. 修复upload标签上传时报isimage键名不存在错误
4. 修复在php5.4以上版本中报preg_replace函数e模式错误

