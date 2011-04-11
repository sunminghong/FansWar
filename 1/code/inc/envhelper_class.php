<?php
class envhelper{
	static public function parseKUID($kuid=""){
		if(!$kuid){
			$kuid=rq("kuid","");
			if(!$kuid) return array('lfrom'=>"",'lfromuid'=>"");
		}
		$k=explode('_',$kuid);
				
		return array('lfrom'=>$k[0],'lfromuid'=>$k[1]);		
	}
		
	static public function packKUID($lfrom,$uid){
		$lfrom=strtolower($lfrom);
		if(substr($uid,0,strlen($lfrom))!=$lfrom)
			return $lfrom."_".$uid;	
		else
			return $uid;
	}
		
	static public function saveToken($uid,$userinfo){
		$json=sreadcookie('sess');
		if(!$json) $session=array();
		else{
			$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
			
			$session=unserialize($json);
			
			if (is_array($session) && isset($session[$userinfo['lfrom']."_0"])){
				unset($session[$lfrom."_0"]);	
			}
		}
		
		$userinfo['lfromname']=$GLOBALS['apiConfig'][$userinfo['lfrom']]['name'];
		
		$session[$uid]=$userinfo;
	
		$json=serialize($session);//echo $json;exit;
		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('sess', $json,3600*24*100);
	}
	
	/*
		param $uid string 包含lfrom，UID的一个组合键，由envhelper::packUID算出
	*/
	static public function readToken($uid=""){
		$json=sreadcookie('sess');//str_replace("\\","",);
		if (!$json)
			return null;

		$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
		$session=unserialize($json);

		if($uid=='')return $session;
		
		return $session[$uid];
	}

}