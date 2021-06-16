<?php /*a:2:{s:37:"E:\tp\app\admin\view\login\index.html";i:1623768399;s:39:"E:\tp\app\admin\view\common\import.html";i:1623768399;}*/ ?>
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
            background-color: rgba(208, 225, 233, 0.8);
            box-shadow: 0 0 10px rgb(1, 2, 2);
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
    <canvas id="canvas"></canvas>
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

        /* 实现背景动画 */
        // 可调参数
        var BACKGROUND_COLOR = "rgba(0,43,54,1)";   // 背景颜色
        var POINT_NUM = 100;                        // 星星数目
        var POINT_COLOR = "rgba(255,255,255,0.7)";  // 点的颜色
        var LINE_LENGTH = 10000;                    // 点之间连线长度(的平方)

        // 创建背景画布
        var cvs = document.createElement("canvas");
        cvs.width = window.innerWidth;
        cvs.height = window.innerHeight;
        cvs.style.cssText = "\
    position:fixed;\
    top:0px;\
    left:0px;\
    z-index:-1;\
    opacity:1.0;\
    ";
        document.body.appendChild(cvs);

        var ctx = cvs.getContext("2d");

        var startTime = new Date().getTime();

        //随机数函数
        function randomInt(min, max) {
            return Math.floor((max - min + 1) * Math.random() + min);
        }

        function randomFloat(min, max) {
            return (max - min) * Math.random() + min;
        }

        //构造点类
        function Point() {
            this.x = randomFloat(0, cvs.width);
            this.y = randomFloat(0, cvs.height);

            var speed = randomFloat(0.3, 1.4);
            var angle = randomFloat(0, 2 * Math.PI);

            this.dx = Math.sin(angle) * speed;
            this.dy = Math.cos(angle) * speed;

            this.r = 1.2;

            this.color = POINT_COLOR;
        }

        Point.prototype.move = function () {
            this.x += this.dx;
            if (this.x < 0) {
                this.x = 0;
                this.dx = -this.dx;
            } else if (this.x > cvs.width) {
                this.x = cvs.width;
                this.dx = -this.dx;
            }
            this.y += this.dy;
            if (this.y < 0) {
                this.y = 0;
                this.dy = -this.dy;
            } else if (this.y > cvs.height) {
                this.y = cvs.height;
                this.dy = -this.dy;
            }
        }

        Point.prototype.draw = function () {
            ctx.fillStyle = this.color;
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.r, 0, Math.PI * 2);
            ctx.closePath();
            ctx.fill();
        }

        var points = [];

        function initPoints(num) {
            for (var i = 0; i < num; ++i) {
                points.push(new Point());
            }
        }

        var p0 = new Point(); //鼠标
        p0.dx = p0.dy = 0;
        var degree = 2.5;
        document.onmousemove = function (ev) {
            p0.x = ev.clientX;
            p0.y = ev.clientY;
        }
        document.onmousedown = function (ev) {
            degree = 5.0;
            p0.x = ev.clientX;
            p0.y = ev.clientY;
        }
        document.onmouseup = function (ev) {
            degree = 2.5;
            p0.x = ev.clientX;
            p0.y = ev.clientY;
        }
        window.onmouseout = function () {
            p0.x = null;
            p0.y = null;
        }

        function drawLine(p1, p2, deg) {
            var dx = p1.x - p2.x;
            var dy = p1.y - p2.y;
            var dis2 = dx * dx + dy * dy;
            if (dis2 < 2 * LINE_LENGTH) {
                if (dis2 > LINE_LENGTH) {
                    if (p1 === p0) {
                        p2.x += dx * 0.03;
                        p2.y += dy * 0.03;
                    } else return;
                }
                var t = (1.05 - dis2 / LINE_LENGTH) * 0.2 * deg;
                ctx.strokeStyle = "rgba(255,255,255," + t + ")";
                ctx.beginPath();
                ctx.lineWidth = 1.5;
                ctx.moveTo(p1.x, p1.y);
                ctx.lineTo(p2.x, p2.y);
                ctx.closePath();
                ctx.stroke();
            }
            return;
        }

        //绘制每一帧
        function drawFrame() {
            cvs.width = window.innerWidth;
            cvs.height = window.innerHeight;
            ctx.fillStyle = BACKGROUND_COLOR;
            ctx.fillRect(0, 0, cvs.width, cvs.height);

            var arr = (p0.x == null ? points : [p0].concat(points));
            for (var i = 0; i < arr.length; ++i) {
                for (var j = i + 1; j < arr.length; ++j) {
                    drawLine(arr[i], arr[j], 1.0);
                }
                arr[i].draw();
                arr[i].move();
            }

            window.requestAnimationFrame(drawFrame);
        }

        initPoints(POINT_NUM);
        drawFrame();
    </script>
</body>

</html>