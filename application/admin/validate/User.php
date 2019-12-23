<?php
	namespace app\admin\validate;
	use think\Validate;
	class User extends Validate {
	    protected  $rule = [
	        'name'      =>  'require|min:4|max:8',
	        'tel'       =>  'require|number|length:11',
	        'email'     =>  'require|email',
	        'avator'    =>  'require',
	        'psd'       =>  'require',
	        'verifyCode'=> 'require'
	    ];

	    protected $message = [
	    	'name.require'      =>    '名字不能为空哦！',
	    	'name.min'          =>    '名字至少两个字哦！',
	    	'name.max'          =>    '名字不能超过四个字符！',
	    	'tel.require'       =>    '电话不能为空！',
	    	'tel.number'        =>    '电话必须为数字！',
	    	'tel.length'        =>    '电话长度应为11位！',
	    	'tel.mobile'        =>    '电话格式不对！',
	    	'email.require'     =>    '邮箱必填！',
	    	'email.email'       =>    '邮箱格式不对！',
	    	'avator.require'    =>    '必须上传头像',
	    	'psd.require'       =>    '密码不能为空',
	    	'verifyCode.require'=>    '验证码不能为空'
	   	];

	   	protected $scene = [
	   		'register' => 'name', //调用该场景这只验证name
	   		'login'    => 'tel,psd'
	   	];
	}