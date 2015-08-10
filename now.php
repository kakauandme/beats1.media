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
<div style='background-image: url(<?php echo $lastRecord->artwork;?>);'><h1><?php echo $lastRecord->artist;?> &mdash; <?php echo $lastRecord->title;?></h1></div>
<?php require_once("copy.php"); ?>
<?php /*Footer */ ?>
<?php require_once("footer.php"); ?>
</body>
</html>