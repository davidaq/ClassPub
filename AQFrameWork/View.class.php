<?php
class View{
	public $var=array();
	public $raw=false;
	public $fp=false;
	private static function makePath($path){
		global $_CONFIG;
		return str_replace('//','/',$_CONFIG['APP_PATH'].'/Template/'.$path);
	}
	public static function exists($path){
		$path=View::makePath($path);
		return file_exists($path.'.html');
	}
	public function View($path){
		$path=View::makePath($path);
		if(!file_exists($path.'.html')){
			__error('No such template defined:'.$path);
		}
		$this->fp=$path;
	}
	public function set($k,$v=''){
		if($this->fp===false)
			return;
		if($v===NULL){
			unset($this->var[$k]);
		}else
		{
			$this->var[$k]=$v;
		}
	}
	public function show(){
		global $_CONFIG;
		if($this->fp===false)
			return;
		if($this->raw===false){
			if(!file_exists($this->fp.'.html')){
				__error('No such template defined:'.$this->fp);
				return;
			}
			$this->raw=implode('',file($this->fp.'.html'));
			$raw=$this->raw;
			while(preg_match('/{:include\((.+?)\)}/',$raw,$M)){
				$path=View::makePath($M[1]);
				if(file_exists($path.'.html')){
					$raw=str_ireplace($M[0],implode('',file($path.'.html')),$raw);
				}else{
					$raw=str_ireplace($M[0],'',$raw);
				}
			}
			unset($M);
			unset($path);
			$raw=preg_replace('/{:if\s*\((.+?)\)}/i','<?php if($1){ ?>',$raw);
			$raw=preg_replace('/{:elseif\s*\((.+?)\)}/i','<?php }elseif($1){ ?>',$raw);
			$raw=preg_replace('/{:else}/i','<?php }else{ ?>',$raw);
			$raw=preg_replace('/{:(?:end|endif|endloop)}/i','<?php } ?>',$raw);
			$raw=preg_replace('/{:loop\s*\((.+?as.+?)\)}/i','<?php foreach($1){ ?>',$raw);
			$raw=preg_replace('/{:(.+?)}/','<?php echo $1;?>',$raw);
			$this->raw=$raw;
			unset($raw);
		}
		
		if(!$_CONFIG['DEBUG'])
			$__errorReportingLevel15101270=error_reporting(0);
		$cmd='';
		foreach(array_keys($this->var) as $k){
			$cmd.="\$$k=\$this->var['$k'];";
		}
		$cmd.='?>'.$this->raw;
		eval($cmd);
		if(!$_CONFIG['DEBUG'])
			error_reporting($__errorReportingLevel15101270);
	}
}
?>
