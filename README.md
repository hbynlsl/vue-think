
vue-think 基于ThinkPHP和Vue框架的API开发服务
===============

本开发服务是个人工作中使用的应用程序模板，基于ThinkPHP5.1和Vue-Cli 3.1 封装而成。主要特性如下：

 + 完整保留ThinkPHP5开发特性
 + 完整保留Vue-Cli 3.1 命令行工具开发特性
 + ThinkPHP层，增加 php think api 命令，快速生成API控制器-模型类-数据表迁移文件
 + Vue层，实现打包路径配置，打包成功后，直接在ThinkPHP环境中运行即可
 + Vue层，添加ResourceComponent组件，涵盖典型资源的CRUD操作


> ThinkPHP5的运行环境要求PHP5.6以上。

## 安装和使用

### 前提条件

* 已正常配置php和composer
* 已正常配置node环境，要求node 8+
* 在命令行中可以正常使用php、composer、npm、yarn等命令

### 下载安装

使用git下载

~~~
git clone https://gitee.com/liushilong/vue-think.git vuethink
~~~

### 使用

#### 启动服务器端程序

~~~
cd vuethink
php think run
~~~

然后就可以在浏览器中访问

~~~
http://localhost:8000
~~~

#### 启动客户端开发服务

~~~
npm run serve
~~~

然后就可以在浏览器中访问

~~~
http://localhost:88
~~~

#### 快速生成资源

~~~
php think api 资源名称
~~~

> 即可快速生成资源控制器类、模型类、数据表迁移文件等
> 资源名称可以是单个单词（如user），也可以是包含模块目录（如restful/user）

#### Vue中使用ResourceComponent组件


## 在线手册

+ [ThinkPHP开发手册](https://www.kancloud.cn/manual/thinkphp5_1/content)
+ [Vue开发手册](https://cn.vuejs.org/v2/guide/) 

## 目录结构

初始目录结构如下：

~~~
www  WEB部署目录（或者子目录）
├─application           ThinkPHP应用目录
│  ├─view               Vue打包后静态入口文件目录
├─config                ThinkPHP应用配置目录
├─route                 ThinkPHP路由定义目录
├─public                ThinkPHP WEB目录（对外访问目录）
│  ├─static             Vue打包后的静态资源目录
│  ├─index.php          入口文件
├─thinkphp              ThinkPHP框架系统目录
├─vendor                PHP第三方类库目录（Composer依赖库）
├─composer.json         composer 定义文件
├─think                 命令行入口文件
├─node_modules          前端JS第三方类库目录（npm依赖库）
├─package.json          Vue前端依赖配置文件
├─vue.config.js         Vue-Cli 3 打包配置文件
~~~



vue-think® vue-think.hbynlsl.cn

