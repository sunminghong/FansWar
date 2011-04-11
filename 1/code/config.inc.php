<?php
define('UC_CONNECT', 'mysql');

//define('UC_DBHOST', 'localhost');
//define('UC_DBUSER', '5d13_uc');
//define('UC_DBPW', '5d13_uc');
//define('UC_DBNAME', '5d13_uc');

define('UC_DBHOST', 'm'.$_SERVER['HTTP_MYSQLPORT'].'.mysql.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT']);		// 数据库服务器
define('UC_DBUSER', SAE_ACCESSKEY);			// 数据库用户名
define('UC_DBPW', SAE_SECRETKEY);			// 数据库密码
define('UC_DBNAME', 'app_'.$_SERVER['HTTP_APPNAME']);			// 数据库名

define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`'.UC_DBNAME.'`.uc_');  
define('UC_DBCONNECT', '0');	

define('UC_CONNECT', 'mysql');

define('UC_KEY', '9edcl1jnwma724VL0zCIcR7YSBdo7lv+FjPf55E');
define('UC_API', 'http://5d13.sinaapp.com/uc');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', '3');
define('UC_PPP', '20');


//ucexample_2.php 用到的应用程序数据库连接参数
$dbhost =UC_DBHOST;			// 数据库服务器
$dbuser =UC_DBUSER;			// 数据库用户名
$dbpw = UC_DBPW;				// 数据库密码
$dbname =UC_DBNAME;			// 数据库名
$pconnect = 0;				// 数据库持久连接 0=关闭, 1=打开
$tablepre = '`'.UC_DBNAME.'`.rec_';   		// 表名前缀, 同一数据库安装多个论坛请修改此处
$dbcharset = 'utf8';			// MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定

//同步登录 Cookie 设置
$cookiedomain = ''; 			// cookie 作用域
$cookiepath = '/';			// cookie 作用路径