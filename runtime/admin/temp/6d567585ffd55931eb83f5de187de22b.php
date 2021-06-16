<?php /*a:4:{s:53:"D:\phpstudypro\WWW\tp\app\admin\view\index\index.html";i:1622516526;s:53:"D:\phpstudypro\WWW\tp\app\admin\view\common\head.html";i:1622518302;s:55:"D:\phpstudypro\WWW\tp\app\admin\view\common\import.html";i:1621911790;s:53:"D:\phpstudypro\WWW\tp\app\admin\view\common\foot.html";i:1622516519;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理页面</title>
    <!-- 引入常用的插件--import.html -->
    <link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/js/jquery.js"></script>
<script src="/static/layui/layui.js"></script>
    <style>
        .top {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            min-width: 900px;
            height: 50px;
            background-color: #333;
            display: flex;
            /* 流式布局使用flex之后，希望内容左一个，右一个，其余内容均分布在中间 */
            justify-content: space-between;
            align-items: center;
            /* 上下居中 */
        }

        .left {
            padding-left: 20px;
            display: inline-flex;
            /* 内联flex布局 */
            align-items: center;
        }

        .left .head {
            display: block;
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .left .name {
            font-size: 16px;
            margin-left: 10px;
            color: #fff;
        }

        .right {
            padding-right: 20px;
        }

        .menu {
            position: fixed;
            top: 50px;
            bottom: 0;
            left: 0;
            width: 200px;
            background-color: #2f4056;
            padding-top: 20px;
        }
    </style>
</head>

<body>

    <div class="header">
        <!-- 顶部 -->
        <div class="top">
            <!-- 左边的头像与姓名 -->
            <div class="left">
                <img src="/static/image/head.jpg" class="head">
                <p class="name"><?php echo htmlentities($username.$name); ?></p>
            </div>
            <!-- 右边的修改密码和退出登录 -->
            <div class="right">
                <button class="layui-btn layui-btn-xs" onclick="changePwd()">修改密码</button>
                <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="loginOut()">退出登录</button>
            </div>
        </div>
        <!--菜单 -->
        <div class="menu">
            <!-- 导航列表 -->
            <div class="layui-nav layui-nav-tree layui-bg-cyan layui-inline">
                <!--  导航项  -->
                <div class="layui-nav-item">
                    <!-- 链接地址 -->
                    <a href="">账户管理</a>
                </div>
                <div class="layui-nav-item">
                    <!-- 链接地址 -->
                    <a href="">用户管理</a>
                </div>
                <div class="layui-nav-item">
                    <!-- 链接地址 -->
                    <a href="">课程管理</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        function changePwd() {
            /* formType:1 表示输入密码  formType:2 表示多行文本 formType:3 表示单行文本 */
            layer.prompt({ title: "请输入你的密码", formType: 1 }, function (pass, index) {
                $.post('resetPwd',{'pwd':pass},function(res){
                    console.log(res);
                })
            });
        }

        function loginOut() {
            layer.confirm("你确定要退出登录吗？", function (index) {
               //需要使用post提交
               $.post('loginOut',function(res){
                   console.log(res);
               });
            });
        }

    </script>
    <div class="container">
        <!-- 放置不一样的东西 -->
首页
</div>
</body>

</html>