<?php
class DBConn{
	private $conn=FALSE;
	private function connect(){
		global $_CONFIG;
		if($this->conn===FALSE){
			$db=mysql_connect($_CONFIG['DBHOST'],$_CONFIG['DBUSER'],$_CONFIG['DBPASS']);
			if($db===FALSE)
				__error('Can not open connection to MySQL server',2);
			mysql_query("SET NAMES '{$_CONFIG['CHARSET']}'"); 
			mysql_query("SET CHARACTER_SET_CLIENT={$_CONFIG['CHARSET']}"); 
			mysql_query("SET CHARACTER_SET_RESULTS={$_CONFIG['CHARSET']}"); 
			mysql_select_db($_CONFIG['DBNAME']);
			if(mysql_errno()){
				__error(mysql_error(),2);
			}
			$this->conn=$db;
		}
		return $this->conn;
	}
	public function query($sql){
		$this->connect();
		$res=mysql_unbuffered_query($sql);
		if($res===FALSE){
			__error('SQL Error: ['.$sql.']'.mysql_error());
			return false;
		}else if($res!==TRUE)
		{
			$ret=array();
			while($row=mysql_fetch_assoc($res))
			{
				$ret[]=$row;
			}
			mysql_free_result($res);
			return $ret;
		}
		return mysql_affected_rows();
	}
	public function escape($str){
		$this->connect();
		return mysql_real_escape_string($str);
	}
}
$_DB=new DBConn;
?>
