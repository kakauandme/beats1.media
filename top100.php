<?php



require_once("partials/functions.php");

$title = $nav["top100"]->title; 
$description = $nav["top100"]->description;


require_once("partials/templates.php");
require_once("Mustache/Autoloader.php");



Mustache_Autoloader::register();
$m = new Mustache_Engine;

connect();
//$lastTrack = getLastTrack();
//$topTracks = getTopTracks(100, 0);
$topAlbums = getTopAlbums();
disconnect();

// $lastTrack->className=" now-playing";
// $lastTrack->title="Now playing: ";

// $topTracks[0]->className=" most-played";
// $topTracks[0]->title="Most played track: ";


//array_push($shuffledTracks, $lastTrack);

//shuffle ( $shuffledTracks);

?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
	<?php /*Header */ ?>
	<?php require_once("partials/header.php"); ?>
	<?php /*CSS */ ?>
	<style type="text/css" media="all">
		<?php require_once("css/top100_inline.css"); ?>
	</style>
</head>
<?php flush(); ?>
<body id="top100" class="no-js">
	<?php 
	echo $m->render($templates["top100grid"], array("tracks" => $topAlbums, "id"=>"top100grid"));
	//echo $m->render($templates["toplisting"], array("tracks" => $topTracks));
	echo $m->render($templates["topgraph"]);
	require_once("partials/copy.php");
	?>
	<script>		
		<?php require_once("js/inline-min.js");
		echo "var cacheBuster = '". $cacheBuster . "';\n";
		?>
		var topAlbums = <?=json_encode($topAlbums);?>;
		
		
		//console.log(topAlbums[0]);
	</script>
	<script type="text/javascript" async src="js/script-min.<?php echo $cacheBuster; ?>.js"></script>
</body>
</html>