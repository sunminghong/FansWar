<?php
class ppt{
	function login($uidarr){
		$lfromuid=$uidarr['lfromuid'];
		$sql="select uid,name from ".dbhelper::tname('ppt_user_lfrom')." where lfromuid='".$uidarr['lfromuid']." and lfrom='".$uidarr['lfrom']."'";
		$rs=dbhelper::getrs($sql);
		if($row=$rs->next()){
			$uid=$row['uid'];
			$name=$row['name'];	
		}
		
		$timestamp=getTimestamp();
		//如果已经有此帐号，则修改相应的值
		
		$sqlu="name='". addslashes($uidarr['name'])."',lasttime=$timestamp,logintime=$timestamp,followers=".$uidarr['followers'].",followings=".$uidarr['followings'].",tweets=".$uidarr['tweets'].",tk='".$uidarr['tk']."',sk='".$uidarr['sk']."'";
		
		if($uid){
			$sql="update ".dbhelper::tname('ppt_user_lfrom')." set logins=logins+1,".$sqlu." where  lfromuid='$lfromuid' and lfrom='$lfrom'";			
			 
			dbhelper::execute($sql);
		}
		else{ //新用户则要添加相应的记录
			$sql="insert into ".dbhelper::tname('ppt_user')." (name,regtime,lasttime,logintime,logins) values('".$uidarr['name']."',$timestamp,$timestamp,$timestamp,1)";	
			$uid=dbhelper::execute($sql,1);
			
			$sql="insert into ".dbhelper::tname('ppt_user_lfrom')." set uid=$uid,lfrom='".$uidarr['lfrom']."',lfromuid=$lfromuid,logins=1,regtime=$timestamp,".$sqlu;
		}
		
		dbhelper::execute($sql);
		$uidarr=array('uid'=>$uid,'name'=>($name?$name:$uidarr['name']));
		
		$json=serialize($uidarr);//echo $json;exit;
		$json= authcode($json, 'ENCODE', $key = 'abC!@#$%^');
		ssetcookie("account",$json,3600*24*30);
		//echo $uid.$sql;
		//exit;
		return $uid;
	}
	
	function readUser(){
		$json=sreadcookie('account');//str_replace("\\","",);
		if (!$json)
			return null;

		$json= authcode($json, 'DECODE', $key = 'abC!@#$%^');
		$session=unserialize($json);
		return $session;
	}

}

/*
CREATE TABLE `test` (
  `id` int(11) NOT NULL auto_increment,
  `uid` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `name2` text NOT NULL,
  `name3` text NOT NULL,
  `name4` text NOT NULL,
  `name5` text NOT NULL,
  `name6` text NOT NULL,
  `name7` text NOT NULL,
  `name8` text NOT NULL,
 
  PRIMARY KEY  (`id`),
  KEY `uid` (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1


insert into test(uid,name,name2,name3,name4,name5,name6,name7,name8)
select rand(),rand(),rand(),rand(),rand(),rand(),rand(),rand(),rand())

*/