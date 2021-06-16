<?php /*a:5:{s:69:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\index\user_contro.html";i:1623773780;s:63:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\common\head.html";i:1623769743;s:65:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\common\import.html";i:1623768399;s:63:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\common\foot.html";i:1623768399;s:71:"D:\DevTools\phpstudy_pro\WWW\tp\app\admin\view\common\addUsersForm.html";i:1623771211;}*/ ?>
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
<style>
    .top-box {
        padding: 10px;
        display: flex;
        justify-content: space-between;
    }

    .search-box {
        width: 300px;
        position: relative;
    }

    .layui-input {
        padding-right: 40px;
    }

    .search-box .layui-icon {
        position: absolute;
        right: 3px;
        top: 0;
        font-size: 20px;
        color: #bbb;
        width: 38px;
        height: 38px;
        text-align: center;
        line-height: 40px;
        cursor: pointer;
        border-radius: 0 50% 50% 0;
    }

    .search-box .layui-icon:hover {
        color: #000;
    }

    .table-box th,
    .table-box td {
        text-align: center;
        border: 0 none;
    }

    .table-box th {
        background-color: #2F4056;
        color: #fff;
    }
    .table-box tr:nth-child(2n){
        background-color: silver;
    }
</style>
<!-- 顶部导航 包含一个搜索内容,一个新增按钮 -->
<div class="top-box">
    <div class="search-box">
        <input type="text" placeholder="请输入需要搜索的内容" id="searchInput" class="layui-input">
        <i class="layui-icon layui-icon-search" onclick="search()"></i>
    </div>
    <button class="layui-btn layui-btn-normal layui-btn-sm " onclick="addUsers()">新增用户</button>
</div>
<table class="table-box layui-table">
    <thead>
        <tr>
            <th>id</th>
            <th>用户名</th>
            <th>用户名密码</th>
            <th>创建时间</th>
            <th>最后登录时间</th>
            <th>电话</th>
            <th style="width:150px;">操作</th>
        </tr>
        
    </thead>
    <tbody>
        <?php if(!(empty($usersList) || (($usersList instanceof \think\Collection || $usersList instanceof \think\Paginator ) && $usersList->isEmpty()))): ?>
        <!--不为空-->
        <?php if(is_array($usersList) || $usersList instanceof \think\Collection || $usersList instanceof \think\Paginator): if( count($usersList)==0 ) : echo "" ;else: foreach($usersList as $key=>$users): ?>
        <tr>
            <td><?php echo htmlentities($users->id); ?></td>
            <td><?php echo htmlentities($users->name); ?></td>
            <td><?php echo htmlentities($users->pwd); ?></td>
            <td><?php echo htmlentities($users->createtime); ?></td>
            <td><?php echo htmlentities($users->lastlogin); ?></td>
            <td><?php echo htmlentities($users->tel); ?></td>
            <td>
                <div class="layui-btn-group">
                    <button class="layui-btn  layui-btn-xs" onclick="resetUserPwd('<?php echo htmlentities($users->id); ?>','<?php echo htmlentities($users->name); ?>')">重置密码</button>
                    <button class="layui-btn layui-btn-danger layui-btn-xs" onclick="delUsers('<?php echo htmlentities($users->id); ?>','<?php echo htmlentities($users->name); ?>')">删除用户</button>
                </div>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; else: ?>
        <!--为空-->
        <tr><td colspan="3">暂无数据</td></tr>
        <?php endif; ?>
        
    </tbody>
</table>
</div>
</body>

</html>
<script>
    function search(){
        var searchInput=document.getElementById('searchInput');
        var searchValue=searchInput.value;
        location.href="?search="+searchValue;
    }


    var index;
    function addUsers(){
        index=layer.open({
            type:1,
            area:["500px"],
            title:"新增账户",
            content:`<div class="user-form" style="padding:20px; padding-right:40px;">
    <form class="layui-form" id="form">
        <div class="layui-form-item">
            <label class="layui-form-label">用户</label>
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
        <div class="layui-form-item">
            <label  class="layui-form-label">电话</label>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="tel">
            </div>
        </div>
        <div class="layui-form-item" style="text-align: center">
            <a class="layui-btn" onclick="usersFormSubmit()">提交</a>
            <button class="layui-btn layui-btn-danger" type="reset">重置</button>
        </div>
    </form>
</div>`
        })
    }
    function usersFormSubmit(){
        var form=document.getElementById("form");
        var formData={
            'username':form.username.value,
            'pwd':form.pwd.value,
            'tel':form.tel.value,
        }
        $.post('api/createUsers',formData,function(res){
            layer.msg(res.msg,{shift:-1,time:800},function(){
                if(res.status==200){
                    layer.close(index);
                    location.reload();
                }
            })
        })
    }
        /* 实现重置密码共功能 */
    function resetUserPwd(userid,username){
        layer.confirm("你确定要重置"+username+"的密码",function(index){
            $.post("api/resetUserPwd",{"userid":userid},function(res){
                layer.msg(res.msg,{shift:-1,time:800},function(){
                    if(res.status==200){
                        layer.close(index);
                        location.reload();
                    }
                })
            })
        });
    }
    /* 实现删除用户功能 */
    function delUsers(userid,username){
        layer.confirm("你确定要删除"+username+"的账户",function(index){
            $.post("api/delUsers",{"userid":userid},function(res){
                layer.msg(res.msg,{shift:-1,time:800},function(){
                    if(res.status==200){
                        layer.close(index);
                        location.reload();
                    }
                })
            })
        });
    }
</script>