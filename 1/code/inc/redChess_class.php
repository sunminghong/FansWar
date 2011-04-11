<?php
class redChessLib {
	public $presql="";

	public $uid=0;
		
	function __construct($uid){
		$this->uid=$uid;
	}

	public function onlines($flag=""){
		if($flag)
			$sql="select * from red_online r inner join red_user u on r.uid=u.uid where flag=".$flag." order by lasttime";
		else
			$sql="select * from red_online order by lasttime";
		
		$rs=dbhelper::getrs($sql);
		while($row=$rs->next()){
			//$arr=row[""]
		}		
	}
	
	/**
	 * 游戏登录
	 * @return array  返回登录用户相关数据
	 * @access public
	 */	
	public function loginUser(){
		$uid=$this->uid;
		$uidarr=array();
		
		$uidarr["wins"]=0;
		$uidarr["losts"]=0;
		$uidarr["score"]=0;
		$uidarr["logins"]=1;

		$timestamp=getTimestamp();

		$ss=" lasttime=$timestamp,logintime=$timestamp";  //,followers=".$uidarr['followers'].",followings=".$uidarr['followings'].",tweets=".$uidarr['tweets'];
		
		$sql="select * from ".dbhelper::tname("red_user")." where uid='".$uid."'";
		$rs=dbhelper::getrs($sql);
		if ($row = $rs->next()) {			
			$uidarr["wins"]=$row["wins"];
			$uidarr["losts"]=$row["losts"];
			$uidarr["score"]=$row["score"];
			$uidarr["logins"]=$row["logins"]+1;
			
			$sql="update ".dbhelper::tname("red_user")." set logins=logins+1,".$ss." where uid=".$uid; 
		}
		else
			$sql="insert into ".dbhelper::tname("red_user")." set uid=".$uid.",score=0,wins=0,losts=0,regtime=$timestamp,".$ss;

		dbhelper::execute($sql);
		
		//记录在线列表
		$sql="replace into ".dbhelper::tname("red_online")." (uid,flag,score,lasttime) values (".$uid.",1,0,$timestamp)";		
		dbhelper::execute($sql);
		return $uidarr;
	}	
}
?>