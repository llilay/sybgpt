<?php
/**
 *
 * 功能说明：数据库配置文件。
 *
 **/
return array(
	'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => '192.168.0.50', // 服务器地址
    'DB_NAME'   => 'sybgpt', // 数据库名
    'DB_USER'   => 'root', // 用户名
    'DB_PWD'    => 'root', // 密码
    'DB_PORT'   => 3306, // 端口
    //'DB_PREFIX' => 't_', // 数据库表前缀
    'DB_CHARSET'=>  'utf8', // 数据库编码默认采用utf8
    //'DB_PARAMS' => array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL)//不区分大小写CASE_NATURAL
    );