<?php
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{	
    
    public $loginInfo;
    public function _initialize() {
        //获取用户信息
        $this->loginInfo = session("currentUser", "", 'admin'); 
        //没有登陆跳回到登陆页面
        if(!$this->loginInfo) {
            $this->redirect('/admin/user/login');
        }
    }

    //显示首页
    public function index () {
        return $this->fetch();
    }

    //获取登陆的用户信息
    public function getUserInfo () {
        return ["user" => $this->loginInfo];
    }

    //增加学生
    public function saveStudent() {
        $stuData = input("post.");
        //如果存在editId则调用更新数据方法
        if(!empty($stuData['editId'])) {
            return $this->updateStudent($stuData);
        }
        //添加
        $validate = validate('Index');
        if (!$validate->check($stuData)) {
            $result = [
                'status' => 300,
                'message' => $validate->getError()
            ];
            return $result;
        }

        $stuId = model('Student')->saveStudentData($stuData);
        return [
            "status" => 200,
            'message' => '添加成功！',
            "stuId"  => $stuId
        ];
    }

    //更新学生数据
    public function updateStudent($stuData) {
        $validate = validate('Index');
        if (!$validate->check($stuData)) {
            $result = [
                'status' => 300,
                'message' => $validate->getError()
            ];
            return $result;
        }

        $updateId = model('Student')->updateStudentById($stuData, $stuData['editId']);//返回编辑的id
        return [
            "status" => 200,
            'message' => '更新成功！',
            "updateId"  => $updateId
        ];
    }

    //全部学生数据
    public function getAllStudent () {
        $data = model('Student')->getAllStudent();
        if(!$data) {
            $result = [
                'status' => 300,
                'data' => []
            ];
        }else {
            $result = [
                'status' => 200,
                'data' => $data
            ];
        }
        return $result;
    }

    //根据地区搜索
    public function searchArea() {
        $area = input('get.area');
        $type = input('get.type');
        $data = model('Student')->searchAreaByName($area, $type);
        if(!$data) {
            $result = [
                'status' => 300,
                'data' => []
            ];
        }else {
            $result = [
                'status' => 200,
                'data' => $data
            ];
        }
        return $result;
    }

    //输入搜索功能
    public function searchFn() {
        $searchName = input('get.searchName');
        $data = model('Student')->searchStudentByName($searchName);
        if(!$data) {
            $result = [
                'status' => 300,
                'data' => []
            ];
        }else {
            $result = [
                'status' => 200,
                'data' => $data
            ];
        }
        return $result;
    }

    //删除学生
    public function delStudent() {
        $delId = input("post.deleteId");
        $res = model('Student')->deleteStudent($delId);//返回被删除的id
        if($res){
            $result = [
                'status' => 200,
                'message'=> '删除成功',
                'id' => $res
            ];
        }else {
            $result = [
                'status' => 300,
                'message'=> '删除失败'
            ];
        }
        return $result;
    }
}
