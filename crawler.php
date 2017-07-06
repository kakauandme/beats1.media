<?php 

	
	$newLine = "<br>\r\n";
	
	require_once("partials/functions.php");
	

	
	//timeExecution("Setup");
	connect();
	$lastRecord = getLastMeta();
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

		//$artworkPos = strpos($file, $artworkAnchor); 
		//$extentionPos = strpos($file, $extentionAnchor, $artworkPos);
		

		if($titlePos){
			$title =  stripRandomChars(substr($file, $titlePos + strlen($titleAnchor), 100));
			logText("Title: ".$title . " (". strlen($title).")");
		}
		$album = "";
		if($albumPos && $artistPos){
			$album =stripRandomChars(substr($file, $albumPos + strlen($albumAnchor), $artistPos - $albumPos-strlen($albumAnchor)));	
			logText("Album: ".$album . " (". strlen($album).")" );
		}

		if($artistPos && $titlePos){
			$artist =stripRandomChars(substr($file, $artistPos + strlen($artisAnchor), $titlePos - $artistPos-strlen($artisAnchor)));	
			logText("Artist: ".$artist . " (". strlen($artist).")");
		}
		// else{
		// 	logText("Not inough info");
		// 	return;
		// }

		// if($artworkPos && $extentionPos){
		// 	$artwork =  stripRandomChars(substr($file,$artworkPos + strlen($artworkAnchor),	$extentionPos + strlen($extentionAnchor) - $artworkPos-strlen($artworkAnchor)));
		// 	logText("Artwork: ". $artwork);
		// }
		//timeExecution("Parse info");
		
		if(isset($title) && isset($artist) && strlen($title) > 3 && strlen($artist) > 3){

			if(!$lastRecord ||  $lastRecord->title != $title ){

				insertRecord($fileName, $title, $album, $artist);

				//timeExecution("Insert record");

				//iTunes API request
				$term = urlencode($artist . ((isset($album) && strlen($album) > 3)?(" " . $album):"") . " " . $title);
				// //echo $term;
				$iTunesJSON =  file_get_contents('http://itunes.apple.com/search?term='.$term.'&media=music&entity=song&limit=1');
				$iTunesData  = json_decode($iTunesJSON, true);

				if($iTunesData["resultCount"] > 0){

					 $iTunesMetadata = $iTunesData["results"][0];
					 //timeExecution("Pull iTunes data");

					 insertMedia($iTunesMetadata);
					 //timeExecution("Add media");
					
				}else{
					logText("No info at iTunes API for " . $term);
				}

				//$content = "<div style='background-image: url({$artwork});'><h1>{$artist} - {$title}</h1></div>";



				// $contentFile = fopen("content.php", "w");
				// fwrite($contentFile, $content);
				// fclose($contentFile);

			}elseif($lastRecord){
				//UPDATE filename in DB
				updateRecord($lastRecord->id, $fileName);
				//timeExecution("Update record");
				logText("Skipping track that is already captured");
				
			}
		}else{
			logText("Not enough meta info in the file");
			//logText($file);
		}

	}else{
		logText("Skipping file that is already captured");
	}
	disconnect();