//登录验证
function check(){
   var username = $.trim($('#username').val());
   if(username == ''){
	  layer.msg('请输入用户名');
	  $('#username').focus();
	  return false;
   }	   
   var password = $.trim($('#password').val());
   if(password == ''){
	  layer.msg('请输入密码');
	  $('#password').focus();
	  return false;
   }
   if($("#iQapTcha").val() == ''){
	  layer.msg('请滑动滑块！');
	  return false;
   }	   		   
   $.ajax({
		type:"POST",
		url :$('#login_form').attr('action'),
		data:$('#login_form').serialize(),
		success: function(res){
			if(res.status){
				layer.msg('登录成功！',{time:1000},function(){
					parent.location.href = parent.location.href;
				});
			}else{
				layer.msg(res.msg);
			}
		}
	});		   
}