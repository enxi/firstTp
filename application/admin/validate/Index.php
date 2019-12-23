<?php
	namespace app\admin\validate;
	use think\Validate;
	class Index extends Validate {
	    protected  $rule = [
	        'name'=> 'require',
			'grade'=> 'require|number',
			'class'=> 'require|number',
			'provinceText'=> 'require',
			'cityText'=> 'require',
			'districtText'=> 'require'
	    ];

	    protected $message = [
	    	'name.require'        =>    '名字不能为空哦！',
	    	'grade.require'       =>    '年级不能为空！',
	    	'grade.number'        =>    '年级必须为数字！',
	    	'class.require'       =>    '班级不能为空！',
	    	'class.number'        =>    '班级必须为数字！',
	    	'provinceText.require'  =>    '省份不能为空！',
	    	'cityText.require'      =>    '城市不能为空！',
	    	'districtText.require'  =>    '区县不能为空！'
	  	];
	}