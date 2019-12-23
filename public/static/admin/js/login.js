(function() {
	$("button").on("click", function() {
		var param = {
			'tel': $("input[name='tel']").val(),
			'psd': $("input[name='psd']").val()
		}
		$.ajax({
			type: "post",
			url: "login",
			data: param,
			success: function(data) {
				if(data.status == 200) {
					alert("登录成功！");
					location.href="/admin/index/index";
				}else {
					alert(data.message);
				}
			},
			error : function() {
				alert("数据请求报错，请重试！")
			}		
		});
	});
}())