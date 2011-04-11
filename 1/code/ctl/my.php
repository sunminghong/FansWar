<?php
class my {
	public function index(){
		$smarty=new light();
		$smarty->set("title","我的地盘");	

		$smarty->set("users",envhelper::readToken());
		$smarty->display("my_index.html");
	}
	
	
	public function home_timeline(){
		$kuids=rq("kuid","");
		if (!$kuids) return "";

		$kuidarr=explode(",",$kuids);
		$users = envhelper::readToken();

		$ms=array();
		foreach($kuidarr as $kuid){
			$k=envhelper::parseKUID($kuid);
			$lfrom=$k['lfrom'];

			$api="openapi_".$lfrom;
			import($api);
			$api=new $api(); 

			$client=$api->getClient($kuid);	
			///print_r($api->getUserInfo($kuid));
			$ms  = $client->public_timeline(); // done	
						
		}
		echo json_encode($ms);
		exit;
	}
}
?>
