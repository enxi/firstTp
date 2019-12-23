<?php
namespace app\common\model;
use think\Model;
class User extends Model
{
    public function add($data) {
    	$res = $this->save($data);
    	return $this->id;
   	}

   	public function upDateById ($data, $id) {
   		$this->save($data, $id);
   	}
}


/*
进入到mysql->bin目录
执行 mysql -plocalhost -uroot -p 按回车键，输入密码
show databases;//显示所有数据库
use ***;//选用某一个数据库
show tables;//显示该数据库下的所有表
describe table1;//查看表table1的结构
select * from table1;//查询表table1

navicat链接服务器上数据库，导出db格式，直接在自己数据库导入即可。
新建表：
CREATE TABLE student(
	`id` int(11) unsigned NOT NULL auto_increment,
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`grade` int(11) unsigned NOT NULL default 1,
	`class` int(11) unsigned NOT NULL default 1,
	`provinceId` int(11) unsigned NOT NULL DEFAULT 0,
	`cityId` int(11) unsigned NOT NULL DEFAULT 0,
	`create_time` int(11) unsigned NOT NULL default 0,
	`update_time` int(11) unsigned NOT NULL default 0,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB auto_increment=1 DEFAULT CHARSET=utf8;

ALTER table student add column districtId VARCHAR(32) not null after cityId;在student中在cityId后面增加districtId列

ALTER table student drop column districtId; 删除student表中的districtId字段

ALTER TABLE student CHANGE districtId districtText VARCHAR(32);将student表中districtId改为districtText，并设置类型为varchar
*/