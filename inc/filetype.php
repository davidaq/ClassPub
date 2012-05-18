<?php
function is_image($filename){  
	$file = @fopen($filename, "rb");
	if(!$file)
		return false;
	$bin = fread($file, 2); //只读2字节  
	fclose($file);  
	$strInfo = @unpack("C2chars", $bin);
	$typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
	$fileType = '';
	$img=array(255216,7173,6677,13780); 
	return in_array($typeCode,$img);
} 
?>	
