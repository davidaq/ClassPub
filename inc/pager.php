<?php
function pager($total,$countPerPage,$page=PAGE){
	$end=ceil($total/$countPerPage);
	if($end<=6){
		return range(1,$end);
	}
	
	$left=$page-2;
	$right=$page+2;
	
	$ret=array();
	$ret[0]=1;
	if($left>2){
		$ret[]='.';
	}else
		$left=2;
	if($right<$end-1){
		$ttt=true;
	}else{
		$ttt=false;
		$right=$end-1;
	}
	for($i=$left;$i<=$right;$i++){
		$ret[]=$i;
	}
	if($ttt)
		$ret[]='.';
	$ret[]=$end;
	return $ret;
}
?>
