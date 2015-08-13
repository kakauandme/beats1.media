<?php
require_once("partials/functions.php");
require_once("partials/templates.php");
require_once("Mustache/Autoloader.php");
Mustache_Autoloader::register();
$m = new Mustache_Engine;

connect();
$topTracks = getTopTracks(22);
$lastTrack = getLastTrack();
disconnect();
if(!$topTracks){
	die("No records in DB");
}
$topTrackId = $topTracks[0]->trackId;
shuffle ( $topTracks);
?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
	<title itemprop="name">Beats 1 Top Tracks</title>
	<?php /*SEO */ ?>
	<meta name="description" content="Most played songs on Beats 1 Radio by Apple Music" itemprop="description" />
	<?php /*Header */ ?>
	<?php require_once("partials/header.php"); ?>
	<?php /*CSS */ ?>
	<style type="text/css" media="all">
		<?php require_once("css/inline.css"); ?>
	</style>
</head>
<body id="top" class="no-js">
	<?php 
	echo '<div id="grid">';
		$cnt= count($topTracks);
		$randomPos = rand(0, $cnt);
		for ($i=0; $i < $cnt; $i++) { 
			if($i== $randomPos){
				$lastTrack->className=" now-playing";
				$lastTrack->title="Now playing: ";
				echo $m->render($templates["track"],$lastTrack);				
			}
			if($topTracks[$i]->trackId == $topTrackId){
				$topTracks[$i]->className=" most-played";
				$topTracks[$i]->title="Most played track: ";
			}
			echo $m->render($templates["track"],$topTracks[$i]);
		}
	echo '</div>';
	require_once("partials/copy.php");
	?>
	<script>		
		<?php require_once("js/inline-min.js");	?>
	</script>
	<script type="text/javascript" async src="js/script-min.js"></script>
	<?php /*Footer */ ?>
	<?php require_once("partials/footer.php"); ?>
</body>
</html>