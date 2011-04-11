<?php
/**
 * @file			user_config.php
 * @CopyRight		(C)1996-2099 SINA Inc.
 * @Project			Xweibo
 * @Author			heli <heli1@staff.sina.com.cn>
 * @Create Date:	2010-11-16
 * @Modified By:	heli/2010-11-16
 * @Brief			用户配置文件
 */

///---------------------------------------------------------------------
/**
 * 是否开启调试状态
 * 当IS_DEBUG 为 true  时，将打开 display_errors,error_reporting(E_ALL),出错时直接显示调试信息
 * 当IS_DEBUG 为 false 时，将关闭如上选项
 */
define('IS_DEBUG',				'');
/// SERVER　环境 类型 目前只有两种 common (一般的通用环境), sae　(sina SAE　环境)
define('XWB_SERVER_ENV_TYPE',	'sae');
//----------------------------------------------------------------------
if (XWB_SERVER_ENV_TYPE!=='sae'){
	/// 产品标识串,每次安装重新生成 ,　date("mdHis") 用于重新安装时，自动更新MC前缀等
	define('APP_FLAG_VER',	'');
	/// MC　KEY　的前缀
	define('MC_PREFIX',			'XWB11_MC_'.APP_FLAG_VER);
	/// 产品安装路径
	define('W_BASE_URL_PATH',	'/');
	/// 微博 APP_KEY
	define('WB_AKEY', 			'');
	/// 微博 SECRET_KEY
	define('WB_SKEY', 			'');
	/// 官方微博功能中创建list使用的ID
	define('SYSTEM_SINA_UID',	'');
	/// 内置设置的token
	define('WB_USER_OAUTH_TOKEN',			'');
	define('WB_USER_OAUTH_TOKEN_SECRET',	'');
	
	/// 安装时的站长个人信息
	define('WB_USER_SITENAME',		'');
	define('WB_USER_SITEINFO',		'');
	define('WB_USER_NAME',			'');
	define('WB_USER_EMAIL',			'');
	define('WB_USER_QQ',			'');
	define('WB_USER_MSN',			'');
	define('WB_USER_TEL',			'');
}
//----------------------------------------------------------------------
/// SAE中的 Storage Domain　域
define('SAE_DOMAIN', 'xweibo');
/// 广告联盟是否开启
define('AD_UNION', '0');
/// 存储　XWEIO　配置文件域的KEY
define('CONFIG_DOMAIN',     'config');
//----------------------------------------------------------------------
/// 是否启用验证码，在SAE下验证码实现不完美，需关闭 设置为　０　或者 空 
define('IS_USE_CAPTCHA',	'');
//----------------------------------------------------------------------
/// HTTP		适配器选择配置
define('HTTP_ADAPTER',		'saeproxy');
/// CACHE 		适配器选择配置
define('CACHE_ADAPTER',		'sae');
/// ACCOUNT		适配器选择配置
define('ACCOUNT_ADAPTER',	'dzUcenter');
/// SMTP		适配器选择配置
define('SMTP_ADAPTER',		'smtp');
/// DB			适配器选择配置
define('DB_ADAPTER',		'mysql');
///　上传适配器
define('UPLOAD_ADAPTER',	'sae');
/// FILE		适配器选择配置
define('FILE_ADAPTER',		'saefile');
/// auth			适配器选择配置
define('AUTH_ADAPTER',		'sae');
//图片处理
define('IMAGE_ADAPTER', 	'sae');

//mail处理
define('MAIL_ADAPTER',		'sae');
/// MC 的 HOST 配置
define('MC_HOST', 			'');
//----------------------------------------------------------------------
/// DB　相关的配置
define('DB_PORT',		$_SERVER["HTTP_MYSQLPORT"]);
define('DB_HOST',		DB_PORT.".mysql.sae.sina.com.cn");
define('DB_USER',		SAE_ACCESSKEY);
define('DB_PASSWD',		SAE_SECRETKEY);
define('DB_CHARSET',	'utf8');
define('DB_PREFIX',		'xwb11_');
define('DB_NAME',		"app_".$_SERVER["HTTP_APPNAME"]);
//----------------------------------------------------------------------
/// 是否打开用户分组缓存 (目前实现尚不完美) ,设置为 false 以下用户分组缓存将被停用 , 如果想单独修改某项个缓存，请更改如下特定的缓存配置
define('CACHE_USER_ALL_ENABLE',	TRUE);
/// 我的首页缓存
define('CACHE_HOME_TIMELINE',	CACHE_USER_ALL_ENABLE ? 'u0/300' : '');
/// 我收到的评论缓存
define('CACHE_COMMENT_TO_ME',	CACHE_USER_ALL_ENABLE ? '' : '');
/// 提到我的微博缓存
define('CACHE_MENTIONS',		CACHE_USER_ALL_ENABLE ? '' : '');
/// 我的粉丝缓存
define('CACHE_FANS',			CACHE_USER_ALL_ENABLE ? '' : '');
/// 我的私信缓存
define('CACHE_MESSAGES',		CACHE_USER_ALL_ENABLE ? '' : '');
//----------------------------------------------------------------------
/// api签名认证key
define('API_KEY',			'');
/// api　过期时间
define('API_TIMESTAMP',		60 * 10);
//----------------------------------------------------------------------
/// 最大的上传大小，单位　M
define('MAX_UPLOAD_FILE_SIZE',	'1');
