<?php /*a:4:{s:38:"E:\tp\app\admin\view\index\course.html";i:1623811660;s:37:"E:\tp\app\admin\view\common\head.html";i:1623769743;s:39:"E:\tp\app\admin\view\common\import.html";i:1623768399;s:37:"E:\tp\app\admin\view\common\foot.html";i:1623768399;}*/ ?>
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
<!-- 顶部导航 包含一个搜索内容,一个新增按钮 -->
<div class="top-box">
    <div class="search-box">
        <input type="text" placeholder="请输入需要搜索的内容" id="searchInput" class="layui-input">
        <i class="layui-icon layui-icon-search" onclick="search()"></i>
    </div>
    <button class="layui-btn layui-btn-normal layui-btn-sm" onclick="addCourse()">新增课程</button>
</div>
<table class="table-box layui-table">
    <thead>
        <thead>
            <tr>
                <th>id</th>
                <th>课程名称</th>
                <th>课程描述</th>
                <th>课程封面</th>
                <th>创建时间</th>
                <th>创建人</th>
                <th>操作</th>
            </tr>
        </thead>
    </thead>
    <tbody>
        <?php if(!(empty($courseList) || (($courseList instanceof \think\Collection || $courseList instanceof \think\Paginator ) && $courseList->isEmpty()))): ?>
        <!-- 不为空 -->
        <?php if(is_array($courseList) || $courseList instanceof \think\Collection || $courseList instanceof \think\Paginator): if( count($courseList)==0 ) : echo "" ;else: foreach($courseList as $key=>$course): ?>
        <tr>
            <td><?php echo htmlentities($course->id); ?></td>
            <td><?php echo htmlentities($course->coursename); ?></td>
            <td><?php echo htmlentities($course->coursedesc); ?></td>
            <td><img src="/storage/<?php echo htmlentities($course->courseimg); ?>" style="max-height:50px;"></td>
            <td><?php echo htmlentities($course->createtime); ?></td>
            <td><?php echo htmlentities($course->user->name); ?></td>
            <td>
                <div class="layui-btn-group">
                    <button class="layui-btn layui-btn-xs" onclick="changeCourse('<?php echo htmlentities($course->id); ?>')">修改课程</button>
                    <button class="layui-btn layui-btn-xs layui-btn-danger"
                        onclick="delCour('<?php echo htmlentities($course->id); ?>','<?php echo htmlentities($course->coursename); ?>')">删除课程</button>
                </div>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; else: ?>
        <!-- 为空 -->
        <tr>
            <td colspan="7">暂无数据</td>
        </tr>
        <?php endif; ?>
    </tbody>
</table>
</div>
</body>

</html>
<div id="dialog" style="display:none;padding:20px;">
    <form class="layui-form" id="form">
        <input type="hidden" name="id" />
        <div class="layui-form-item">
            <span class="layui-form-label">课程名称</span>
            <div class="layui-input-block">
                <input type="text" class="layui-input" name="coursename">
            </div>
        </div>
        <div class="layui-form-item">
            <span class="layui-form-label">课程描述</span>
            <div class="layui-input-block">
                <textarea name="coursedesc" class="layui-textarea"></textarea>
            </div>
        </div>
        <div class="layui-form-item">
            <input type="hidden" name="courseimg">
            <span class="layui-form-label">课程图片</span>
            <div class="layui-input-block">
                <div class="layui-upload">
                    <button type="button" class="layui-btn" id="uploadBtn">上传图片</button>
                    <div class="layui-upload-list">
                        <img class="layui-upload-img" style="max-height:45px;" id="uploadImg">
                        <p id="uploadText"></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="layui-form-item">
            <a class="layui-btn" onclick="courseFormSubmit()">提交</a>
            <button class="layui-btn layui-btn-danger" type="reset">重置</button>
        </div>
    </form>
</div>
<script>
    //1. 找到我们弹窗的位置
    var $dialog = $("#dialog");
    var form = $dialog.find("#form")[0]; //将jquery对象转换为js对象
    var isAdd = false; //是否是添加
    function resetForm() {
        form.reset();
        $("#uploadImg").attr("src", "");
    }
    /* 新增课程 */
    function addCourse() {
        resetForm();
        //2. 调用弹窗，与修改弹窗一样，所以可以使用一个方法来封装
        formOpen("新增课程");
        isAdd = true; //这里是新增的弹窗
    }
    /* 修改课程 */
    function changeCourse(courseid) {
        resetForm();
        $.post("api/courseById", { id: courseid }, function (res) {
            if (res.status === 200) {
                var checkCourse = res.data; //是讲json的字符串转化为json对象
                console.log(checkCourse);
                form.coursename.value = checkCourse.coursename;
                form.coursedesc.value = checkCourse.coursedesc;
                form.courseimg.value = checkCourse.courseimg;
                $("#uploadImg").attr("src", "/storage/" + checkCourse.courseimg);
                form.id.value = checkCourse.id;
                formOpen("修改课程");
                isAdd = false; //这里是修改的弹窗
            }
        })

    }

    function formOpen(title) {
        layer.open({
            title: title,
            type: 1,
            area: ["500px", "420px"],
            content: $dialog
        })
    }
    /* 文件上传 */
    function courseFormSubmit() {
        var formData = {
            coursename: form.coursename.value,
            coursedesc: form.coursedesc.value,
            courseimg: form.courseimg.value
        };
        var postUrl = isAdd ? "api/addCourse" : "api/changeCourse";
        if (!isAdd) formData.id = form.id.value;
        $.post(postUrl, formData, function (res) {
            layer.msg(res.msg, {
                shift: -1,
                time: 800
            }, function () {
                if (res.status == 200) {
                    location.reload();
                }
            })
        });
    }
    var uploadInst = layui.upload.render({
        elem: '#uploadBtn',
        url: '/admin.php/api/upload' //改成您自己的上传接口
        ,
        before: function (obj) {
            //预读本地文件示例，不支持ie8
            obj.preview(function (index, file, result) {
                $('#uploadImg').attr('src', result); //图片链接（base64）
            });

            layer.msg('上传中', {
                icon: 16,
                time: 0
            });
        },
        done: function (res) {
            //如果上传失败
            if (res.status != 200) {
                return layer.msg('上传失败');
            }
            layer.msg('上传成功', {
                icon: 1
            });
            //   ...成功之后的事情
            form.courseimg.value = res.msg;
        }
    });

    /* 查询搜索 */
    function search() {
        var searchInput = document.getElementById('searchInput');
        var searchValue = searchInput.value;
        location.href = "?search=" + searchValue;
    }

    /* 实现删除课程功能 */
    function delCour(courseid, coursename) {
        layer.confirm("你确定要删除" + coursename + "的课程", function (index) {
            $.post("api/delCour", {
                "courseid": courseid
            }, function (res) {
                layer.msg(res.msg, {
                    shift: -1,
                    time: 800
                }, function () {
                    if (res.status == 200) {
                        layer.close(index);
                        location.reload();
                    }
                })
            })
        });
    }
</script>