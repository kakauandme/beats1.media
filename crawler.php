<?php 

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);


$URL = 'http://itsliveradiobackup.apple.com/streams/hub02/session02/64k/';

$path  =  getcwd() . "/";

$albumAnchor = "TALB";
$artisAnchor = "TPE1";
$titleAnchor = "TIT2";

function stripRandomChars($str){
	$str = trim($str);
	$chars = str_split($str);

	$start = 0;
	$finish = count($chars);	
	for ($i=1; $i < count($chars); $i++) { 
		if(!$start && ord($chars[$i]) > 32 && $chars[$i] < 128){
			$start = $i;
		}
		if($start && (ord($chars[$i]) < 32 || ord($chars[$i]) >= 128)){
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



//echo $fileName . "\r\n";


$file = file_get_contents($URL . $fileName, false,NULL,-1, 3000);


//echo $file. "<br>";


$albumPos = strpos($file, $albumAnchor);
$artistPos = strpos($file,$artisAnchor);
$titlePos = strpos($file, $titleAnchor);


if($titlePos){
	$title =  stripRandomChars(substr($file, $titlePos + strlen($titleAnchor), 100));
	echo $title . "<br>";
}


if($albumPos && $artistPos){
	$album =stripRandomChars(substr($file, $albumPos + strlen($albumAnchor), $artistPos - $albumPos-strlen($albumAnchor)));	
	echo $album . "<br>";
}

if($artistPos && $titlePos){
	$artist =stripRandomChars(substr($file, $artistPos + strlen($artisAnchor), $titlePos - $artistPos-strlen($artisAnchor)));	
	echo $artist . "<br>";
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


$term = urlencode($artist . (isset($album)?(" " . $album):"") . " " . $title);
//echo $term;
$iTunesJSON =  file_get_contents('http://itunes.apple.com/search?term='.$term.'&media=music&entity=song');
$iTunesData  = json_decode($iTunesJSON, true);

if($iTunesData["resultCount"] == 0){
	echo "no itunes info";
	return;
}

$iTunesMetadata = $iTunesData["results"][0];
$iTunesMetadata["artworkUrl1500"] = str_replace(".100x100-75", ".1500x1500-75", $iTunesMetadata["artworkUrl100"]);
$content = "<div style='background-image: url({$iTunesMetadata["artworkUrl1500"]});'><h1>{$iTunesMetadata["artistName"]} - {$iTunesMetadata["trackName"]}</h1></div>";


$contentFile = fopen("content.php", "w");
fwrite($contentFile, $content);
fclose($contentFile);
