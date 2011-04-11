<?php

require_once(dirname(__FILE__).'/../config.php');
// 定义框架目录
define("TP_PATH",APP_PATH."/templates");
// 默认时区设置
@date_default_timezone_set('PRC');

require(APP_PATH.'/inc/function.php');

require_once(APP_PATH.'/smarty/Smarty.class.php');

class light extends Smarty {

   function __construct()
   {
        $this->Smarty();
		
        $this->template_dir = TP_PATH.'/'.getTemplatePath();
        $this->compile_dir  =  APP_PATH.'/templates_c';
        $this->config_dir   = APP_PATH.'/configs/';
        $this->cache_dir    = APP_PATH.'/cache/';

        $this->caching = true;
        $this->assign('app_name', 'Guest Book');
   }
   
   public function set($k,$v){
	  $this->assign($k,$v);
   }
}

?>
