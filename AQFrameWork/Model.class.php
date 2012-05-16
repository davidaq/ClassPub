<?php
class Model{
	private $doc;
	private $modelName;
	private $actions=array();
	private $lastSQL='';
	public function Model($path){
		global $_CONFIG;
		$this->modelName=$path;
		$path=str_replace('//','/',$_CONFIG['APP_PATH'].'/Model/'.$path.'.mod.php');
		if(!file_exists($path)){
			__error('No such model defined:'.$path);
		}
		$f=file($path);
		unset($f[0]);
		$actNow=false;
		$sqlStart=false;
		foreach($f as $l){
			$tl=trim($l);
			if($tl){
				$tc=strlen($tl);
				switch($tl{0}){
					case '%':
						if(preg_match('/^\%(.+?)\s*(?:\/\/(.*?))?$/',$tl,$m)==0)
							break;
						$actNow=$m[1];
						$sqlStart=false;
						$this->actions[$actNow]['doc']=isset($m[2])?trim($m[2]):'';
						$this->actions[$actNow]['sql']='';
						$this->actions[$actNow]['condition']='true';
						$key=&$this->actions[$actNow]['arg'];
						$key=array();
						break;
					case '$':
						if(!$sqlStart){
							if(preg_match('/^\$([a-zA-Z][a-zA-Z0-9]+)(?:\s*=\s*(.+?))?\s*(?:\/\/(.*))?$/',$tl,$m)==0)
								break;
							$key[$m[1]]['default']=isset($m[2])&&$m[2]!=''?$m[2]:NULL;
							$key[$m[1]]['doc']=isset($m[3])&&$m[3]!=''?$m[3]:'';
							break;
						}
					case ':':
						$this->actions[$actNow]['condition']=preg_replace('/\s*\/\/.+?$/','',substr($tl,1));
						break;
					default:
						if($actNow){
							$sqlStart=true;
							$this->actions[$actNow]['sql'].=preg_replace('/\s*\/\/.+?$/','',$l);
						}else
							$this->doc.=$l;
						break;
				}
			}
		}
		if(isset($this->actions['_init']))
			$this->call('_init');
	}
	private function validate(&$_arg,&$_condition,$_sqlF)
	{
		global $_CONFIG;
		$_cmd='$TP=$_CONFIG[\'TBLPRE\'];';
		$_sql=$_sqlF.' ';
		if($_arg){
			foreach($_arg as $k=>$f)
			{
				$_cmd.='$'.$k.'=$_arg[\''.$k.'\'];';
			}
			eval($_cmd);
		}
		if($_condition){
			if(eval('return '.$_condition.';')===false)
			{
				return false;
			}
		}
		$_save=array();
		$_k=0;
		while(preg_match('/(?!=\\\\)[\'"]/',$_sql,$_m,PREG_OFFSET_CAPTURE)){
			$_c=$_m[0][0];
			$_p=$_m[0][1];
			do{
				$_p=strpos($_sql,$_c,$_p+1);
				if($_p===false){
					__error('Quotation missmatch in sql statement :'.$_sqlF);
					return false;
				}
			}while($_sql{$_p-1}=='\\');
			$_c=$_p-$_m[0][1]+1;
			$_t=substr($_sql,$_m[0][1],$_c);
			$_save[$_k]=$_t;
			$_sql=substr($_sql,0,$_m[0][1]).'$_save['.$_k.']'.substr($_sql,$_p+1);
			$_k++;
		}
		$_sql=explode(';',$_sql);
		if(count($_sql)>0){
			foreach($_sql as $_k=>$_f){
				$_f=trim($_f);
				if(isset($_f{5})){
					$_sql[$_k]=eval('return "'.$_f.'";');
				}else
					unset($_sql[$_k]);
			}
		}else{
			__error('Can\'t parse action');
			return false;
		}
		return $_sql;
	}
	public function __call($method,$arg){
		if(isset($arg[0]))
			return $this->call($method,$arg[0]);
		else
			return $this->call($method);
	}
	private function transf($val){		//将变量转换为Sql表达式
		global $_DB;
		if(is_numeric($val))
			return $val;
		if(is_array($val)){
			$ret=array();
			foreach($val as $f){
				$ret[]=$this->transf($f);
			}
			return '('.implode(',',$ret).')';
		}
		return '"'.$_DB->escape($val).'"';
	}
	public function call($action,$_arg=false){
		global $_DB;
		if(!isset($this->actions[$action]))
			__error('No such action ['.$action.'] in model ['.$this->modelName.']',1);
		$act=&$this->actions[$action];
		$arg=array();
		$sql=$act['sql'];
		if($act['arg']){
			foreach($act['arg'] as $k=>$f)
			{
				if(isset($_arg[$k])){
					$arg[$k]=$_arg[$k];
				}elseif($f['default']===NULL)
				{
					__error('Argument ['.$k.'] missing for action ['.$action.'] in model ['.$this->modelName.']',1);
					return false;
				}else
					$arg[$k]=$f['default'];
				$arg[$k]=$this->transf($arg[$k]);
			}
		}
		if(($sql=$this->validate($arg,$act['condition'],$sql))===false){
			__error('Condition missmatch for action ['.$action.'] in model ['.$this->modelName.']',1);
			return false;
		}
		$ret=NULL;
		if(is_array($sql)){
			$this->lastSQL=implode(chr(10).';',$sql);
			foreach($sql as $f){
				$ret=$_DB->query($f);
			}
		}else{
			$this->lastSQL=$sql;
			$ret=$_DB->query($sql);
		}
		return $ret;
	}
	public function getLastSQL(){
		return $this->lastSQL;
	}
	public function help(){
		echo<<<html
<pre style="font-size:1.3em">
<h1>{$this->modelName}</h1>
<div>{$this->doc}</div>
html;
	foreach($this->actions as $k=>$f){
		$keys='';
		if($f['arg'])
			foreach($f['arg'] as $kk=>$ff){
				$keys.='<div>$'.$kk.($ff['default']?('=<i>'.$ff['default'].'</i>'):'').' [<i>'.$ff['doc'].'</i>]</div>';
			}
		echo<<<html
<h3>[{$k}]</h3>
<div>{$f['doc']}</div>
<i>(Valid Condition: {$f['condition']})</i>
{$keys}
<div>{$f['sql']}</div>
html;
	}
		echo'</pre>';
	}
}
?>
