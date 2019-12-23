CREATE TABLE `user`(
	`id` int(11) unsigned NOT NULL auto_increment,
	`name` VARCHAR(50) NOT NULL DEFAULT '',
	`tel` VARCHAR(20) NOT NULL DEFAULT '',
	`email` VARCHAR(30) NOT NULL DEFAULT '',
	`psd` CHAR(32) NOT NULL DEFAULT '',
	`code` VARCHAR(10) NOT NULL DEFAULT '',
	`create_time` int(11) unsigned NOT NULL default 0,
	PRIMARY KEY (`id`)
)ENGINE=InnoDB auto_increment=1 DEFAULT CHARSET=utf8;