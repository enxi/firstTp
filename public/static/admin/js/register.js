(function() {
	//上传头像
	$("#file_upload").uploadify({
        'buttonText'      : '图片上传',
        'fileTypeDesc'    : 'IMAGE FILE',
        'fileObjName'     : 'file',
        'swf'             : '/static/uploadify/uploadify.swf',
        'uploader'        : "upload",
        'onUploadSuccess' : function(file, data, response) {
        	if(JSON.parse(data).msg == 'success') {
        		$("#upload_org_code_img").attr("src", JSON.parse(data).data).show();
        		$("#file_upload_image").val(JSON.parse(data).data);
        	}
        },
        'onUploadError' : function(file, errorCode, errorMsg, errorString) {
        	alert('请求出错，请重试！');
        }
    });

    //注册
	$("button").on("click", function() {
		var uname = $("input[name='name']").val(),
			tel = $("input[name='tel']").val(),
			email = $("input[name='email']").val(),
			psd = $("input[name='psd']").val(),
			verifyCode = $("input[name='verifyCode']").val(),
			file_upload_image = $("#file_upload_image").val();
		var param = {
			'name': uname,
			'tel': tel,
			'email': email,
			'psd': psd,
			'verifyCode': verifyCode,
			'avator': file_upload_image
		}
		$.ajax({
			type: "post",
			url: "register",
			data: param,
			success: function(data) {
				if(data.status == 200) {
					alert("添加成功！");
					location.href = "login";
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