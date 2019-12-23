<?php
namespace app\common\model;
use think\Model;
class Student extends Model
{	
	//添加一条学生数据
    public function saveStudentData ($stuData) {
    	$stuData['create_time'] = time();
    	$this->save($stuData);
    	return $this->id;
    }

    //获取全部学生数据
    public function getAllStudent() {
    	//return $this->select();
    	//return $this->paginate(5);
    	$list = $this->paginate(5)->toArray()['data'];
    	$page = $this->paginate(5)->render();
    	$allData = [
    		'list' => $list,
    		'page' => $page
    	];
    	return $allData;
    }

    //根据地区搜索
    function searchAreaByName($area, $type) {
    	if($type == 'province') {
    		$paramData = ['provinceText'=>$area];
    	}else if($type == 'city') {
    		$paramData = ['cityText'=>$area];
    	}else if($type == 'district') {
    		$paramData = ['districtText'=>$area];
    	}

    	$res = $this->where($paramData)->paginate(5,false,[ 'query' => request()->param()]);
    	$list = $res->toArray()['data'];
    	$page = $res->render();
    	$allData = [
    		'list' => $list,
    		'page' => $page
    	];
    	return $allData;
    }

    //输入搜索功能
    public function searchStudentByName ($searchName) {
    	//模糊搜索
    	$data['name'] = ['like', "%".$searchName."%"];
		$res = $this->where($data)->paginate(2,false,[ 'query' => request()->param()]);
		$list = $res->toArray()['data'];
		$page = $res->render();
    	$allData = [
    		'list' => $list,
    		'page' => $page
    	];
    	return $allData;
    }

    //删除一个学生
    public function deleteStudent($delId) {
    	$data = ['id' => $delId];
    	return $this->where($data)->delete();//返回被删除的id
    }

    //更新一条学生数据
    public function updateStudentById($stuData, $editId) {
    	$stuData['update_time'] = time();
    	return $this->allowField(true)->save($stuData, ['id'=>$editId]);//返回编辑的id
    }
}
