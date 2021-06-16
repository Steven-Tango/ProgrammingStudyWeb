# 框架搭建
## thinkphp安装
1. 安装composer
2. 在项目根目录下打开命令行输入 composer create-project topthink/think tp
3. apache配置httpd.conf讲DocumentRoot的值改为项目目录/tp/public
4. 启动apache 输入localhost 出现tp6欢迎页面表明安装成功

## 多应用模式
1. 在tp目录下打开命令行输入 composer require topthink/think-multi-app
2. 结构目录改为如下：
```
|─app 应用目录
│  ├─index              主应用
│  │  ├─controller      控制器目录--页面和数据库的控制中心
│  │  ├─model           模型目录--与数据库打交道
│  │  ├─view            视图目录--与页面打交道
│  │  ├─route           路由目录--简化url地址（隐藏你页面的真实路径）
│  │  └─ ...            更多类库目录
│  │ 
│  ├─admin              后台应用
│  │  ├─controller      控制器目录
│  │  ├─model           模型目录
│  │  ├─view            视图目录
│  │  ├─route           路由目录
│  │  └─ ...            更多类库目录
│
├─public                WEB目录（对外访问目录）
│  ├─admin.php          后台入口文件
│  ├─index.php          入口文件
│  ├─router.php         快速测试文件
│  └─.htaccess          用于apache的重写
│
├─config                全局应用配置目录
├─runtime               运行时目录
│  ├─index              index应用运行时目录
│  └─admin              admin应用运行时目录
```

3. public下的admin.php的内容和index.php内容相同，目的是增加一个admin的入口文件
## 路由
1. 多应用模式下 route文件夹在app/index/和app/admin下（在app的每个应用文件夹下）
2. 访问localhost/think 出现hello,thinkphp6表示访问成功
    - 报错信息：No input file specified 或者 找不到控制器
    - 解决方式：public/.htaccess修改为下面这句话
    ```
    <IfModule mod_rewrite.c>
    Options +FollowSymlinks -Multiviews
    RewriteEngine On

    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php [L,E=PATH_INFO:$1]
    </IfModule>

    ```

## 连接数据库
1. 将.example.env重命名为.env 
2. 将数据库信息修改为正确的信息，例如
```   
[DATABASE]
TYPE = mysql
HOSTNAME = localhost
DATABASE = course
USERNAME = root
PASSWORD = 123456
HOSTPORT = 3306
CHARSET = utf8
DEBUG = true
```


## 引入view视图
1. 在tp目录下打开命令行输入 composer require topthink/think-view
2. 多应用模式下 view文件夹在app/index/和app/admin下（在app的每个应用文件夹下）

## 引入captcha 验证码
1. 在tp目录下打开命令行输入 composer require topthink/think-captcha
2. 使用<div>{:captcha_img()}</div>
3. 去掉app/middlewware.php的 \think\middleware\SessionInit::class的注释



## 引入前端模块
### layui
1. https://www.layui.com/ 下载layui
2. 解压下载内容后，将layui整个文件夹复制到项目的public/static文件夹下（public文件夹是唯一一个直接向外访问的接口）

### jquery
1. 下载jQuery
2. 将jQuery 放在public/static/js文件夹下

### 使用
1. 在app/index/view下创建一个common的文件夹 创建一个import.html
2. 写入以下代码
```
<link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/js/jquery.js"></script>
<script src="/static/layui/layui.js"></script>
```
3. 在使用到这些文件的地方写入
```
    {include file='common/import/}
```

## 功能划分
 以index项目为例：app/index/下
 - controler 控制器 负责分发页面和请求以及请求处理
    - Index.php 主控制器，负责模板页面
    - Req.php 负责ajax请求反馈
 - model 模型 负责根据得到的参数操作数据库
    -  
 - view 视图 负责前端页面制作
    - index 主页面 放置主控制器需要的模板页面
    - common 公共 放置一些共用的代码块比如引入文件、头部、尾部等
 - route 路由 负责路由分发
    - app.php 放置前端路由地址
    - req.php 放置ajax请求的路由地址



# 代码开始
## 引入模块
在app/index/controller/index.php引入所需要的模块
```
use app\BaseController;
class Index extends BaseController{...}
```
## 在BaseController里引入模块
在app\BaseController引入
```   
use think\facade\View
```
## 后台登录
1. view里新建login文件夹 再新建index.html
```html
   <!-- </head>前面写入 -->
   {include file="common/import"}
    <style>
        body{
            background-color:brown;
        }
        .form-box{
            position:fixed;
            top:50%;
            left:50%;
            transform: translate(-50%,-50%);/* 变形：平移(x轴往左走本身的50%,y往上走本身的50%) */
            width:500px;
            padding:50px;
            background-color:rgba(255,255,255,.8);
        }
        .title{
            font-size:20px;
            padding-bottom:20px;
            font-weight:bold;
            text-align:center;
        }
        .code img{height:38px; width:auto;display:block;}
    </style>
    <!-- <body>里面写入 -->
      <div class="form-box">
        <h3 class="title">编程学习网后台管理页面</h3>
        <form id="form" class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">账户名称</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="name">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">账户密码</label>
                <div class="layui-input-block">
                    <input type="password" class="layui-input" name="pwd">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">验证码</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="code">
                </div>
                <div class="code">{:captcha_img();}</div>
            </div>
            <div class="layui-form-item" style="text-align:center">
                <button class="layui-btn" type="button" onclick="formSubmit()">立即登录</button>
            </div>
        </form>
    </div>
    <script>
        function formSubmit(){
            var form = document.getElementById('form');
            var formData={
                "name":form.name.value,
                "pwd":form.pwd.value,
                "code":form.code.value
            };
            $.post('login',formData,function(res){
                console.log(res);
            });
        }
    </script>
```
2. view里新建common文件夹，再新建import.html(引入一些常用的js/css文件)
```html
<link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/js/jquery.js"></script>
<script src="/static/layui/layui.js"></script>
```
3. route/app.php里添加路由规则
```php
   Route::get('login','login/index')->ext('html');
   Route::post('login','login/login');
```
**改错:router文件夹重命名为route**
4. app\admin\controller\login.php 加入方法
```php
   public function login(){
      return  json(request()->post());
   }
```
5. 访问地址 localhost/admin.php/login.html

## 项目启动
1. 复制代码tp文件夹到C盘，找到tp下的public 复制文件路径C:/tp/public
2. 打开Xampp->apache->config->httpd.conf 修改DocumentRoot的地址为C:/tp/public（两排都要改）
3. 重启apache
4. 浏览器输入localhost/phpmyadmin 导入course.sql
5. 使用VScode打开Tp文件夹
6. 浏览器输入localhost/admin.php/login.html

## 登录的后台
1. 检验传入的数据是否合法-否->状态500 描述传入内容不合法
2. 检验传入验证码是否正确->状态500 验证码不正确
3. 检验账户是否存在 ->状态500 账户不存在
4. 检验密码是否正确 ->状态500 账户名或者密码错误
5. 全部正确->状态200 登录成功 跳转到首页

###
1. 把返回的数据封装成一个方法，方便调用
json() thinkphp提供的一个方法 将数组转化成对象的字符串 json
json([
    "status"=>$s,
    "msg"=>$m,
    "data"=>$d
])
放在BaseController里面
    打开app\BaseController 在方法里面写入
```php
    public function res_json($status,$msg,$data=null){
        return json([
            "status"=>$status,
            "msg"=>$msg,
            "data"=>$data
        ]);
    }
```
2. 获取到前端传回来的post数据
```php
    $res=request()->post();
```
2. 验证数据是否合法 login.php
```php
/* 验证的规则 */
        $roles=[
            "name"=>"require|length:4,30",
            "pwd"=>"require|length:4,20",
            "code"=>"require"
        ];
        /* 如果没有满足应该抛出的异常 */
        $msg=[
            "name.require"=>"账户名为空",
            "name.length"=>"账户名长度为4到30",
            "pwd.require"=>"密码为空",
            "pwd.length"=>"密码长度为4到20",
            "code.require"=>"验证码为空"
        ];
        try{
            $this->validate($res,$roles,$msg);
        }catch(ValidateException $e){
            return $this->res_json(500,$e->massage);
        }catch(\Exception $e){
            return $this->res_json(500,$e->getError());
        }
```
3. 验证验证码
    1. app\middleware.php把 \think\middleware\SessionInit::class的注释去掉
    2. 使用captcha_check()进行验证
    3. view/login/index.html中的返回值时输入一句话
```js
    ...function(res){
        form.code.value="";
        $(".code img").click();
    }
    ...
```
# 模型
模型会自动对应数据表，模型类的命名规则是除去表前缀的数据表名称，采用驼峰法命名，并且首字母大写
## 创建user模型
1. 在app\admin\model 新建一个User.php
```php
    namespace app\admin\model;
    use think\Model;
    class User extends Model{
        protected $table="admin_user";
        protected $schema=[
            "id"=>"int",
            "name"=>"string",
            "pwd"=>"string",
            "createtime"=>"datetime",
            "lastlogin"=>"datetime"
        ];
        public static function select_by_name($name){
            return User::where('name',$name)->findOrEmpty();
        }
        public static function add_lastLogin($id){
            $user=User::where('id',$id)->find();
            if($user){
                $user->lastlogin=date("Y-m-d H:i:s");
                $user->save();
            }
        }
    }
```
2. 回到app\admin\controller\login.php
```php
    function login(){
              /* 1. 得到用户输入的内容（post） */
        $res=request()->post();
        /* 2. 验证输入的内容是否有误 */
        /* 验证的规则 */
        $roles=[
            "name"=>"require|length:4,30",
            "pwd"=>"require|length:4,20",
            "code"=>"require"
        ];
        /* 如果没有满足应该抛出的异常 */
        $msg=[
            "name.require"=>"账户名为空",
            "name.length"=>"账户名长度为4到30",
            "pwd.require"=>"密码为空",
            "pwd.length"=>"密码长度为4到20",
            "code.require"=>"验证码为空"
        ];
        try{
            $this->validate($res,$roles,$msg);
        }catch(ValidateException $e){
            return $this->res_json(500,$e->massage);
        }catch(\Exception $e){
            return $this->res_json(500,$e->getError());
        }
        /* 3. 验证验证码是否正确 */
       if(!captcha_check($res['code'])) return $this->res_json(500,"验证码错误");
       /* 4. 验证数据库了 */
       $user = User::select_by_name($res['name']);       
        // 1. 验证当前name是否存在于数据库
       if(!$user) return $this->res_json(500,"账户不存在");
        // 2. 验证当前密码和数据库保存密码是否相同
        if($user->pwd != md5($res['pwd'])) return $this->res_json(500,"密码不正确");
        // 验证成功 我们还需要做一件事情 把当前的时间存入登录时间里面
        User::add_lastLogin($user->id);
        // 保存session
        session_start();
        $_SESSION['userid']=$user->id;
        return  $this->res_json(200,"登录成功");
    } 
```
3. app\admin\view\login\index.html
```js
    ...
    $.post('login',formData,function(res){
        console.log(res);
        $(".code img").click();
        form.code.value="";
        layer.msg(res.msg);//使用layui输出错误结果
        if(res.status==200){
            setTimeout(function(){
                location.href="index.html";
            },1000);
        }
    });
    ...
```
4. app\admin\controller\ 新建Index.php
```php
    <?php
    namespace app\admin\controller;
    use app\BaseController;
    class Index extends BaseController{
        private $userid;
        protected  function initialize(){//每次调用方法时会先执行的内容
            session_start();
            if(!isset($_SESSION['userid'])){
                return redirect('login.html')->send();
            }
            $this->userid=$_SESSION['userid'];
            echo $this->userid;
          }
        function index(){
            return view();
        }
    }
```
5. app\admin\route\app.php 新增一条路由信息
```php
    Route::get('index','Index/index')->ext('html');
```
6. app\middleware.php里面的\think\middleware\SessionInit::class的注释去掉
7. 在app\admin\view里创建一个index文件夹再创建一个index.html

## 20210601
## 启动项目
1. xampp->congfig->service and port setting->把443 改为4431
2. xampp->apache->config->httpd-ssl.conf 把listen 443 改为 listen 4431
3. xampp->apache->config->httpd.conf 把 documentroot的地址改为D:/tp/public 
4. 启动apache 浏览器访问 localhost/admin.php/login.html
## 加载数据库
1. xampp 启动mysql 
2. 浏览器访问localhost/phpmyadmin
3. 导入->选择course.sql->点击执行

## 登录 查漏补缺
1. app\admin\controller\login.php
```php
<?php
namespace app\admin\controller;
use app\BaseController;
use app\admin\model\User;
class Login extends BaseController{
    public function index(){
        return view();
    }
    public function login(){
        /* 1. 得到用户输入的内容（post） */
        $res=request()->post();
        /* 2. 验证输入的内容是否有误 */
        /* 验证的规则 */
        $roles=[
            "name"=>"require|length:4,30",
            "pwd"=>"require|length:4,20",
            "code"=>"require"
        ];
        /* 如果没有满足应该抛出的异常 */
        $msg=[
            "name.require"=>"账户名为空",
            "name.length"=>"账户名长度为4到30",
            "pwd.require"=>"密码为空",
            "pwd.length"=>"密码长度为4到20",
            "code.require"=>"验证码为空"
        ];
        try{
            $this->validate($res,$roles,$msg);
        }catch(ValidateException $e){
            return $this->res_json(500,$e->massage);
        }catch(\Exception $e){
            return $this->res_json(500,$e->getError());
        }
        /* 3. 验证验证码是否正确 */
       if(!captcha_check($res['code'])) return $this->res_json(500,"验证码错误");
       /* 4. 验证数据库了 */
       $user = User::select_by_name($res['name']);       
        // 1. 验证当前name是否存在于数据库
       if(!$user) return $this->res_json(500,"账户不存在");
        // 2. 验证当前密码和数据库保存密码是否相同
        if($user->pwd != md5($res['pwd'])) return $this->res_json(500,"密码不正确");
        // 验证成功 我们还需要做一件事情 把当前的时间存入登录时间里面
        User::add_lastLogin($user->id);
        // 保存session
        session_start();
        $_SESSION['userid']=$user->id;
        $_SESSION['username']=$user->name;
        $_SESSION['name']="自己的姓名";
        return  $this->res_json(200,"登录成功");
    }
}
?>
```
2. app\admin\model\User.php
```php
<?php
namespace app\admin\model;
use think\Model;
Class User extends Model{
    // 定义数据库的表
    protected $table="admin_user";
    // 定义该表里面的字段
    protected $schema=[
        "id"=>"int",
        "name"=>"string",
        "pwd"=>"string",
        "createtime"=>"datetime",
        "lastlogin"=>"datetime"
    ];

    public static function select_by_name($name){
        // 使用模型进行数据库查询name名等于传入的$name 查询出一条结果或者没有
        $user=User::where("name",$name)->findOrEmpty();
        return $user;
    }
    public static function add_lastLogin($id){
        $user=User::where("id",$id)->find();
        if($user){
            $user->lastlogin=date("Y-m-d H:i:s");
            $user->save();
        }
    }
}
```

## 首页制作
1. appp\BaseController.php,新增一个方法并使用一个模块
    - 第10行 加入一句话：
        use think\exception\HttpResponseException;
    - 在class的最下面 最后一个}前面加入一个方法：
```php
    protected function redirectTO(...$args){
        throw new HttpResponseException(redirect(...$args));
    }
```
2. app\admin\contorller\Index.php的function initialize()修改代码
```php
<?php
   namespace app\admin\controller;
    use app\BaseController;
    use think\facade\View;
    class Index extends BaseController{
        private $userid;
        protected function initialize(){
            session_start();
            if(!isset($_SESSON['userid'])){
                return $this->redirectTo('login.html');
            }
            $this->userid=$_SESSION['userid'];
            View::assign([
                "username"=>$_SESSION['username'],
                "name"=>$_SESSION['name']
            ]);
        }
        function index(){
            return view();
        }
    }
```
3. app\admin\view\index\index.html 随便在哪加入
    {$username.$name}
4. 浏览器访问localhost/admin.php/index.html 如果未登录会跳转到login.html当中

## 写入管理页面的代码
1. app\admin\view\index\index.html写入
```php
    {include file="common/head"}
    首页
    {include file="common/foot"}
```
2. app\admin\view\common 新建两个文件 一个叫head.html 一个叫foot.html
- head.html
```html

```
-foot.html
```html
```

### 修改密码
1. 在app\admin\route\app.php写入路由
```php
    Route::post('resetPwd','login/resetPwd');
    Route::post('loginOut','login/loginOut');
```
2. 在app\admin\controller\login.php中加入新方法 是最后一个}前面
```php
    public function resetPwd(){
        $res=request()->post();
        if(!isset($res['pwd']) || !$res['pwd']){
            return $this->res_json(500,"未输入需要更改的密码");
        }
        session_start();
        if(!isset($_SESSION['userid'])){
            return $this->res_json(501,"您并未登录，请先重新登录");
        }
        if(!User::resetPwd($_SESSION['userid'],$res['pwd'])){
            return $this->res_json(502,"修改失败，请联系管理员");
        }
        return $this->res_json(200,"修改密码成功");
    }
    public function loginOut(){
        session_start();
        $_SESSION=array();
        session_destroy();
        return $this->res_json(200,"退出成功");
    }
```
3. 在app\admin\model\User.php中加入新方法，是最后一个}前面
```php
    public static function resetPwd($userid,$pwd){
        $user=User::where('id',$userid)->findOrEmpty();
        if($user){
            $user->pwd=md5($pwd);
            $user->save();
            return true;
        }
        return false;
    }
```

# 20210602
## 启动项目
1. 复制代码tp文件夹到E
2. 打开xampp->apache->config->httpd.conf 修改DocumentRoot的地址为E:/tp/public(两处)
3. 浏览器访问localhost/phpmyadmin/ 导入course.sql数据库文件->点击执行
4. 浏览器访问localhost/admin.php/ 会自动跳转到login.html上面（如果没有跳转就问老师）
5. VSCode打开E盘的tp文件夹

## 完成head.html的后续工作
1. 修改密码成功之后。如果结果为200 表示成功
    - 弹出修改密码成功的提示框
    - 弹完了之后关闭当前修改密码窗口
    如果结果不为200，就表示失败了
    - 弹出修改密码失败的提示
2. 退出登录成功之后，
    如果结果为200 表示成功
    - 提示一下退出成功，然后跳转到登录页（刷新当前页面就可以了，因为如果没有session值时，我们的程序本身就会跳转到登录页）
    如果结果不为200 表示失败
    - 提示失败的原因

### 代码
1. 修改密码成功与否是在app\admin\view\common\head.html的
```js
...
    $.post('resetPwd',...,function(res){
        console.log(res);
        layer.msg(res.msg);
        if(res.status==200){
            layer.close(index);
        }
    });
```
2. 退出登录：app\admin\view\common\head.html的
```js
    $.post('loginOut',function(res){
        console.log(res);
        layer.msg(res.msg,function(){
            if(res.status==200){
                location.reload();
            }
        })
    });
```
3. 划分container的区域 在head.html的style里面
```css
    body{background-color:#f5f5f5;}
    .container{
        padding-top:50px;
        padding-left:200px;
    }
```
## 账户管理
1. 在app\admin\controller\index.php当中加入新方法，加在最后一个}前面
```php
    function userControl(){
        return view();
    }
```
2. 在app\admin\view\index文件夹下创建一个user_control.html
```html
    {include file="common\head"}
    账户管理
    {include file="common\foot"}
```
3. 在app\admin\route\app.php中加入路由
```php
    Route.get('user',"index/userControl")->ext("html");
```
4. 访问localhost/admin.php/user.html
### 修改细节-当选择了user.html时，菜单导航要被选中
1. 需要去获取我们当前的路由地址是谁
- 在app\admin\controller\index.php的initialize方法中加
```php
    $action = request()->action();
    View::assign("action",$action);
```
2. 需要去判断当路由是user时，需要把对应的样式加载建立
在app\admin\view\common\head.html中修改导航为
```html
    <div class="layui-nav layui-nav-tree layui-bg-cyan layui-inline">
        <!-- 导航项 -->
        <div class="layui-nav-item {if condition="$action == 'index'"}layui-this{/if}">
            <a href="index.html">首页</a>
        </div>
        <div class="layui-nav-item {if condition='$action eq "userControl"'}layui-this{/if}">
            <!-- 链接地址 -->
            <a href="user.html">账户管理</a>
        </div>
        <div class="layui-nav-item">
            <!-- 链接地址 -->
            <a href="#">用户管理</a>
        </div>
        <div class="layui-nav-item">
            <!-- 链接地址 -->
            <a href="#">课程管理</a>
        </div>
    </div>
```
3. 细节 .container的内边距值再调大一点
```css
    .container{
       margin-top:70px;
        margin-left:220px;
        margin-right:20px;
        background-color:#fff;
        min-height:500px;
        border-radius:4px;
        box-shadow:0 0 10px  rgba(0,0,0,.8);
    }
```
4. head.html中的引入样式
```html
<link rel="stylesheet" href="/static/css/admin.css">
```
5. 把user_control.html当中style标签里面的内容copy到\public\static\css\admin.css中(以前的代码删除)
### userControl的传值
1. app\admin\model\User.php 加入新方法 select_all
```php
    public static function select_all($userid){
        return User::select();
    }
```
2. app\admin\controller\Index.php 的userControl加入代码
```php
    function userControl(){
        $db_res=User::select_all($this->userid);
        print_r($db_res);
        exit;
        return view();
    }
```
3. app\admin\controller\Index.php的第5行加入新代码
```php
      use app\admin\model\User;
```
### user_control.html
```html
{include file="common/head"}
<!-- 顶部导航 包含一个搜索内容,一个新增按钮 -->
<div class="top-box">
    <div class="search-box">
        <input type="text" placeholder="请输入需要搜索的内容" class="layui-input">
        <i class="layui-icon layui-icon-search"></i>
    </div>
    <button class="layui-btn layui-btn-normal layui-btn-sm ">新增账户</button>
</div>
<table class="table-box layui-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>账户名</th>
            <th style="width:150px;">操作</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>admin</td>
            <td>
                <div class="layui-btn-group">
                        <a class="layui-btn  layui-btn-xs">重置密码</a>
                        <a class="layui-btn layui-btn-danger layui-btn-xs">删除用户</a>
                </div>
            </td>
        </tr>
    </tbody>
</table>
{include file="common/foot"}
```

## 查漏补缺
1. app\admin\contorller\Login.php
```php
    <?php
namespace app\admin\controller;
use app\BaseController;
use app\admin\model\User;
class Login extends BaseController{
    public function index(){
        return view();
    }
    public function login(){
        /* 1. 得到用户输入的内容（post） */
        $res=request()->post();
        /* 2. 验证输入的内容是否有误 */
        /* 验证的规则 */
        $roles=[
            "name"=>"require|length:4,30",
            "pwd"=>"require|length:4,20",
            "code"=>"require"
        ];
        /* 如果没有满足应该抛出的异常 */
        $msg=[
            "name.require"=>"账户名为空",
            "name.length"=>"账户名长度为4到30",
            "pwd.require"=>"密码为空",
            "pwd.length"=>"密码长度为4到20",
            "code.require"=>"验证码为空"
        ];
        try{
            $this->validate($res,$roles,$msg);
        }catch(ValidateException $e){
            return $this->res_json(500,$e->massage);
        }catch(\Exception $e){
            return $this->res_json(500,$e->getError());
        }
        /* 3. 验证验证码是否正确 */
       if(!captcha_check($res['code'])) return $this->res_json(500,"验证码错误");
       /* 4. 验证数据库了 */
       $user = User::select_by_name($res['name']);       
        // 1. 验证当前name是否存在于数据库
       if(!$user) return $this->res_json(500,"账户不存在");
        // 2. 验证当前密码和数据库保存密码是否相同
        if($user->pwd != md5($res['pwd'])) return $this->res_json(500,"密码不正确");
        // 验证成功 我们还需要做一件事情 把当前的时间存入登录时间里面
        User::add_lastLogin($user->id);
        // 保存session
        session_start();
        $_SESSION['userid']=$user->id;
        $_SESSION['username']=$user->name;
        $_SESSION['name']="自己的姓名";
        return  $this->res_json(200,"登录成功");
    }

    public function resetPwd(){
        $res=request()->post();
        if(!isset($res['pwd']) || !$res['pwd']){
            return $this->res_json(500,"未输入需要更改的密码");
        }
        session_start();
        if(!isset($_SESSION['userid'])){
            return $this->res_json(501,"您并未登录，请重新登录");
        }
        if(!User::resetPwd($_SESSION['userid'],$res['pwd'])){
            return $this->res_json(502,'修改密码失败，请联系管理员');
        }
        return $this->res_json(200,"修改密码成功");
    }

    public function loginOut(){
        session_start();
        $_SESSION=array();
        session_destroy();
        return $this->res_json(200,"退出成功");
    }
}
?>
```
2. app\admin\model\User.php
```php
<?php
namespace app\admin\model;
use think\Model;
Class User extends Model{
    // 定义数据库的表
    protected $table="admin_user";
    // 定义该表里面的字段
    protected $schema=[
        "id"=>"int",
        "name"=>"string",
        "pwd"=>"string",
        "createtime"=>"datetime",
        "lastlogin"=>"datetime"
    ];

    public static function select_by_name($name){
        // 使用模型进行数据库查询name名等于传入的$name 查询出一条结果或者没有
        $user=User::where("name",$name)->findOrEmpty();
        return $user;
    }
    public static function add_lastLogin($id){
        $user=User::where("id",$id)->find();
        if($user){
            $user->lastlogin=date("Y-m-d H:i:s");
            $user->save();
        }
    }
    public static function resetPwd($userid,$pwd){
        $user=User::where("id",$userid)->findOrEmpty();
        if($user){
            $user->pwd=md5($pwd);
            $user->save();
            return true;
        }
        return false;
    }
    public static function select_all($userid){
        return User::select();
    }
}
```
3. app\admin\view\Index\index.html
```php
{include file="common/head"}
首页
{include file="common/foot"}
```
4. app\admin\view\common\head.html
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理页面</title>
    <!-- 引入常用的插件 也就是import.html -->
    {include file="common/import"}
    <link rel="stylesheet" href="/static/css/admin.css">
    <style>
        body{
            background-color:#f5f5f5;
        }
        .top{
            position:fixed;/* 固定定位 */
            top:0;
            left:0;
            right:0;
            height:50px;
            background-color:#333;
            display:flex;/* flex布局 */
            justify-content: space-between;/* 使用flex之后，希望内容左一个右一个 其余内容均匀分布在中间 */
            align-items:center;/* 上下居中 */
        }
        .left{
            padding-left:20px;
            display:inline-flex;/* 内联的flex布局 */
            align-items:center;
        }
        .left .head{
            display:block;
            width:40px;
            height:40px;
            border-radius:50%;/* 圆 */
        }
        .left .name{
            font-size:16px;
            margin-left:10px;
            color:#fff;
        }
        .right{
            padding-right:20px;
        }
        .menu{
            position:fixed;
            top:50px;
            bottom:0;
            left:0;
            width:200px;
            background-color:#2F4056;
            padding-top:20px;
        }
        .container{
            margin-top:70px;
            margin-left:220px;
            margin-right:20px;
            background-color:#fff;
            min-height:500px;
            border-radius:4px;
            box-shadow:0 0 10px  rgba(0,0,0,.8);
        }
    </style>
</head>
<body>
    <div class="header">
        <!-- 顶部 -->
        <div class="top">
            <!-- 左边的头像+姓名 -->
            <div class="left">
                <img src="/static/image/head.jpg" alt="" class="head">
                <p class="name">{$username.$name}</p>
            </div>
            <!-- 右边的修改密码和退出登录 -->
            <div class="right">
                <button class="layui-btn layui-btn-xs" onclick="changePwd()">修改密码</button>
                <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="loginOut()">退出登录</button>
            </div>
        </div>
        <!-- 菜单 -->
        <div class="menu">
            <!-- 导航列表 -->
            <!-- div.layui-nav.layui-nav-tree.layui-bg-cyan.layui-inline -->
            <div class="layui-nav layui-nav-tree layui-bg-cyan layui-inline">
                <!-- 导航项 -->
                <div class="layui-nav-item {if condition="$action == 'index'"}layui-this{/if}">
                    <a href="index.html">首页</a>
                </div>
                <div class="layui-nav-item {if condition='$action eq "userControl"'}layui-this{/if}">
                    <!-- 链接地址 -->
                    <a href="user.html">账户管理</a>
                </div>
                <div class="layui-nav-item">
                    <!-- 链接地址 -->
                    <a href="#">用户管理</a>
                </div>
                <div class="layui-nav-item">
                    <!-- 链接地址 -->
                    <a href="#">课程管理</a>
                </div>
            </div>
        </div>
    </div>
    <script>
    /* formType:1表示输入密码 2表示多行文本 3表示单行文本 */
    function changePwd(){
        layer.prompt({title:"请输入你的密码",formType:1},function(pass,index){
            $.post('resetPwd',{'pwd':pass},function(res){
                console.log(res);
                layer.msg(res.msg);
                if(res.status===200){
                    layer.close(index);
                }
            })
        });
    }
    function loginOut(){
        layer.confirm("您确定要退出登录吗",function(index){
            // 需要使用post提交去调用loginOut方法
            $.post('loginOut',function(res){
                console.log(res);
                layer.msg(res.msg,{shift: -1,time:800},function(){
                    if(res.status==200){
                        location.reload();
                    }
                })
            });
        });
    }
    </script>
    <div class="container">
        <!-- 放置的就是不一样的东西 -->
```
5. app\admin\view\common\foot.html
```html
    </div>
</body>
</html>
```
6. app\admin\route\app.php
```php
<?php
    use think\facade\Route;
    Route::get('login','login/index')->ext('html');    
    Route::post('login','login/login');

    Route::get("index",'Index/index')->ext('html');

    Route::post('resetPwd',"login/resetPwd");
    Route::post('loginOut',"login/loginOut");

    Route::get('user','Index/userControl')->ext('html');
?>
```
7. app\BaseContorller.php
```php
<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use think\facade\View;
use think\exception\HttpResponseException;
/**
 * 控制器基础类
 */
abstract class BaseController
{
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }

    // 返回的json数据打包
    protected function res_json($status,$msg,$data=null){
        return json([
            "status"=>$status,
            "msg"=>$msg,
            "data"=>$data
        ]);
    } 
    // 跳转页面
    protected function redirectTo(...$args)
    {
        // 此处 throw new HttpResponseException 这个异常一定要写
        throw new HttpResponseException(redirect(...$args));
    }
}

```
8. app\admin\view\login\index.html
```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    {include file="common/import"}
    <style>
        body{
            background-color:brown;
        }
        .form-box{
            position:fixed;
            top:50%;
            left:50%;
            transform: translate(-50%,-50%);/* 变形：平移(x轴往左走本身的50%,y往上走本身的50%) */
            width:500px;
            padding:50px;
            background-color:rgba(255,255,255,.8);
        }
        .title{
            font-size:20px;
            padding-bottom:20px;
            font-weight:bold;
            text-align:center;
        }
        .code img{height:38px; width:auto;display:block;}
    </style>
</head>
<body>
    <div class="form-box">
        <h3 class="title">编程学习网后台管理页面</h3>
        <form id="form" class="layui-form">
            <div class="layui-form-item">
                <label class="layui-form-label">账户名称</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="name">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">账户密码</label>
                <div class="layui-input-block">
                    <input type="password" class="layui-input" name="pwd">
                </div>
            </div>
            <div class="layui-form-item">
                <label class="layui-form-label">验证码</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="code">
                </div>
                <div class="code">{:captcha_img();}</div>
            </div>
            <div class="layui-form-item" style="text-align:center">
                <button class="layui-btn" type="button" onclick="formSubmit()">立即登录</button>
            </div>
        </form>
    </div>
    <script>
        function formSubmit(){
            var form = document.getElementById('form');
            var formData={
                "name":form.name.value,
                "pwd":form.pwd.value,
                "code":form.code.value
            };
            $.post('login',formData,function(res){
                console.log(res);
                $(".code img").click();
                form.code.value="";
                layer.msg(res.msg);//使用layui输出错误结果
                if(res.status==200){
                    setTimeout(function(){
                        location.href="index.html";
                    },1000);
                }
            });
        }
    </script>
</body>
</html>
```
9. app\admin\controller\Index.php
```php
<?php
  namespace app\admin\controller;
  use app\BaseController;
  use think\facade\View;
  use app\admin\model\User;
  class Index extends BaseController{
      private $userid;
      protected function initialize(){
          session_start();
          if(!isset($_SESSION['userid'])){
              return $this->redirectTo('login.html');
          }
          $this->userid=$_SESSION['userid'];
          View::assign([
              "username"=>$_SESSION['username'],
              "name"=>$_SESSION['name']
          ]);
          $action = request()->action();
          View::assign("action",$action);
      }
      function index(){
          return view();
      }
      function userControl(){
          $db_res=User::select_all($this->userid);
          print_r($db_res);
          return view();
      }
  }
```



## 用户列表
1. app\admin\controller\Index.php 找到userController方法
    把print_r($db_res)删除,改成View::assign('userlist',$db_res);
2. 数据渲染 :将数据库保存的数据按照相应的样式放置到前台(视图)进行展示
    -app\admin\view\index\user_controller.html
```html
     {notempty name="userlist"}
        <!-- 不为空 -->
            {foreach name="userlist" item="user"}
                <tr>
                    <td>{$user->id}</td>
                    <td>{$user->name}</td>
                    <td>{$user->createtime}</td>
                    <td>{$user->lastlogin}</td>
                    <td>
                        <div class="layui-btn-group">
                            <a class="layui-btn  layui-btn-xs">重置密码</a>
                            <a class="layui-btn layui-btn-danger layui-btn-xs">删除用户</a>
                        </div>
                    </td>
                </tr>
            {/foreach}
        {else/}
        <!-- 为空 -->
        <tr>
            <td colspan="3">暂无数据</td>
        </tr>
        {/notempty}
```
## 新增账户
1. 给新增账户添加onclick事件 app\admin\view\index\user_controller
```html
     <button class="layui-btn layui-btn-normal layui-btn-sm " onclick="addUser()>新增账户</button>
```
2. 添加addUser方法  ->在user_controller.html的最后加<script></script>
```html
<script>
    function addUser(){
        layer.open({
            type:1,
            areas:["500px"],
            content:`{include file = "common/addUserForm"}`
        })
    }
    </script>
```
3. 在app\admin\view\common文件夹中添加addUserForm.html
```html
    <div class="user-form" style="padding:20px; padding-right:40px;">
    <form class="layui-form" >
        <div class="layui-form-item">
            <label class="layui-form-label">账户</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="username">
            </div>
        </div>
        <div class="layui-form-item">
            <label  class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="pwd">
            </div>
        </div>
        <div class="layui-form-item" style="text-align: center">
            <a class="layui-btn">提交</a>
            <button class="layui-btn layui-btn-danger" type="reset">重置</button>
        </div>
    </form>
</div>
```
4. 提交数据
- 在addUserForm.html第二行加进入id="form",给提交按钮添加userFormSubmit事件，最后一行加入
```html 
    <script>
    var index;
    function addUser(){
        index=layer.open({
            type:1,
            area:["500px"],
            title:"新增账户",
            content:`{include file="common/addUserForm"}`
        })
    }
    function userFormSubmit(){
        var form=document.getElementById("form");
        var formDate={
            'username':form.username.value,
            'userpwd':form.pwd.value
        }
        $.post('api/createUser',formDate,function(res){
            layer.msg(res.msg,{shift:-1,time:800},function(){
                if(res.code===200){
                    //关闭弹窗
                    layer.close(index);
                }
            })
        })
    }
</script>
```
### 创建api控制器，来放置我们的需要使用ajax提交的数据接口
1. app\admin\controller里面创建API.php
```php
  namespace app\admin\controller;
use app\BaseController;
Class Api extends BaseController{
    private $userid;
    function initialize(){
        session_start();
        if(!$_SESSION['userid']){
            return $this->redirectTo('login.html');
        }
        $this->userid=$_SESSION['userid'];
    }
    public function createUser(){
        $res=request()->post();
        if(!isset($res['username']) || !isset($res['pwd'])){
            return this->res_json(500,"参数传递错误");
        }
        $roles=[
            "username"=>"require",
            "pwd"=>"require"
        ];
        $msgs=[
            "username.require"=>"账户为空",
            "pwd.require"=>"密码为空",
        ];
        try{
            $this->validate($res,$roles,$msgs);
        }catch(\Exception $e){
            return $this->res_json(500,$e->getError());
        }
        $user=User::create_user($res['username'],$res['pwd']);
        if(!$user) return $this->res_json(500,"添加失败");
        return $this->res_json(200,"添加成功");
    }
}
```
3. 与数据库打交道，在app\admin\model\User.php添加create_user方法
```php
    public static function create_user($username,$pwd){
            $user=new User;
            if(User::where('name',$username)->find()){
                return "账户名已存在";
            }
           $user=new User;
           $user->save([
               'name'=>$username,
               'pwd'=>md5($pwd)
           ]);
           return $user->id;
        }
```
         
### 重置密码
1. app\admin\controller\Api.php加入resetPwd方法
```php
    public function resetPwd(){
            $res=request()->post();
            if(!isset($res['userid'])){
                return $this->res_json(500,"未传入需要修改的密码");
            }
            if(User::resetPwd($res['userid'])){
                return $this->res_json(200,"重置密码成功");
            }
            return $this->res_json(500,"重置密码失败");
        }
```
2. app\admin\view\Index\User_controller.html找到重置密码添加resetPwd方法
```html
<button class="layui-btn  layui-btn-xs" onclick="resetPwd({$user->id},{$user->name})">重置密码</button>
```
3. 在app\admin\view\Index\User_controller.html script里面加入resetPwd方法
```js
    function resetPwd(userid,username){
        layer.confirm("你确定要重置"+username+"的密码",function(index){
            $.post("api/resetPwd",{"userid":userid},function(res){
                layer.msg(res.msg,{shift:-1,time:800},function(){
                    if(res.status==200){
                        layer.close(index);
                    }
                })
            })
        });
    }
```
4. app\admin\model\User.php加入resetPwd方法
```php
    
       public static function resetPwd($userid,$pwd="1234"){
           $user=User::where('id',$userid)->findOrEmpty();
           if($user){
               $user->pwd=md5($pwd);
               $user->save();
               return true;
           }
           return false;
       }
```

## 删除账户
1.  app\admin\controller\Api.php加入delUser方法
```php
    public function delUser(){
            $res=request()->post();
            if(!isset($res['userid'])){
                return $this->res_json(500,"未传入需要删除的账户");
            }
            if(User::delUser($res['userid'])){
                return $this->res_json(200,"删除账户成功");
            }
            return  $this->res_json(500,"删除账户失败");
        }
   ``` 
2. app\admin\view\Index\user_controller添加delUser事件
    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="delUser('{$user->id}','{$user->name}')">删除用户</button>
3. app\admin\view\model\User.php加入delUser方法
```php
 public static function delUser($userid){
            $user=User::where('id',$userid)->find();
            if(!user) return 0;
            $user->delete();
            return true;
        }
```
4. app\admin\view\Index\user_controller的script里面添加delUser方法
```js
    function delUser(userid,username){
        layer.confirm("你确定要删除"+username+"的账户",function(index){
            $.post("api/delUser",{"userid":userid},function(res){
                layer.msg(res.msg,{shift:-1,time:800},function(){
                    if(res.status==200){
                        layer.close(index);
                        location.reload();
                    }
                })
            })
        });
    }
```
## 搜索账户
1. app\admin\model\User.php修改select_all方法
```php
    public static function select_all($userid){
            if($search){
                return User::where("name",'like',"%".$search."%")->select();
            }
            return User::select();
        }

```
2. app\admin\controller\Index.php修改找到userController()改写为
    ```php
    function userController()
    {
        $res=request()->get();
        if(isset($res['search'])){
            $db_res=User::select_all($res['search']);
        }else{
            $db_res=User::select_all();
        }
        View::assign("userlist",$db_res);
        return view();
    }
    ```
3. app\admin\view\Index\user_controller 找到搜索按钮为input添加searcheInput事件、i标签添加search事件
    ```html
    <input type="text" placeholder="请输入需要搜索的内容" id="searchInput" class="layui-input">
        <i class="layui-icon layui-icon-search" onclick="search()"></i>
    ```
4. app\admin\view\Index\user_controller里的script添加 search方法
```js
    function search(){
        var searchInput=document.getElementById('searchInput');
        var searchValue=searchInput.value;
        location.href="?search="+searchValue;
    }
```
## 课程列表
1. 修改数据库的结构，给course表加入外键约束
```sql
ALTER TABLE course ADD CONSTRAINT admin_id FOREIGN KEY(admin_id) REFERENCES admin_user(id)
```
2. app\admin\controller\Index.php添加方法
```php
    public function course(){
        $courses=Course::select_all();
        View::assign("couseList",$courses);
        return view();
    }
```
-- Index.php上面加入use..
```
    use app\admin\model\Course;
```
3. app\admin\model新建文件Course.php
```php
    <?php
        namespace app\admin\model;
        use think\model;
        Class Course extends Model{
            protected $table="course";
            //连接admin_user表
            public function user(){
                return $this->belongsTo(User::calss,'admin_id');
            }
            public static function select_all(){
                return Course::with('User')->select();
            }
        }
    ?>  
```
4. app\admin\view\Index文件夹下新建html文件 course.html
```html
    {include file="common/head"}
    课程管理
    {$couseList}
    {include file="common/foot"}
```
5. app\admin\view\common\head.html找到链接的课程管理修改为
```html
    <div class="layui-nav-item">
        <a href="#">课程管理</a>
    </div>
    改为
     <div class="layui-nav-item {if condition='$action eq "Course"'}layui-this{/if}">
            <a href="course.html">课程管理</a>
    </div>
```
6. 修改路由
```php
    Route::get('course','index/Course')->ext('html');
```


##  课程管理页面
1. 修复bug-> app\admin\view\index\course.html ->去复制index\user_Controller.html
2. course.html中的新增账户改为新增课程  addUser()->addCourse()
3. 修改table
    -修改thead
    ```php
        <thead>
            <tr>id</tr>
            <tr>课程名称</tr>
            <tr>课程描述</tr>
            <tr>课程图片</tr>
            <tr>课程创建时间</tr>
            <tr>课程创建人</tr>
            <th style="width:150px;">操作</th>
        </thead>
    ```
    ```php
     <tbody>
        {notempty name="courseList"}
        <!--不为空-->
        {foreach name="courseList" item="course"}
        <tr>
            <td>{$course->id}</td>
            <td>{$course->coursename}</td>
            <td>{$course->coursedesc}</td>
            <td>{$course->courseimg}</td>
            <td>{$course->createtime}</td>
            <td>{$course->user->name}</td>
            <td>
                <div class="layui-btn-group">
                    <button class="layui-btn  layui-btn-xs" onclick="onchange('$course')">修改课程</button>
                    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="delCour('{$course->id}','{$course->coursename}')">删除课程</button>
                </div>
            </td>
        </tr>
        {/foreach}
        {else/}
        <!--为空-->
        <tr><td colspan="3">暂无数据</td></tr>
        {/notempty}
        
    </tbody>
    ```

## 新增账户
1. course.html中最后加入script标签
2. 在script标签里面加入
    