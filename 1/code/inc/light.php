<?php

require_once(dirname(__FILE__).'/../config.php');
// 定义框架目录
define("TP_PATH",APP_PATH."/templates");
define("DATADIR",APP_PATH."/data");
// 默认时区设置
@date_default_timezone_set('PRC');

require(APP_PATH.'/inc/function.php');
require(APP_PATH.'/inc/envhelper_class.php');
require(APP_PATH.'/inc/dbhelper_class.php');
require(APP_PATH.'/inc/sae_memcache_wrapper1.php');

require(APP_PATH."/inc/ppt_class.php");

require_once(APP_PATH.'/inc/template.class.php');
//require_once(APP_PATH.'/smarty/Smarty.class.php');

class light extends template {

   function __construct()
   {
		$this->template();
		$this->assign('charset', DEFAULT_CHARTSET);

		$this->defaulttpldir = TP_PATH.'/mobile';
		$this->tpldir = TP_PATH.'/default';
		$this->objdir = DATADIR.'/templates';
		$this->langfile = TP_PATH.'/mobile/templates.lang.php';
   }
   
   public function set($k,$v){
	  $this->assign($k,$v);
   }
}

function getBase(){
	return 'http://'.$_SERVER['HTTP_APPNAME'].'.sinaapp.com/';	
}

function import($libname){
	include_once(APP_PATH.'/inc/'.$libname.".php");
}

function execAction(){
	$c=$_GET['c']?$_GET['c']:'home';
	$a=$_GET['a']?$_GET['a']:'index';
	
	include(APP_PATH."/ctl/".$c.".php");
	
	$cont=new $c();
	$cont->$a();	
}

function rq($k,$default=''){
	if(isset($_GET[$k]))
		return $_GET[$k];
	else 
		return $default;	
}


function rf($k,$default=''){
	if(isset($_GET[$k]))
		return $_GET[$k];
	else 
		return $default;	
}

?>
