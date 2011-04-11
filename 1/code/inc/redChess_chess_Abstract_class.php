<?php
//所有Redchess的不同OPENAPI的牌操作相关的基类，不同的openapi继承此类即可
class redChess_chess_Abstract{
	public $lfrom="";
	public $lfromuid="";
	public $uid=0;
	
	protected $timestamp=0;
	protected $insertno=0;
	
	
	public function check(){
		$lfrom=$this->lfrom;
		$lfromuid=$this->lfromuid;

		$sql="select max(lasttime) as last from ".dbhelper::tname("red_chess_updatelog")." where lfrom='$lfrom' and lfromuid='$lfromuid' and uid=".$this->uid;

		$lasttime=dbhelper::getvalue($sql); 
		$time=explode(" ", microtime());
		//echo "update".$lasttime."/".($time[1]-3600*24*3);
		if($lasttime && $lasttime > $time[1]-3600*24*3){
			return false;
		}
		else{//echo "true";
			return true;
		}
	}
	
	//初始化，必须调用
	public function init(){

		//第一步:初始化种子 
		$seedarray =microtime(); 
		$seedstr =split(" ",$seedarray,5); 
		$seed =$seedstr[0]*10000;
		//第二步:使用种子初始化随机数发生器 
		srand($seed);
		//第三步:生成指定范围内的随机数 
		$random =rand(100,9999);
		
		$insertno=microtime_float()." ".$random;
		$this->insertno=substr(str_replace(array(" ","."),"",$insertno),count($insertno)-10);
		//echo "insertno==".$insertno;		//return;
		$this->preSql="REPLACE ".dbhelper::tname("red_chess_".$this->lfrom)." (id,lfrom,name,followers,followings,tweets,score,insertno,lasttime,avatar) VALUES";
		$this->spit="";
	
		$this->timestamp=getTimestamp();
	}
	
	//完成所有导入逻辑处理
	public function filish(){
		$lfrom=$this->lfrom;
		$lfromuid=$this->lfromuid;
		
		$sqllib="delete from ".dbhelper::tname("red_userchess")." where uid=".$this->uid." and lfrom='$lfrom' and lfromuid='$lfromuid'";
		dbhelper::execute($sqllib);		
		$sqllib="INSERT INTO ".dbhelper::tname("red_userchess")." (uid,lfrom,lfromuid,chessid) select ".$this->uid.",'$lfrom','$lfromuid',id from ".dbhelper::tname("red_chess_".$this->lfrom)." where insertno='".$this->insertno."'";		
		dbhelper::execute($sqllib);
		
		$sqllib="INSERT INTO ".dbhelper::tname("red_chess_updatelog")." (uid,lfrom,lfromuid,lasttime) select ".$this->uid.",'$lfrom','$lfromuid',".$this->timestamp."";		
		dbhelper::execute($sqllib);
	}


		
		
	protected function addChess($uid, $screen_name,$followers,$followings,$tweets,$score,$avatar){
		$timestamp=$this->timestamp;
		$this->preSql.=$this->spit."({$uid},'".$this->lfrom."', '".addslashes($screen_name)."',{$followers},{$followings},{$tweets},{$score},".$this->insertno.",{$timestamp},'".addslashes($avatar)."')";
		$this->spit=",";
	}
	
	protected function saveChess(){
		//echo $this->preSql;
		dbhelper::execute($this->preSql);
		/*if (!$res) {
			echo 'error is happing';// or exit;
		}*/
		
		$this->preSql="REPLACE ".dbhelper::tname("red_chess_".$this->lfrom)." (id,lfrom,name,followers,followings,tweets,score,insertno,lasttime,avatar) VALUES";
		$this->spit="";
	}
	
	
	protected function getClient(){
		$lfrom=$this->lfrom;
		$lfromuid=$this->lfromuid;
		
		$api="openapi_".$lfrom;
		import($api);
		$api=new $api();
		
		$this->client=$api->getClient(envhelper::packKUID($lfrom,$lfromuid));
		return $this->client;
	}
	
}


?>