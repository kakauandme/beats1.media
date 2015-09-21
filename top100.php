<?php

$title = "Top 100 Tracks on Beats 1"; 
$description = "Top 100 most played songs played on Beats 1 Radio by Apple Music";

require_once("partials/functions.php");
require_once("partials/templates.php");
require_once("Mustache/Autoloader.php");
Mustache_Autoloader::register();
$m = new Mustache_Engine;

connect();
//$lastTrack = getLastTrack();
$topTracks = getTopTracks(100, 0);
disconnect();


// $lastTrack->className=" now-playing";
// $lastTrack->title="Now playing: ";

// $topTracks[0]->className=" most-played";
// $topTracks[0]->title="Most played track: ";

$shuffledTracks = $topTracks;

//array_push($shuffledTracks, $lastTrack);

//shuffle ( $shuffledTracks);

?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
	<?php /*Header */ ?>
	<?php require_once("partials/header.php"); ?>
	<?php /*CSS */ ?>
	<style type="text/css" media="all">
		<?php require_once("css/top_inline.css"); ?>
	</style>
</head>
<?php flush(); ?>
<body id="top" class="no-js">
	<?php 
	echo $m->render($templates["top100grid"], array("tracks" => $shuffledTracks));
	echo $m->render($templates["toplisting"], array("tracks" => $topTracks));
	require_once("partials/copy.php");
	?>
	<script>		
		<?php require_once("js/inline-min.js");
		echo "var cacheBuster = '". $cacheBuster . "';";
		?>
	</script>
	<script type="text/javascript" async src="js/script-min.<?php echo $cacheBuster; ?>.js"></script>
</body>
</html>