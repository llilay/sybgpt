<?php
return array(
    // 基本配置
    'SHOW_PAGE_TRACE' => true,
    'URL_MODEL' => 2,
    //REWRITE模式前提：httpd.conf :
	//LoadModule rewrite_module modules/mod_rewrite.so 开启
	//AllowOverride All 开启
	//.htacess 配置
	
	//微信配置参数
    'wechat_config' => array(
    	'Token' => 'K9BjKbuzTElLmvqUgbuiBRKS8i5hONsc',
        'EncodingAESKey' => 'vODEG8bKCHSxZ5CW4dVsg4t8ThRy4z1aDezIUCcuL9U',
        'HistoryNews' => 'http://mp.weixin.qq.com/s/U9J4Kl9RFvVhAsFc5wTwEA',
    ),
    // 站点设置
    'WEB_SITE' => array(
        'COMPANY' => '重庆树青科技有限公司',//公司名称
        'HTTP_KIND' => 'http',//https http
    	'WEB_TITLE' => '材便宜商城',
        'WEB_URL' => 'admin.cpy365.com',//此域名一定要在公众号中设置方可使用
        'WEB_FILE_PATH' => '/myfolder',
    ),
    'SUPER_ACCOUNT' => '15123779600',//超级管理员
    // 扩展配置文件
    'LOAD_EXT_CONFIG' => 'db,app',
    //'配置项'=>'配置值'

    //文件上传根目录
    'RESOURCES_UPLOAD_PATH' => $_SERVER['DOCUMENT_ROOT'] . '/Resources/',
    //文件保存根目录
    'RESOURCES_SAVE_PATH' => $_SERVER['DOCUMENT_ROOT'] . '/Resources/',
    //文件远程地址根目录
    'RESOURCES_URL' => '/Resources/',
);