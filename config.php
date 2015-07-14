<?php 
	include_once("functions.php");
	$URL = 'http://itsliveradiobackup.apple.com/streams/hub02/session02/64k/';
	$path  =  getcwd() . "/";

	$albumAnchor = "TALB";
	$artisAnchor = "TPE1";
	$titleAnchor = "TIT2";

	$artworkAnchor = "artworkURL_640x";
	$extentionAnchor = ".jpg";

	$playlist = file_get_contents($URL . 'prog.m3u8');

	$pos = strrpos($playlist, ",");
	$fileName = trim(substr($playlist, $pos + 2));//get last file
	$lastFileContent = file_get_contents("lastFile.txt");
	if($fileName == $lastFileContent){
		echo "LOG: File duplicate"."\r\n";
		return;
	}
	$lastFile = fopen("lastFile.txt", "w");
	fwrite($lastFile, $fileName);
	fclose($lastFile);

	$file = file_get_contents($URL . $fileName, false,NULL,-1, 3000);

	$albumPos = strpos($file, $albumAnchor);
	$artistPos = strpos($file,$artisAnchor);
	$titlePos = strpos($file, $titleAnchor);

	$artworkPos = strpos($file, $artworkAnchor); 
	$extentionPos = strpos($file, $extentionAnchor, $artworkPos);
	

	if($titlePos){
		$title =  stripRandomChars(substr($file, $titlePos + strlen($titleAnchor), 100));
		echo "Title: ".$title ."\r\n";
	}

	 if($albumPos && $artistPos){
		$album =stripRandomChars(substr($file, $albumPos + strlen($albumAnchor), $artistPos - $albumPos-strlen($albumAnchor)));	
		echo "Album: ".$album ."\r\n";
	}

	if($artistPos && $titlePos){
		$artist =stripRandomChars(substr($file, $artistPos + strlen($artisAnchor), $titlePos - $artistPos-strlen($artisAnchor)));	
		echo "Artist:".$artist ."\r\n";
	}else{
		echo "LOG: Not inough info" ."\r\n";
		return;
	}

	if($artworkPos && $extentionPos){
		$artwork =  stripRandomChars(substr($file,$artworkPos + strlen($artworkAnchor),	$extentionPos + strlen($extentionAnchor) - $artworkPos-strlen($artworkAnchor)));
		echo "Artwork URL: ". $artwork ."\r\n";
	}

	$lastSongContent = file_get_contents("lastSong.txt");
	if($title  == $lastSongContent){
		echo "LOG: Song duplicate"."\r\n";
		return;
	}
	$lastSong = fopen("lastSong.txt", "w");
	fwrite($lastSong, $title);
	fclose($lastSong);

	$content = "<div style='background-image: url({$artwork});'><h1>{$artist} - {$title}</h1></div>";

	$contentFile = fopen("content.php", "w");
	fwrite($contentFile, $content);
	fclose($contentFile);