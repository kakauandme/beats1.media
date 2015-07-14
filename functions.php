<?php 
function stripRandomChars($str){
	$str = trim($str);
	$chars = str_split($str);
	$flag = false;
	$start = 0;
	$finish = count($chars);	
	for ($i=0; $i < count($chars); $i++) { 
		if(!$flag && ord($chars[$i]) > 32 && $chars[$i] < 128){
			$start = $i;
			$flag= true;
		}
		if($flag && (ord($chars[$i]) < 32 || ord($chars[$i]) >= 128)){
			$finish = $i;
			break;
		}
	}
	return substr($str,$start, $finish - $start);
}