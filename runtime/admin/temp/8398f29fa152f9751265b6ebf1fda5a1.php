<?php /*a:2:{s:53:"D:\phpstudypro\WWW\tp\app\admin\view\login\index.html";i:1622516528;s:55:"D:\phpstudypro\WWW\tp\app\admin\view\common\import.html";i:1621911790;}*/ ?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css">
<script src="/static/js/jquery.js"></script>
<script src="/static/layui/layui.js"></script>
    <style>
        body {
            background-color: rgb(119, 143, 167);
        }

        .form-box {
            transform: translate(-50%, -50%);
            position: fixed;
            top: 50%;
            left: 50%;
            width: 500px;
            padding: 50px;
            background-color: rgba(211, 218, 221, 0.8);
        }

        .titile {
            font-size: 25px;
            padding-bottom: 20px;
            font-weight: bold;
            text-align: center;
        }

        .code img {
            height: 38px;
            width: auto;
            display: block;
        }
    </style>
</head>

<body>
    <div class="form-box">
        <h3 class="titile">编程学习网后台管理页面</h3>
        <form action="form" method="POST" class="layui-form" id="form">
            <div class="layui-form-item">
                <label for="" class="layui-form-label">账户名称</label>
                <div class="layui-input-block">
                    <input type="text" class="layui-input" name="name">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">密码</label>
                <div class="layui-input-block">
                    <input type="password" class="layui-input" name="pwd">
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label">验证码</label>
                <div class="layui-input-inline">
                    <input type="text" class="layui-input" name="code">
                </div>
                <div class="code"><?php echo captcha_img();; ?></div>
            </div>
            <div class="layui-form-item" style="text-align: center;">
                <button class="layui-btn" type="button" onclick="formSubmit();">登陆</button>
            </div>
        </form>
    </div>
    <script>
        function formSubmit() {
            var form = document.getElementById('form');
            var formData = {
                "name": form.name.value,
                "pwd": form.pwd.value,
                "code": form.code.value
            };
            $.post('login', formData, function (res) {
                console.log(res);
                $(".code img").click();
                form.code.value = "";
                layer.msg(res.msg);//使用layui输出错误结果
                if (res.status == 200) {
                    setTimeout(function () {
                        location.href = "index.html";
                    }, 1000);
                }
            });
        }
    </script>
</body>

</html>