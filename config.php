<?php 
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


$URL = 'http://itsliveradiobackup.apple.com/streams/hub02/session02/64k/';

$path  =  getcwd() . "/";

$albumAnchor = "TALB";
$artisAnchor = "TPE1";
$titleAnchor = "TIT2";

$artworkAnchor = "artworkURL_640x";
$artworkAnchorEnd = ".jpg";

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

$playlist = file_get_contents($URL . 'prog.m3u8');

$pos = strrpos($playlist, ",");
$fileName = trim(substr($playlist, $pos + 2));//get last file
$lastFileContent = file_get_contents("lastFile.txt");
if($fileName == $lastFileContent){
	echo "file duplicate";
	return;
}
$lastFile = fopen("lastFile.txt", "w");
fwrite($lastFile, $fileName);
fclose($lastFile);

 $file = file_get_contents($URL . $fileName, false,NULL,-1, 3000);

$albumPos = strpos($file, $albumAnchor);
$artistPos = strpos($file,$artisAnchor);
$titlePos = strpos($file, $titleAnchor);

$artworkStart = strpos($file, $artworkAnchor); 
$artworkEnd = strpos($file, $artworkAnchorEnd, 
$artworkStart);

if($artworkStart && $artworkEnd){
$artwork =  stripRandomChars(substr($file, 
$artworkStart + strlen($artworkAnchor), 
$artworkEnd + strlen($artworkAnchorEnd) - 
$artworkStart-strlen($artworkAnchor)));
echo "Artwork URL:". $artwork . "<br>";
}
if($titlePos){
	$title =  stripRandomChars(substr($file, $titlePos + strlen($titleAnchor), 100));
	echo "Title:".$title . "<br>";
}
 if($albumPos && $artistPos){
	$album =stripRandomChars(substr($file, $albumPos + strlen($albumAnchor), $artistPos - $albumPos-strlen($albumAnchor)));	
	echo "Album:".$album . "<br>";
}
if($artistPos && $titlePos){
	$artist =stripRandomChars(substr($file, $artistPos + strlen($artisAnchor), $titlePos - $artistPos-strlen($artisAnchor)));	
	echo "Artist:".$artist . "<br>";
}else{
	echo "not inough info";
	return;
}
$lastSongContent = file_get_contents("lastSong.txt");
if($title  == $lastSongContent){
	echo "song duplicate";
	return;
}
$lastSong = fopen("lastSong.txt", "w");
fwrite($lastSong, $title);
fclose($lastSong);
$content = "<div style='background-image: url({$artwork});'><h1>{$artist} - {$title}</h1></div>";
$contentFile = fopen("content.php", "w");
fwrite($contentFile, $content);
fclose($contentFile);
