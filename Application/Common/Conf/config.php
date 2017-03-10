<?php
return array(
	/* 数据库设置 */
    //'URL_HTML_SUFFIX'       =>  'shtml',
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'wxtp',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  'zc564265135',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'wx_',    // 数据库表前缀
    'DB_PARAMS'          	=>  array(), // 数据库连接参数
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    
    'SHOW_PAGE_TRACE' => false,

    'URL_MODEL'             =>  2,       // URL访问模式,可选参数0、1、2、3,代表以下四种模式：
	//常量配置
    //非测试
    'TOKEN'=>'weixin',
    'appid'=>'wx4265490a47b4bfd2',
    'appsecret'=>'c63edba21cb9361dc00704bfa06f0ea1',
    'crypt'=>'22XI2vwYAKV5Cwm6PFxse5tXqLN8QWqT6zgVKRK02mr',
    //测试
    //'TOKEN'=>'weixin',
    //'appid'=>'wx15d335404a6180fd',
    //'appsecret'=>'d4624c36b6795d1d99dcf0547af5443d',
    //'crypt'=>'22XI2vwYAKV5Cwm6PFxse5tXqLN8QWqT6zgVKRK02mr',

    );