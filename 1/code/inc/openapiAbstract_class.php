<?php 
abstract class openapiAbstract{
	public $name="新浪微博";
	public $lfrom="tsina";  //必须与类名相同，即openapi_(tsina)
	
	public function saveToken($lfromuid,$data){
		
		$json=serialize($data);//echo $json;exit;
		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie('api_'.envhelper::packKUID($this->lfrom,$lfromuid), $json,3600*24*100);
	}
	public function readToken($lfromuid){
		$json=sreadcookie('api_'.envhelper::packKUID($this->lfrom,$lfromuid));//str_replace("\\","",);
		if (!$json)
			return null;

		$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
		$session=unserialize($json);
		
		return $session;
	}
}

?>