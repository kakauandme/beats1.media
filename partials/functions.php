<?php 


require_once("partials/variables.php");
$cacheBuster = 29;
//$time_start = microtime(true);
$siteName = "Beats 1 Media";
$baseURL = "http://" . $_SERVER["HTTP_HOST"];


$nav = [];

$nav["top"]  = new stdClass();
$nav["top"]->href="/";
$nav["top"]->title="Trending Tracks on Beats 1";
$nav["top"]->text="Trending Tracks";
$nav["top"]->description = "New popular songs on Beats 1 Radio by Apple Music";

$nav["now"]  = new stdClass();
$nav["now"]->href="/now";
$nav["now"]->title="Now playing on Beats 1";
$nav["now"]->text="Now playing";
$nav["now"]->description = "Curently playing on Beats 1 Radio by Apple Music";



$nav["top100"]  = new stdClass();
$nav["top100"]->href="/top100";
$nav["top100"]->title="Hottest 100 Albums on Beats 1";
$nav["top100"]->text="Hottest 100 Albums";
$nav["top100"]->description = "Most popular albums streamed on Beats 1 Radio by Apple Music";

$connection = null;

function stripRandomChars($str){
	$str = trim($str);
	$chars = str_split($str);
	$flag = false;
	$start = 0;
	$finish = count($chars);
	// for ($i=0; $i < 255; $i++) { 
	// 	echo $i . " - " . chr($i) . "<br/>";
	// }	
	for ($i=0; $i < count($chars); $i++) {
		if($flag && (ord($chars[$i]) < 32 || ord($chars[$i]) >= 127)){
			$finish = $i;
			break;
		}
		if(!$flag && ord($chars[$i]) > 47 && ord($chars[$i]) < 127){
			$start = $i;
			$flag= true;
		}
		
	}
	return substr($str,$start, $finish - $start);
}

function replaceSpaces($str){
	return preg_replace('/\s+/', '-', preg_replace('/[^a-z0-9\s]/', '', strtolower($str)));
}
function logText($str){

	global $newLine;
	echo "LOG: ". $str . $newLine;
}




function connect(){
	global $connection;
	global $servername;
	global $username;
	global $password;
	global $dbname;
	$connection = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($connection->connect_error) {
	    die("MySQL connection failed: " . $connection->connect_error);
	} 
}


function getLastTrack(){
	global $connection;	
	$sql = "SELECT  m.trackName, m.artistName, m.collectionName, m.artworkUrl100, m.trackViewUrl, m.previewUrl FROM media m JOIN plays p ON m.trackId = p.trackId WHERE p.`date` >= DATE_SUB(NOW(), INTERVAL 1 day) ORDER BY p.date DESC LIMIT 1;";
	//echo 	$sql ;
	$lastRecord = FALSE;

	if($result = $connection->query($sql)){

		if ($result->num_rows == 1) {

			$lastRecord = $result->fetch_object();
			$lastRecord->unique_title =  replaceSpaces($lastRecord->trackName .' '. $lastRecord->artistName);
			//$lastRecord->unique_title = replaceSpaces($lastRecord->trackName .' '. $lastRecord->artistName);

		}
		$result->close();
	}
	return $lastRecord;
}
function getLastMeta(){
	global $connection;	
	$sql = "SELECT id, filename, title, album, artist FROM meta  WHERE `date` >= DATE_SUB(NOW(), INTERVAL 1 day) ORDER BY id DESC LIMIT 1";
	//echo 	$sql ;
	$lastRecord = FALSE;

	if($result = $connection->query($sql)){

		if ($result->num_rows == 1) {

			$lastRecord = $result->fetch_object();
			

		}
		$result->close();
	}
	return $lastRecord;
}

function getTopTracks(){

	global $connection;	

	
	

	$sql = "	SELECT  
    
		`m`.`trackId` AS `trackId`,
        `m`.`trackName` AS `trackName`,
        `m`.`collectionId` AS `collectionId`,
        `m`.`collectionName` AS `collectionName`,
        `m`.`artistName` AS `artistName`,
        `m`.`artworkUrl100` AS `artworkUrl100`,
        `m`.`releaseDate` AS `releaseDate`,
        `m`.`primaryGenreName` AS `primaryGenreName`,
        `m`.`trackViewUrl` AS `trackViewUrl`,
        t1.plays1 - IF(t2.plays2 IS NULL, 0, t2.plays2) AS diff,
		t1.plays1 AS plays
    FROM (SELECT 
	`p1`.`trackId` AS trackId1,
    COUNT(0) AS `plays1`
    FROM
        `plays` `p1`

    WHERE
        (`p1`.`date` >= (NOW() - INTERVAL 3 WEEK)) 
    GROUP BY trackId1) t1
    LEFT JOIN 
    
    (SELECT 
	`p2`.`trackId` AS trackId2,
    COUNT(0) AS `plays2`
    FROM
        `plays` `p2`

    WHERE
        (`p2`.`date` < (NOW() - INTERVAL 1 WEEK))  AND (`p2`.`date` >= (NOW() - INTERVAL 2 WEEK)) 
    GROUP BY trackId2) t2 ON  t2.trackId2 = t1.trackId1
    
    JOIN media m ON m.trackId = t1.trackId1
    WHERE t1.plays1 > t2.plays2 OR t2.plays2 IS NULL
    ORDER BY diff DESC, t1.plays1 DESC
    LIMIT 16;";
	//echo 	$sql ;
	$topTracks = FALSE;

	if($result = $connection->query($sql)){

		if ($result->num_rows > 0 ) {
			$topTracks = array();
			$i = 0;
			while($obj = $result->fetch_object()) {
				$obj->i = ++$i;
				$obj->unique_title =  replaceSpaces($obj->trackName .' '. $obj->artistName);
		      array_push($topTracks,$obj); 
		    }
		

		}
		$result->close();
	}
	return $topTracks;
}


function getTopAlbums(){
	global $connection;	
	$sql = "SELECT a.collectionId, a.collectionName, a.artistName, a.trackViewUrl, UNIX_TIMESTAMP(a.releaseDate) AS releaseDate, UNIX_TIMESTAMP(MIN(a.firstPlay)) AS firstPlay, UNIX_TIMESTAMP(MAX(a.lastPlay)) AS lastPlay, a.artworkUrl100, a.primaryGenreName, MAX(a.plays) AS totalPlays, COUNT(*) AS numberOfTracks
			FROM (SELECT m.collectionId, m.collectionName, m.artistName, m.artworkUrl100, m.releaseDate, m.primaryGenreName, m.trackViewUrl,  MAX(p.`date`) AS lastPlay, COUNT(*) AS plays, MIN(p.`date`) AS firstPlay
					FROM media m 
			        JOIN plays p ON m.trackId = p.trackId
			        WHERE p.`date` >= DATE_SUB(NOW(), INTERVAL 1 year)
			        GROUP BY p.trackId) a			  
			GROUP BY a.collectionId
			ORDER BY TotalPlays DESC, NumberOfTracks DESC
			LIMIT 100;";
	$topAlbums = FALSE;
	if($result = $connection->query($sql)){

		if ($result->num_rows > 0 ) {
			
			$topAlbums = array();
			while($r = mysqli_fetch_assoc($result)) {
			    $topAlbums[] = $r;
			}
		}
		$result->close();
	}
	return $topAlbums;
}


function insertRecord($fileName, $title, $album, $artist){

	global $connection;	

	$sql = "INSERT INTO meta (filename,title,album,artist) VALUES ('" . mysql_escape_string($fileName) . "', '" . mysql_escape_string($title) . "', '".mysql_escape_string($album)."', '". mysql_escape_string($artist). "');";
	//echo $sql;
	if(!$connection->query($sql)){
		logText('Error : ('. $connection->errno .') '. $connection->error);
	}
}

function updateRecord($id, $fileName){

	global $connection;	
	
	$sql = "UPDATE meta SET filename = '" . mysql_escape_string($fileName) . "' WHERE id = {$id}";
	//echo $sql;
	if(!$connection->query($sql)){
		logText('Error : ('. $connection->errno .') '. $connection->error);
	}
}

function insertMedia($data){


	global $connection;

	$sql = 'SELECT trackId FROM media WHERE trackId = '. $data["trackId"];
	
	$result = $connection->query($sql);	
	if(!$result || $result->num_rows == 0){


		
		$sql = 'INSERT INTO media VALUES('.
			$data["trackId"].','.
			'"'.$data["trackName"].'",'.
			$data["artistId"].','.
			'"'.$data["artistName"].'",'.
			$data["collectionId"].','.
			'"'.$data["collectionName"].'",'.
			'"'.$data["previewUrl"].'",'.
			// '"'.$data["artworkUrl30"].'",'.
			// '"'.$data["artworkUrl60"].'",'.
			'"'.$data["artworkUrl100"].'",'.
			$data["trackPrice"].','.
			'"'.date ("Y-m-d H:i:s", strtotime($data["releaseDate"])).'",'.
			$data["trackTimeMillis"].','.
			'"'.$data["primaryGenreName"].'",'.
			'"'.$data["radioStationUrl"].'",'.
			'"'.$data["trackViewUrl"].'"'.
		');';

		if(!$connection->query($sql)){
			logText('Error : ('. $connection->errno .') '. $connection->error);
		}else{
			logText($data["trackName"] . " inserted");
		}
		
	}

	if($result){
		$result->close();
	}

	$sql = 'INSERT INTO plays VALUES('. $data["trackId"].', CURRENT_TIMESTAMP);';
	if(!$connection->query($sql)){
			logText('Error : ('. $connection->errno .') '. $connection->error);
	}else{
			logText($data["trackName"] . " recorder");
	}

	// echo "<pre>";
	// var_dump($data);
	// echo "</pre>";
}

function disconnect(){

	global $connection;
	 $connection->close();
}