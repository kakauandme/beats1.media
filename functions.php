<?php 

$time_start = microtime(true);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "beats1.media";

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


function logText($str){

	global $newLine;
	echo "LOG: ". $str . $newLine;
}


function timeExecution($str){
	$tmp = microtime(true);
	
	global $time_start;
	global $newLine;
	echo "TIME " . $str.": " . round($tmp - $time_start,4) . " sec".$newLine;
	$time_start = $tmp;
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
	    die("MySQL connection failed: " . $conn->connect_error);
	} 
}


function getLastRecord(){
	global $connection;	

	$sql = "SELECT id, filename, title, album, artist, artwork FROM meta ORDER BY id DESC LIMIT 1";
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


function insertRecord($fileName, $title, $album, $artist, $artwork){

	global $connection;	

	$sql = "INSERT INTO meta (filename,title,album,artist,artwork) VALUES ('" . mysql_escape_string($fileName) . "', '" . mysql_escape_string($title) . "', '".mysql_escape_string($album)."', '". mysql_escape_string($artist). "', '". mysql_escape_string($artwork). "');";
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
			'"'.$data["artworkUrl30"].'",'.
			'"'.$data["artworkUrl60"].'",'.
			'"'.$data["artworkUrl100"].'",'.
			'"'.$data["artworkUrl1500"].'",'.
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
	logText($sql);
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

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "beats1.media";

// // Create connection
// $conn = new mysqli($servername, $username, $password, $dbname);
// // Check connection
// if ($conn->connect_error) {
//     die("MySQL connection failed: " . $conn->connect_error);
// } 

// $sql = "SELECT lastfile, lastsong FROM meta ODER BY id LIMIT 1";
// $result = $conn->query($sql);

// if ($result->num_rows > 0) {
//     // output data of each row
//     while($row = $result->fetch_assoc()) {
//         echo "id: " . $row["id"]. " - Name: " . $row["firstname"]. " " . $row["lastname"]. "<br>";
//     }
// } else {
//     echo "0 results";
// }
// $conn->close();