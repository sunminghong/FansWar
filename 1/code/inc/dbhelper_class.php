<?php
class dbhelper {
	static function tname($tname){
//		$pre=substr($tname,0,4);
//		if($GLOBALS['DBPREDEFINED'][$pre])
//			return "`".$GLOBALS['DBPREDEFINED'][$pre]."`.`".$tname."`";
//		else
//			die('表名前缀不符合要求！');	

		return $GLOBALS['DBNAME'].".`".$tname."`";
	}
	
	public static $conns=array();

	/**
	 * 取得主数据库服务连接资源 
	 * @return mix  返回连接符
	 * @access public
	 */
	static function getConnM(){
		return self::getConn($GLOBALS['DBCONFIG'][0]);
	}

	/**
	 * 取从数据库服务连接资源 
	 * @return mix  返回连接符
	 * @access public
	 */
	static function getConnS(){
		$len=count($GLOBALS['DBCONFIG']);
		if ($len>1) {
			list($usec, $sec) = explode(" ", microtime());			
			$dbid=$sec % $len;
			if ($dbid==0)$dbid=1;
		}
		else
			$dbid=0;
			
		return self::getConn($GLOBALS['DBCONFIG'][$dbid]);
	}
	
	/**
	 * 取得数据库服务连接资源 ，内部私有属性
	 * @return mix  返回连接符
	 * @access private
	 */	
	static private function getConn($serv){
		$key=$serv['db']."_".$serv['dbuser']."_".$serv['dbpwd'];
		if(in_array($key,array_keys(self::$conns)))
			return self::$conns[$key];
			
		$conn=mysql_connect($serv['db'],$serv['dbuser'],$serv['dbpwd']);
		if($conn){
			mysql_select_db(DEFAULT_DB,$conn);
			mysql_query("SET NAMES '".DEFAULT_CHARTSET."'",$conn);
			self::$conns[$key]=$conn;
			return $conn;
		}
		else
			return false;		
	}
	
	/**
	 * 执行SQL语句，无返回数据
	 * @param string $sql sql语句
	 * @access public
	 */
	static function execute($sql,$newid=0){
		if(preg_match("/replace|insert|update|delete/i",$sql)){
			$res=mysql_query($sql,self::getConnM());
			if($newid)
				return mysql_insert_id(self::getConnM());
			else
				return $res;
		}else {
			$res=mysql_query($sql,self::getConnS());
			if($newid)
				return mysql_insert_id(self::getConnM());
			else
				return $res;
		}
	}
	
	//事务执行SQL语句
	static function exesqls($sql,$count=false){
		connstart();
		$sqlArr = split (';', $sql);
		mysql_query('BEGIN',self::getConnM());
		$errnum = 0;
		$exesqlCount = array();
		foreach ($sqlArr as $Sqltxt) {
			if($Sqltxt!=''){
				$Sqltxt = trim($Sqltxt);
				$res = mysql_query($Sqltxt,self::getConnM());
				if(mysql_error(self::getConnM())){
					$errnum++;
				}else{
					if($count){
						if(preg_match("/select/i",$sql)){
							array_push($exesqlCount,mysql_num_rows($res));
						}else{
							array_push($exesqlCount,mysql_affected_rows()); 	
						}
					}
				}
			}
		}
		if($errnum){
			mysql_query("ROLLBACK",$conn);
			return $exesqlCount;
		}else{
			mysql_query("COMMIT",$conn);
			return $exesqlCount;
		}
	}
	
	
	/**
	 * 执行SQL语句并返回recordset类记录集对象
	 * @param string $sql sql语句
	 * @return recordset 返回一个recordset对象，有next()方法
	 * @access public
	 */	
	static function getrs($sql){
		$conn=null;
		if(eregi("replace|insert|update|delete",$sql)){
			$conn=self::getConnM();
		}else {
			$conn=self::getConnS();
		}

		return new recordset($sql,$conn);
	}
	
	/**
	 * 执行SQL语句并返回第一行第一列的值
	 * @param string $sql sql语句
	 * @return string 返回第一行第一列的值
	 * @access public
	 */	
	static function getvalue($sql){
		if($row=self::getrs($sql)->next()){
			foreach($row as $val)
				return $val;	
		}else
			return "";		
	}
	
	/**
	 * 关闭数据库连接
	 * @access public
	 */		
	static function close(){
		foreach(self::$conns as $k=>$v){			
			mysql_free_result($v);
			mysql_close($v);
		}
	}
		
		
	/**
	 * 拼接分页html代码
	 * @access public
	 */	
	static function smulti($start, $perpage, $count, $url, $ajaxdiv='') {
		$multi = array('last'=>-1, 'next'=>-1, 'begin'=>-1, 'end'=>-1, 'html'=>'');
		if($start > 0) {
			if(empty($count)) {
				showmessage('no_data_pages');
			} else {
				$multi['last'] = $start - $perpage;
			}
		}
	
		$showhtml = 0;
		if($count == $perpage) {
			$multi['next'] = $start + $perpage;
		}
		$multi['begin'] = $start + 1;
		$multi['end'] = $start + $count;
	
		if($multi['begin'] >= 0) {
			if($multi['last'] >= 0) {
				$showhtml = 1;
				//if($_SGLOBALS[['inajax']) {
				//	$multi['html'] .= "<a href=\"javascript:;\" onclick=\"ajaxget('$url&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">|&lt;</a> <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[last]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&lt;</a> ";
				//} else {
					$multi['html'] .= "<a href=\"$url\">|&lt;</a> <a href=\"$url&start=$multi[last]\">&lt;</a> ";
				//}
			} else {
				$multi['html'] .= "&lt;";
			}
			$multi['html'] .= " $multi[begin]~$multi[end] ";
			if($multi['next'] >= 0) {
				$showhtml = 1;
				//if($_SGLOBALS[['inajax']) {
				//	$multi['html'] .= " <a href=\"javascript:;\" onclick=\"ajaxget('$url&start=$multi[next]&ajaxdiv=$ajaxdiv', '$ajaxdiv')\">&gt;</a> ";
				//} else {
					$multi['html'] .= " <a href=\"$url&start=$multi[next]\">&gt;</a>";
				//}
			} else {
				$multi['html'] .= " &gt;";
			}
		}
	
		return $showhtml?$multi['html']:'';
	}
}

/**
 * 数据记录集对像
 * @access public
 */
class recordset{
	public $res=null;
	
	/**数据记录集构造函数
	 * @param string $sql  sql语句
	 * @param mix  $conn  数据库连接符
	 * @access public
	 */	
	function __construct($sql,$conn){
		if (!$sql) return;
		
		$res=mysql_query($sql,$conn);
		if (!$res)return;
		
		$this->res=$res;
	}
	
	/**返回下一条记录数组
	 * @return array  记录数组
	 * @access public
	 */		
	public function next(){
		if($this->res)
			return 	mysql_fetch_array($this->res, MYSQL_ASSOC) ; 
		else
			return false;
	}
}
?>