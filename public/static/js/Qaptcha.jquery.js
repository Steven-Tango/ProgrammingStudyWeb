jQuery.QapTcha = {
    build: function(options) {
        var defaults = {
            txtLock: '请按住滑块，拖动到最右侧',//未解锁文字
            txtUnlock: '验证通过',//解锁成功文字
            disabledSubmit: false,//未解锁提交按钮无效
            autoRevert: true,//是否自动回弹
            PHPfile: qurl,//验证服务器
            autoSubmit: false//是否自动提交
        };
        if (this.length > 0) return jQuery(this).each(function(i) {
            var opts = $.extend(defaults, options),
                t = $(this),
                form = $('form').has(t),
                Clr = jQuery('<div>', {
                    'class': 'clr'
                }),
                bgSlider = jQuery('<div>', {
                    'class': 'bgSlider'
                }),
                bgMasks = jQuery('<div>', {
                    'class': 'bgMasks'
                }),
                Slider = jQuery('<div>', {
                    'class': 'Slider'
                }),
                Icons = jQuery('<div>', {
                    id: 'Icons'
                }),
                TxtStatus = jQuery('<div>', {
                    'class': ' TxtStatus dropError TxtStatusFont',
                    text: opts.txtLock
                }),
            inputQapTcha = jQuery('<input>',{name:'iQapTcha',value:'',type:'hidden',id:'iQapTcha'});

            if (opts.disabledSubmit) form.find('input[type=\'submit\']').attr('disabled', 'disabled');
            bgSlider.appendTo(t);//添加验证背景
            Icons.insertAfter(bgSlider);
            Clr.insertAfter(Icons);
            Slider.appendTo(bgSlider);//添加滑块
            bgMasks.appendTo(bgSlider);//添加上层遮罩
            TxtStatus.appendTo(bgSlider);//添加文字
            inputQapTcha.appendTo(t);
            t.show();
            generatePass();
            Slider.draggable({//滑块添加滑动
                drag: function(event, ui) {//拖动回调
                    $(bgMasks).width(bgSlider.width() - ui.position.left);
                },
                revert: function(event, ui) {//验证回弹
                    if (opts.autoRevert) {
                        if (parseInt(Slider.css("left")) > (bgSlider.width() - Slider.width() - 3)) return false;
                        else return true
                    }
                },
                revertDuration: 15,
                containment: bgSlider,
                axis: 'x',//横向
                stop: function(event, ui) {//推动结束回调
                    if (ui.position.left > (bgSlider.width() - Slider.width() - 3)) {
                        $.ajax({//拖动到头给服务器发消息
                            type: "post",    // 请求方式
                            async: "async",  // 异步请求
                            data: {          // 传递数据
                                action: 'qaptcha',
                            },
                            url: opts.PHPfile, // 提交的URL
                            dataType: "json",  // 数据类型
                            success: function(data) { // 成功响应后调用函数
                                if (!data.error) {  // 成功响应
                                    Slider.draggable('disable').css('cursor','default'); // 设置可滑动CSS样式
                                    inputQapTcha.val(data.val); // 为滑动条赋值
                                    TxtStatus.text(opts.txtUnlock).addClass('dropSuccess').removeClass('dropError'); // 添加滑动成功的CSS样式，移除滑动失败CSS样式
                                    Slider.addClass('SliderSuccess'); // 添加滑动成功CSS样式
                                    Icons.css('background-position', '-16px 0'); // 设置图标CSS样式
                                    $('#iQapTcha-error').remove(); // 移除滑动失败内容
//                                    form.find('input[type=\'submit\']').removeAttr('disabled');
                                }
                            }
                        });
                    }else {
                        while (bgMasks.width() <= bgSlider.width()) {
                            $(bgMasks).width(bgMasks.width() + Slider.width())
                        }
                        $(bgMasks).width(bgMasks.width() - Slider.width())
                    }
                }
            });
            function generatePass() {
                var chars = 'azertyupqsdfghjkmwxcvbn23456789AZERTYUPQSDFGHJKMWXCVBN';
                var pass = '';
                for(i=0;i<10;i++){
                    var wpos = Math.round(Math.random()*chars.length);
                    pass += chars.substring(wpos,wpos+1);
                }
               $.ajax({ // 生成时，将值存入session
                   type: "post",
                   async: "async",
                   data: {
                       pass: pass
                   },
                   url: setQapTchaurl,
               });
                return pass;
            }
        })
    }
};
jQuery.fn.QapTcha = jQuery.QapTcha.build;

function refurbishValidate(ctrl) {//重置表单
    ctrl.html("");
    ctrl.QapTcha({
        disabledSubmit: true,
        autoSubmit: false,
        autoRevert: true,
        txtLock: "请按住滑块，拖动到最右侧",
        txtUnlock: '验证通过',
        PHPfile:qurl
    })
}

