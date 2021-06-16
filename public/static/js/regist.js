//注册验证
function check(){
   var username = $.trim($('#username').val());
   if(username == ''){
	  layer.msg('请输入用户名');
	  $('#username').focus();
	  return false;
   }	   
   if(!username.match(/^[\u4E00-\u9FA5a-zA-Z0-9_]{3,16}$/)) { 
	  layer.msg('用户名只能是中英文、数字和下划线,长度3-16位');
	  $("#username").focus();
	  return false;
   }
   var password = $.trim($('#password').val());
   if(password == ''){
	  layer.msg('请输入密码');
	  $('#password').focus();
	  return false;
   }	   
   if(!password.match(/^[a-zA-Z0-9_]{6,32}$/)) { 
	  layer.msg('密码只能是英文、数字和下划线,长度6-32位');
	  $("#password").focus();
	  return false;
   }
   var code = $.trim($('#code').val());
   if(code == ''){
	  layer.msg('请输入验证码');
	  $('#code').focus();
	  return false;
   }	   
   $.ajax({
		type:"POST",
		url :$('#reg_form').attr('action'),
		data:$('#reg_form').serialize(),
		beforeSend: function() {
			$("#submit").removeAttr("onClick");
		},
		success: function(res){
			if(res.status){
				layer.msg('注册成功！',{time:1000},function(){
					parent.location.href = parent.location.href;
				});
			}else{
				$('#imgcode').click();
				$("#submit").attr("onClick","check()");
				layer.msg(res.msg);
			}
		}
   });	   
}