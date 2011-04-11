<?php

// 定义当前目录
define("APP_PATH",dirname(__FILE__));

define("DEFAULT_DB",'app_'.$_SERVER['HTTP_APPNAME']);  //默认的数据库
define("DEFAULT_CHARTSET","utf8"); //默认的数据库连接编码

define("CookieDomain",""); //cookie域
define("CookiePre","redchess"); //cookie键前缀

$db_port=3307;
//主库的host
$db_host="w.rdc.sae.sina.com.cn";
/**
 * 如果连从库，则：
 * $db_host="r.rdc.sae.sina.com.cn";
 */
$db_name='app_'.$_SERVER['HTTP_APPNAME'];
$db_password=SAE_SECRETKEY;
$db_user=SAE_ACCESSKEY;
$link=mysql_connect("$db_host:$db_port",$db_user,$db_password);
if($link)
{
    mysql_select_db($db_name,$link);
    //your code goes here
}

$DBCONFIG=array(
	array("dbhost"=>'w.rdc.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT'],"dbuser"=>SAE_ACCESSKEY,"dbpwd"=>SAE_SECRETKEY),//此为该组数据服务器的主服务器（Master/Slave 结构里的Master）
	array("dbhost"=>'r.rdc.sae.sina.com.cn:'.$_SERVER['HTTP_MYSQLPORT'],"dbuser"=>SAE_ACCESSKEY,"dbpwd"=>SAE_SECRETKEY)//此为slave服务器，可以有多个进行读负载均衡
);
$DBPREDEFINED=array(
	'ppt_'=>"passport",
	'red_'=>'redchess'
);
$DBNAME='app_'.$_SERVER['HTTP_APPNAME'];

$apiConfig=array(
	"tsina"=>array(
		"name"=>'新浪微博',
		"access_key" =>'4106323544',
		"screct_key"=>'fdea0fd0626378d951a366e00c5444d7'
	)
);


$currTemplate='default'; //定义采用哪个模版

?>