<?php
include_once("redChess_chess_Abstract_class.php");
class redChess_chess_tsina extends redChess_chess_Abstract{
	
	function __construct($uid,$lfromuid){
		$this->lfromuid=$lfromuid;
		$this->lfrom='tsina';
		$this->uid=$uid;
	}
	
	public function updateChessLib($top=500){
		if(!$this->check()) return;
		
		$this->init();
		$this->downChess(-1,$this->lfromuid,$top);
		$this->filish();
	}

	
	/**
	 * 游戏登录
	 * @param array $uid 用户参数数组，必须包括uid,name,followers,followings,tweets
	 * @return array  返回登录用户相关数据
	 * @access public
	 */	
	 private function downChess($cursor,$uid,$top){	
		$follower_list=$this->getClient()->followers($cursor,10,$uid);
		//echo $cursor."-".$uid;
		//print_r($follower_list);
		if ($follower_list["users"]) {
			//$sql="REPLACE INTO red_chess (uid,name,followers,followings,tweets,score,insertno,lasttime,avatar) VALUES";
			
			//$spit="";
			foreach($follower_list["users"] as $user){
				$uid_=$user['id'];
				$screen_name=$user['screen_name'];
				$followings=$user['friends_count'];
				$followers=$user['followers_count'];
				$tweets=$user['statuses_count'];
				$avatar=$user['profile_image_url'];
				
				$score=$following+$follwers*50+$tweets*3;
				//$sql.=$spit."({$uid_}, '".addslashes($screen_name)."',{$followers},{$followings},{$tweets},{$score},{$insertno},{$timestamp},'".addslashes($avatar)."')";
				//$spit=",";
				 $this->addChess($uid_, $screen_name,$followers,$followings,$tweets,$score,$avatar);				
			}
			$this->saveChess();
	
			if ($follower_list["next_cursor"]){
				$cursor=$follower_list["next_cursor"];
				if($cursor!=0 && $cursor<$top){
					//)会循环调用本方法downchess()
					$this->downChess($cursor,$uid,$top);
				}
			}
		}
	}
}


?>