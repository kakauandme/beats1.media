<?php 


require_once("partials/variables.php");

//$time_start = microtime(true);
$siteName = "Beats 1 Media";
$baseURL = "http://" . $_SERVER["HTTP_HOST"];


$nav = [];

$nav[0]  = new stdClass();
$nav[0]->href="/";
$nav[0]->title="Recent Hits on Beats 1";
$nav[0]->text="Recent Hits";

$nav[1]  = new stdClass();
$nav[1]->href="/now";
$nav[1]->title="Now playing on Beats 1";
$nav[1]->text="Now playing";



// $nav[1]  = new stdClass();
// $nav[1]->href="/top100";
// $nav[1]->title="Top 100 Tracks on Beats 1";
// $nav[1]->text="Top 100";

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
	$sql = "SELECT p.date, m.trackId, m.trackName, m.artistName, m.artworkUrl100, m.trackViewUrl, COUNT(*) AS plays FROM media m LEFT JOIN plays p ON m.trackId = p.trackId GROUP BY p.date, m.trackId, m.trackName, m.artistName, m.artworkUrl100, m.trackViewUrl ORDER BY p.date DESC LIMIT 1;";
	//echo 	$sql ;
	$lastRecord = FALSE;

	if($result = $connection->query($sql)){

		if ($result->num_rows == 1) {

			$lastRecord = $result->fetch_object();
			$lastRecord->unique_title = replaceSpaces($lastRecord->trackName .' '. $lastRecord->artistName);

		}
		$result->close();
	}
	return $lastRecord;
}
function getLastMeta(){
	global $connection;	
	$sql = "SELECT id, filename, title, album, artist FROM meta ORDER BY id DESC LIMIT 1";
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
function getTopTracks($limit, $limitInWeeks){

	global $connection;	

	
	$timeLimit = "";
	if($limitInWeeks){
		$timeLimit = " WHERE p.`date` >= DATE_SUB(NOW(), INTERVAL " . $limitInWeeks . " WEEK) ";
	}

	$sql = "SELECT m.trackId, m.trackName, m.collectionName, m.artistName, m.artworkUrl100, m.primaryGenreName, m.trackViewUrl, COUNT(*) AS plays FROM media m LEFT JOIN plays p ON m.trackId = p.trackId ". $timeLimit ." GROUP BY m.trackId, m.trackName, m.collectionName, m.artistName,  m.artworkUrl100, m.primaryGenreName, m.trackViewUrl ORDER BY plays DESC LIMIT ".$limit.";";
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