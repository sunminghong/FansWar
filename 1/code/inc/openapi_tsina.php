<?php
include_once('openapiAbstract_class.php');
include_once('TSinaClient_class.php');
class openapi_tsina extends openapiAbstract{

	private $oClient=null;
	private $akey;
	private $skey;
	
	function __construct(){
		$this->akey=$GLOBALS['apiConfig'][$this->lfrom]["access_key"];
		$this->skey=$GLOBALS['apiConfig'][$this->lfrom]["screct_key"];
		
		$this->name="新浪微博";
		$this->lfrom="tsina";  //必须与类名相同，即openapi_(tsina)
	}
		
	public function getLoginUrl($callbackurl){
		$o = new TSinaOAuth( $this->akey , $this->skey);
		
		$keys = $o->getRequestToken();
		$aurl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $callbackurl);
		
		$this->saveToken(0,array('uid'=>0,'tk'=>$keys['oauth_token'],'sk'=>$keys['oauth_token_secret']));
		return $aurl;
	}
		
	public function callback(){	
		$t=$this->readToken(0);

		if (!is_array($t))
			return false;
				
		$o = new TSinaOAuth( $this->akey , $this->skey , $t['tk'] , $t['sk']  );
		
		$last_key = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;
		
		//print_r($last_key);exit;
		if($last_key && $last_key['oauth_token']){
			$t=array(
				"tk"=>$last_key['oauth_token'],
				"sk"=>$last_key['oauth_token_secret']
			);
				
			$uidarr=$this->getUserInfo($t);
			$uidarr['tk']=$t['tk'];
			$uidarr['sk']=$t['sk'];
			
			/*$userinfo=array(
				"tk"=>$t['tk'],
				"sk"=>$t['sk'],
				"uid"=>$uidarr['uid'],
				"name"=>$uidarr["name"]
			);*/
			$this->saveToken($uidarr['lfromuid'],$t);
			return $uidarr;
		}
		else
			return false;
	}
	
	public function getUserInfo($tokenOrlfromuid){
		$token=$tokenOrlfromuid;
		if(!is_array($token)) $token=$this->readToken($token);
		$uidarr=$this->getClient($token)->getUserInfo();
		$lfromuid=$uidarr["id"];
		
		$uidarr["lfromuid"]=$lfromuid;
		$uidarr['lfrom']=$this->lfrom;
		$uidarr['name']=$uidarr['screen_name'];
		
		//"lfrom='".$uidarr['lfrom']."',name='". addslashes($uidarr['name'])."',lasttime=$timestamp,logintime=$timestamp,followers=".$uidarr['followers'].",followings=".$uidarr['followings'].",tweets=".$uidarr['tweets'].",ak='".$uidarr['ak']."',sk='".$uidarr['sk']."'";
		$uidarr['followers']=$uidarr['followers_count'];
		$uidarr['tweets']=$uidarr['statuses_count'];
		$uidarr['followings']=$uidarr['friends_count'];
				
		//		print_r($uidarr);exit;
		return $uidarr;
	}	
	
	public function getClient($tokenOrlfromuid){
		if ($this->oClient)	
			return $this->oClient;

		$token=$tokenOrlfromuid;
		if(!is_array($token)) $token=$this->readToken($token);
		
		if (!is_array($token))
			return false;
		$this->oClient=$c = new TSinaClient( $this->akey , $this->skey , $token['tk'] , $token['sk']);
		return $c;
	}
	
	
	
}