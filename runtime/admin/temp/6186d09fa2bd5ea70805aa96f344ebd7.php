<?php /*a:4:{s:63:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\index\index.html";i:1623768399;s:63:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\common\head.html";i:1623769743;s:65:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\common\import.html";i:1623768399;s:63:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\common\foot.html";i:1623768399;}*/ ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>管理页面</title>
    <!--引入常用的插件 也就是import.html-->
    <link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/js/jquery.js"></script>
<script src="/static/layui/layui.js"></script>
    <!-- 加入user_ontroller样式 -->
    <link rel="stylesheet" href="/static/css/admin.css">
    <style>
        body {
            background-color: lightslategray;
        }

        .top {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 50px;
            background-color: #333;
            display: flex;
            /*flex布局*/
            justify-content: space-between;
            /*使用flex后，希望内容左一个右一个 其余内容均匀分布在中间*/
            align-items: center;
        }

        .left {
            padding-left: 20px;
            display: inline-flex;
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
            width: 200px;
            background-color: #2F4056;
            padding-top: 20px;
        }

        .container {
            margin-top: 70px;
            margin-left: 220px;
            margin-right: 20px;
            background-color: rgb(249, 252, 255);
            min-height: 500px;
            border-radius: 4px;
            box-shadow: 0 0 10px rgba(0, 0, 0, .8);
        }
    </style>
</head>

<body>
    <div class="header">
        <!--顶部-->
        <div class="top">
            <!--左边的头像+姓名-->
            <div class="left">
                <a href="index.html"><img src="/static/image/head.jpg" alt="" class="head"></a>
                <p class="name"><?php echo htmlentities($username.$name); ?></p>
            </div>
            <!--右边的修改密码和退出登录-->
            <div class="right">
                <button class="layui-btn layui-btn-xs" onclick="changePwd()">修改密码</button>
                <button class="layui-btn layui-btn-xs layui-btn-normal" onclick="loginOut()">退出登录</button>
            </div>
        </div>
        <!--菜单-->
        <div class="menu">
            <!--导航列表-->
            <div class="layui-nav layui-nav-tree layui-bg-cyan layui-inline">
                <!--导航项-->
                <div class="layui-nav-item <?php if($action == 'index'): ?>layui-this<?php endif; ?>">
                    <a href="index.html">首页</a>
                </div>
                <div class="layui-nav-item <?php if($action == 'userController'): ?>layui-this<?php endif; ?>">
                    <!--链接地址-->
                    <a href="user.html">账户管理</a>
                </div>
                <div class="layui-nav-item <?php if($action == "userContro"): ?>layui-this<?php endif; ?>">
                    <a href="userContro.html">用户管理</a>
                </div>
                <div class="layui-nav-item <?php if($action == "course"): ?>layui-this<?php endif; ?>">
                    <a href="course.html">课程管理</a>
                </div>
            </div>
        </div>
    </div>
    <script>
        /*formType:1表示输入密码，2表示多行文本，3表示单行文本*/
        function changePwd() {
            layer.prompt({
                title: "请输入您的密码",
                formType: 1
            }, function (pass, index) {
                $.post('resetPwd', {
                    'pwd': pass
                }, function (res) {
                    console.log(res);
                    layer.msg(res.msg);
                    if (res.status == 200) {
                        layer.close(index);
                    }
                });
            });
        }

        function loginOut() {
            layer.confirm("您确定要退出登录吗", function (index) {
                $.post('loginOut', function (res) {
                    console.log(res); //控制台中的提示，与代码本身无关
                    layer.msg(res.msg, {
                        shift: -1,
                        time: 800
                    }, function () {
                        if (res.status == 200) {
                            location.reload();
                        }
                    })
                });
            });
        }
    </script>
    <div class="container">
        <!--放置的就是不一样的东西-->
首页
</div>
</body>

</html>