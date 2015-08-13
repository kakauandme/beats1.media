<?php
	require_once("partials/functions.php");
	require_once("partials/templates.php");
	require_once("Mustache/Autoloader.php");
	Mustache_Autoloader::register();
	$m = new Mustache_Engine;
	connect();
	$lastTrack = getLastTrack();
	disconnect();
	if(!$lastTrack){
		die("No records in DB");
	}
?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
<title itemprop="name">Now playing on Beats 1</title>
<?php /*SEO */ ?>
<meta name="description" content="Curently playing on Beats 1 Radio by Apple Music" itemprop="description" />
<?php /*Header */ ?>
<?php require_once("partials/header.php"); ?>
<?php /*CSS */ ?>
<link rel="stylesheet" type="text/css" href="/css/now.css" media="all">
</head>
<body id="body">
	<?php 
		$lastTrack->artworkUrl = str_replace("100x100", "1500x1500", $lastTrack->artworkUrl100);
		echo $m->render($templates["now"],$lastTrack);
		require_once("partials/copy.php"); ?>	
	</div>
<?php /*Footer */ ?>
<?php require_once("partials/footer.php"); ?>
</body>
</html>