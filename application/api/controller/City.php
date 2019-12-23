<?php
namespace app\api\controller;
use think\Controller;
class City extends Controller
{	
	//获取全部省份数据
	public function getAllProvince() {
		$province = model('Cities')->getAllProvince();
		if(!$province) {
			$result = [
		        'status' => 300,
		        'data' => []
		    ];
		}
		$result = [
	        'status' => 200,
	        'data' => $province
	    ];
	    return $result;

		/*
		//同上述自己组装数据一样，可以通过怕print_r($province);在页面直接看www.tp5.com/api/city/getAllProvince
		if(!$province) {
			$this->result($province, 0, 'error');
		}
		$this->result($province, 1, 'success');
		*/
	}

	//根据省份Id获取当下城市二级数据
	public function getAllCity() {
		$provinceId = input('get.provinceId');
		$city = model('Cities')->getCitiesByProvinceId($provinceId);
		if(!$city) {
			$result = [
		        'status' => 300,
		        'data' => []
		    ];
		}
		$result = [
	        'status' => 200,
	        'data' => $city
	    ];
	    return $result;
	}

	//根据城市Id获取三级区县数据
	public function getAllDistrict() {
		$cityId = input('get.cityId');
		$district = model('Cities')->getDistrictByCityId($cityId);
		if(!$district) {
			$result = [
		        'status' => 300,
		        'data' => []
		    ];
		}
		$result = [
	        'status' => 200,
	        'data' => $district
	    ];
	    return $result;
	}
}