<?php 

	$newLine = "\r\n";
	
	require_once("functions.php");
	require_once("variables.php");



	$playlist = file_get_contents($mediaPath . $playlistFilename);

	$pos = strrpos($playlist, ",");
	$fileName = trim(substr($playlist, $pos + 2));//get last file
	$lastFileContent = file_get_contents("lastFile.txt");
	if($fileName == $lastFileContent){
		echo "LOG: File duplicate".$newLine;
		return;
	}
	$lastFile = fopen("lastFile.txt", "w");
	fwrite($lastFile, $fileName);
	fclose($lastFile);

	$file = file_get_contents($mediaPath . $fileName, false,NULL,-1, 3000);

	$albumPos = strpos($file, $albumAnchor);
	$artistPos = strpos($file,$artisAnchor);
	$titlePos = strpos($file, $titleAnchor);

	$artworkPos = strpos($file, $artworkAnchor); 
	$extentionPos = strpos($file, $extentionAnchor, $artworkPos);
	

	if($titlePos){
		$title =  stripRandomChars(substr($file, $titlePos + strlen($titleAnchor), 100));
		echo "Title: ".$title .$newLine;
	}

	 if($albumPos && $artistPos){
		$album =stripRandomChars(substr($file, $albumPos + strlen($albumAnchor), $artistPos - $albumPos-strlen($albumAnchor)));	
		echo "Album: ".$album .$newLine;
	}

	if($artistPos && $titlePos){
		$artist =stripRandomChars(substr($file, $artistPos + strlen($artisAnchor), $titlePos - $artistPos-strlen($artisAnchor)));	
		echo "Artist:".$artist .$newLine;
	}else{
		echo "LOG: Not inough info" .$newLine;
		return;
	}

	if($artworkPos && $extentionPos){
		$artwork =  stripRandomChars(substr($file,$artworkPos + strlen($artworkAnchor),	$extentionPos + strlen($extentionAnchor) - $artworkPos-strlen($artworkAnchor)));
		echo "Artwork: ". $artwork .$newLine;
	}
	

	$lastSongContent = file_get_contents("lastSong.txt");
	if($title  == $lastSongContent){
		echo "LOG: Song duplicate".$newLine;
		return;
	}
	$lastSong = fopen("lastSong.txt", "w");
	fwrite($lastSong, $title);
	fclose($lastSong);

	$content = "<div style='background-image: url({$artwork});'><h1>{$artist} - {$title}</h1></div>";

	$contentFile = fopen("content.php", "w");
	fwrite($contentFile, $content);
	fclose($contentFile);