<?php
	require_once("functions.php");
	connect();
	$lastRecord = getLastRecord();
	disconnect();
	if(!$lastRecord){
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
</head>
<body id="body">
<div class="now" style='background-image: url(<?php echo str_replace("100x100", "1500x1500", $lastRecord->artworkUrl100);?>);'><h1><?php echo $lastRecord->artistName;?> &mdash; <?php echo $lastRecord->trackName;?></h1>
<?php require_once("copy.php"); ?>	
</div>
<?php /*Footer */ ?>
<?php require_once("footer.php"); ?>
</body>
</html>