<?php
include_once(APP_PATH."/inc/redChess_class.php");
class redchess{
	function index(){ // 这里是首页
		$smarty=new light();
		$smarty->set("title","红棋");		
		
		$ppt=new ppt();
		$u=$ppt->readUser();
		$smarty->set('userinfo',$u);
				
		$rchess=new redChessLib($u['uid']);		
		$redinfo=$rchess->loginUser();
		$smarty->set('redinfo',$redinfo);
		
		$users=envhelper::readToken();

		$smarty->set("users",$users);
		$smarty->display("redchess_index.html");
	}
	
	function sync(){		
		$ppt=new ppt();
		$u=$ppt->readUser();
		$uid=$u['uid'];
		
		$u=envhelper::parseKUID();
		$rchess="redChess_chess_".$u['lfrom'];
		import($rchess);
		$rchess=new $rchess($uid,$u['lfromuid']);
		
		$rchess->updateChessLib(500);
	}
	
	function onlines(){		
		$u=envhelper::parseUID();
			
		$rchess="redChess_".$u['lfrom'];
		import($rchess);
		$rchess=new $rchess($u['lfromuid']);
		
		$userinfo=$rchess->onlines();
	}
}	