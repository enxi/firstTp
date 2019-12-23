(function() {
	//展示默认数据
	$.ajax({
		type: "get",
		url: "getAllStudent",
		success: function(data) {
			showData(data);
		},
		error : function() {
			alert("列表数据请求报错，请重试！");
		}		
	});

	//分页
	$(".pages").on("click", "a", function() {
        $.ajax({
        	type: "get",
            url: $(this).attr('href'),
            success:function(data) {
            	showData(data);
            }
        });
        return false; //这里就是阻止a标签默认跳转的行为
	});

	function showData(data) {
		if(data.status == 200) {
			//学生数据展示
			var students = data.data.list;
				studentHtml = '';
			for(var i = 0;i < students.length;i++) {
				studentHtml += '<ul class="stu-content-item cf" id='+students[i].id+'>'+
								'<li class="stu-content-name">'+students[i].name+'</li>'+
								'<li class="stu-content-grade-class">'+students[i].grade+'年'+students[i].class+'班</li>'+
								'<li class="stu-content-area">'+students[i].provinceText+'-'+students[i].cityText+'-'+students[i].districtText+'</li>'+
								'<li>'+
									'<img src="/static/admin/image/delete.png" alt="删除" class="delete-item" />'+
									'<img src="/static/admin/image/modify.png" alt="修改" class="edit-item" />'+
								'</li>'+
							'</ul>';
			}
			$(".stu-content").empty().append(studentHtml);
			//分页
			var pageHtml = data.data.page;
			$(".pages").empty().html(pageHtml);
		}if(data.status == 300) {
			alert("暂无数据！");
		}
	}

	//通过‘省份/城市/区县’筛选数据
	$(".filter-wrapper").on("change", "select", function() {
		var proName = $(this).find("option:checked").text();
		$.ajax({
			type: "get",
			url: "searchArea",
			data: 'area='+proName+"&type="+$(this).attr("class"),
			success: function(data) {
				showData(data);
			},
			error : function() {
				alert("数据请求报错，请重试！");
			}		
		});
	});

	//输入关键词搜索
	$(".searchBtn").on("click", function() {
		var searchName = $(".searchName").val();
		if(!searchName) {
			alert("请输入学生姓名进行搜索！");
			return false;
		}

		$.ajax({
			type: "get",
			url: "searchFn",
			data: 'searchName='+searchName,
			success: function(data) {
				showData(data);
			},
			error : function() {
				alert("搜索请求报错，请重试！");
			}		
		});
	});

	//删除
	$(document).on("click", ".delete-item", function() {
		var relDel = confirm("您确定要删除该学生吗？");
		if(relDel) {
			var deleteId = $(this).closest(".stu-content-item").attr("id");
			$.ajax({
				type: "post",
				url: "delStudent",
				data: 'deleteId='+deleteId,
				success: function(data) {
					if(data.status == 200) {
						alert(data.message);
						location.href = "/admin/index/index";
					}else {
						alert(data.message);
					}
				},
				error : function() {
					alert("删除报错，请重试！");
				}		
			});
		}
	});

	//获取全部省份信息
	$.ajax({
		type: "get",
		url: "/api/city/getAllProvince",
		success: function(data) {
			if(data.status == 200) {
				var provRes = data.data,
					provResHtml = '';
				//展示省份数据
				for(var i = 0;i < provRes.length;i++) {
					provResHtml += '<option value='+provRes[i].id+'>'+provRes[i].name+'</option>'
				}
				$(".province").empty().append(provResHtml);
				
			}else {
				alert("省份数据请求失败！");
			}
		},
		error : function() {
			alert("省份数据请求报错，请重试！");
		}		
	});

	//根据省份id获取对应二级城市数据
	$(".province").on("change", function() {
		var provinceId = $(this).val();
		$.ajax({
			type: "get",
			url: "/api/city/getAllCity",
			data: "provinceId="+provinceId,
			success: function(data) {
				if(data.status == 200) {
					var cityRes = data.data,
						cityResHtml = '<option value=0>选择城市</option>';
					//展示城市数据
					for(var i = 0;i < cityRes.length;i++) {
						cityResHtml += '<option value='+cityRes[i].id+'>'+cityRes[i].name+'</option>'
					}
					$(".city").empty().append(cityResHtml);
					//区县清空
					$(".district").empty();
				}else {
					alert("省份数据请求失败！");
				}
			},
			error : function() {
				alert("城市数据请求报错，请重试！");
			}		
		});
	});

	//根据城市id获取三级区县数据
	$(".city").on("change", function() {
		var cityId = $(this).val();
		$.ajax({
			type: "get",
			url: "/api/city/getAllDistrict",
			data: "cityId="+cityId,
			success: function(data) {
				if(data.status == 200) {
					var districtRes = data.data,
						districtResHtml = '<option value=0>选择区县</option>';
					//展示省份数据
					for(var i = 0;i < districtRes.length;i++) {
						districtResHtml += '<option value='+districtRes[i].id+'>'+districtRes[i].name+'</option>'
					}
					$(".district").empty().append(districtResHtml);
				}else {
					alert("省份数据请求失败！");
				}
			},
			error : function() {
				alert("城市数据请求报错，请重试！");
			}		
		});
	});

	//添加数据
	var editId = '';
	$(".addStudent").on("click", function() {
		$(".add-dialog").show();
		$(".add-title").text("添加");
		$('input[name="stuName"]').val('');
		editId = '';
	});

	//编辑
	$(document).on("click", ".edit-item", function() {
		var editItem = $(this).closest(".stu-content-item");
		editId = $(this).closest(".stu-content-item").attr("id");
		$(".add-dialog").show();
		$(".add-title").text("编辑");
		$('input[name="stuName"]').val(editItem.find(".stu-content-name").text());
		//后面内容省略填充
	});

	$(".add-submit").on("click", function() {
		var name = $('input[name="stuName"]').val(),
			grade = $('input[name="grade"]').val(),
			className = $('input[name="class"]').val(),
			provinceId = $('.add-province').val(),
			provinceText = $('.add-province').find("option:selected").text(),
			cityId = $('.add-city').val(),
			cityText = $('.add-city').find("option:selected").text(),
			districtId = $('.add-district').val(),
			districtText = $('.add-district').find("option:selected").text();
		var paramData = {
			'name': name,
			'grade': grade,
			'class': className,
			'provinceText': provinceText,
			'cityText': cityText,
			'districtText': districtText
		}
		//编辑情况有editId，添加情况没有
		if(editId) {
			paramData.editId = editId;
		}
		$.ajax({
			type: "post",
			url: "saveStudent",
			data: paramData,
			success: function(data) {
				if(data.status == 200) {
					alert(data.message);
					location.href="/admin/index/index";
				}else {
					alert(data.message);
				}
			},
			error : function() {
				alert("请求报错，请重试！");
			}		
		});
	});

	$(".add-close").on("click", function() {
		$(".add-dialog").hide();
	});

	//弹出框内——获取全部省份信息
	$.ajax({
		type: "get",
		url: "/api/city/getAllProvince",
		success: function(data) {
			if(data.status == 200) {
				var provRes = data.data,
					provResHtml = '';
				//展示省份数据
				for(var i = 0;i < provRes.length;i++) {
					provResHtml += '<option value='+provRes[i].id+'>'+provRes[i].name+'</option>'
				}
				$(".add-province").empty().append(provResHtml);
				
			}else {
				alert("省份数据请求失败！");
			}
		},
		error : function() {
			alert("省份数据请求报错，请重试！");
		}		
	});

	//弹出框内——根据省份id获取对应二级城市数据
	$(".add-province").on("change", function() {
		var provinceId = $(this).val();
		$.ajax({
			type: "get",
			url: "/api/city/getAllCity",
			data: "provinceId="+provinceId,
			success: function(data) {
				if(data.status == 200) {
					var cityRes = data.data,
						cityResHtml = '<option value=0>选择城市</option>';
					//展示城市数据
					for(var i = 0;i < cityRes.length;i++) {
						cityResHtml += '<option value='+cityRes[i].id+'>'+cityRes[i].name+'</option>'
					}
					$(".add-city").empty().append(cityResHtml);
					//区县清空
					$(".add-district").empty();
				}else {
					alert("省份数据请求失败！");
				}
			},
			error : function() {
				alert("城市数据请求报错，请重试！");
			}		
		});
	});

	//弹出框内——根据城市id获取三级区县数据
	$(".add-city").on("change", function() {
		var cityId = $(this).val();
		$.ajax({
			type: "get",
			url: "/api/city/getAllDistrict",
			data: "cityId="+cityId,
			success: function(data) {
				if(data.status == 200) {
					var districtRes = data.data,
						districtResHtml = '<option value=0>选择区县</option>';
					//展示省份数据
					for(var i = 0;i < districtRes.length;i++) {
						districtResHtml += '<option value='+districtRes[i].id+'>'+districtRes[i].name+'</option>'
					}
					$(".add-district").empty().append(districtResHtml);
				}else {
					alert("省份数据请求失败！");
				}
			},
			error : function() {
				alert("城市数据请求报错，请重试！");
			}		
		});
	});

	//获取用户信息头部展示
	$.ajax({
		type: "post",
		url: "getUserInfo",
		success: function(data) {
			$(".name").html("欢迎您："+data.user.name);
			$(".avator").attr("src", data.user.avator);
		},
		error : function() {
			alert("数据请求报错，请重试！");
		}		
	});

	//退出功能
	$(".logout").on("click", function() {
		$.ajax({
			type: "post",
			url: "/admin/user/logout",
			success: function(data) {
				if(data.status == 200) {
					alert(data.message);
					location.href="/admin/user/login";
				}
			},
			error : function() {
				alert("数据请求报错，请重试！");
			}		
		});
	});
}())