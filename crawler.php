<?php 

	
	$newLine = "<br />";
	
	require_once("functions.php");
	

	
	//timeExecution("Setup");
	connect();
	$lastRecord = getLastRecord();
	//timeExecution("Query DB");


	$playlist = file_get_contents($mediaPath . $playlistFilename);

	//timeExecution("Read playlist");
	

	$pos = strrpos($playlist, ",");

	$fileName = trim(substr($playlist, $pos + 2));//get last file	
	

	if(!$lastRecord || $lastRecord->filename != $fileName){

		$file = file_get_contents($mediaPath . $fileName, false,NULL,-1, 3000);

		//timeExecution("Read track");;
		

		$albumPos = strpos($file, $albumAnchor);
		$artistPos = strpos($file,$artisAnchor);
		$titlePos = strpos($file, $titleAnchor);

		$artworkPos = strpos($file, $artworkAnchor); 
		$extentionPos = strpos($file, $extentionAnchor, $artworkPos);
		

		if($titlePos){
			$title =  stripRandomChars(substr($file, $titlePos + strlen($titleAnchor), 100));

			if(strlen($title) < 3){
				logText($file);
			}
			logText("Title: ".$title);
		}
		$album = "";
		if($albumPos && $artistPos){
			$album =stripRandomChars(substr($file, $albumPos + strlen($albumAnchor), $artistPos - $albumPos-strlen($albumAnchor)));	
			logText("Album: ".$album );
		}

		if($artistPos && $titlePos){
			$artist =stripRandomChars(substr($file, $artistPos + strlen($artisAnchor), $titlePos - $artistPos-strlen($artisAnchor)));	
			logText("Artist:".$artist);
		}
		// else{
		// 	logText("Not inough info");
		// 	return;
		// }

		if($artworkPos && $extentionPos){
			$artwork =  stripRandomChars(substr($file,$artworkPos + strlen($artworkAnchor),	$extentionPos + strlen($extentionAnchor) - $artworkPos-strlen($artworkAnchor)));
			logText("Artwork: ". $artwork);
		}
		//timeExecution("Parse info");
		
		if(isset($title) && isset($artist)){

			if(!$lastRecord ||  $lastRecord->title != $title ){

				insertRecord($fileName, $title, $album, $artist, $artwork);

				//timeExecution("Insert record");

				//iTunes API request
				$term = urlencode($artist . (isset($album)?(" " . $album):"") . " " . $title);
				// //echo $term;
				$iTunesJSON =  file_get_contents('http://itunes.apple.com/search?term='.$term.'&media=music&entity=song');
				$iTunesData  = json_decode($iTunesJSON, true);

				if($iTunesData["resultCount"] > 0){

					 $iTunesMetadata = $iTunesData["results"][0];
					 //timeExecution("Pull iTunes data");
					 $iTunesMetadata["artworkUrl1500"] = str_replace(".100x100-75", ".1500x1500-75", $iTunesMetadata["artworkUrl100"]);				

					 insertMedia($iTunesMetadata);
					 //timeExecution("Add media");
					
				}else{
					logText("No itunes info");
				}

				//$content = "<div style='background-image: url({$artwork});'><h1>{$artist} - {$title}</h1></div>";



				// $contentFile = fopen("content.php", "w");
				// fwrite($contentFile, $content);
				// fclose($contentFile);

			}elseif($lastRecord){
				//UPDATE filename in DB
				updateRecord($lastRecord->id, $fileName);
				//timeExecution("Update record");
				logText("Song duplicate");
				
			}
		}else{
			logText("Not enough info");
			logText($file);
		}

	}else{
		logText("File duplicate");
	}
	disconnect();

	
