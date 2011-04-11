<?php

class account
{
	function index(){ // 这里是首页
		$this->display("index/main_index.html");
	}

	function tologin(){
		$lfrom=rq("lfrom","tsina");
		$callbackurl=getbase().'index.php?c=account&a=callback&lfrom=tsina';
		
		$api="openapi_".$lfrom;
		import($api);
		$api=new $api();	
		$tourl=$api->getLoginUrl($callbackurl);
		header("Location: $tourl");
	}
	
	function callback(){
		$lfrom=rq("lfrom","tsina");
		
		$api="openapi_".$lfrom;
		import($api);
		$api=new $api();	



		$uidarr=$api->callback();
		if(!$uidarr){ //登录失败
			$tourl="/err_login.html";
			header("Location: $tourl");exit;
		}else{
			$ppt=new ppt();
			$uid=$ppt->login($uidarr);
					
			$userinfo=array(
				"lfrom"=>$lfrom,
				"lfromuid"=>$uidarr['lfromuid'],
				"name"=>$uidarr["name"],
				"uid"=>$uid
			);
			envhelper::saveToken(envhelper::packKUID($lfrom,$userinfo['lfromuid']),$userinfo);
			
			
			$tourl="?c=my";
			header("Location: $tourl");
		}
	}
	
}	
