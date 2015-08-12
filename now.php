<?php
	require_once("functions.php");
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
<?php require_once("header.php"); ?>
<?php /*CSS */ ?>
<link rel="stylesheet" type="text/css" href="/css/now.css" media="all">
</head>
<body id="body">
<div class="now" style='background-image: url(<?php echo str_replace("100x100", "1500x1500", $lastTrack->artworkUrl100);?>);'><h1><?php echo $lastTrack->artistName;?> &mdash; <?php echo $lastTrack->trackName;?></h1>
<?php require_once("copy.php"); ?>	
</div>
<?php /*Footer */ ?>
<?php require_once("footer.php"); ?>
</body>
</html>