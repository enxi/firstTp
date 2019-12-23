<?php
namespace app\common\model;
use think\Model;
class Cities extends Model
{	
	//获取全部省份信息
    public function getAllProvince() {
    	$data = [
    		'level' => 1
    	];
    	$order = [
    		'id' => 'asc' //desc为倒序
    	];
    	$province = $this->where($data)
			    		 ->order($order)
			    		 ->select();
		  return $province;
   	}

   	//根据省份Id获取当下城市二级数据
   	public function getCitiesByProvinceId ($provinceId) {
   		$data = [
   			'parentid_id' => $provinceId,
   			'level' => 2
   		];
   		$order = [
    		'id' => 'asc' 
    	];
    	$cities = $this->where($data)->order($order)->select();
    	return $cities;
   	}

   	//根据城市Id获取三级区县数据
   	public function getDistrictByCityId ($cityId) {
   		$data = [
   			'parentid_id' => $cityId,
   			'level' => 3
   		];
   		$order = [
    		'id' => 'asc' 
    	];
    	$cities = $this->where($data)->order($order)->select();
    	return $cities;
   	}
}
