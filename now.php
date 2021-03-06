<?php

	require_once("partials/functions.php");
	$title = $nav["now"]->title; 
	$description = $nav["now"]->description;
	
	
	require_once("partials/templates.php");
	require_once("Mustache/Autoloader.php");
	Mustache_Autoloader::register();
	$m = new Mustache_Engine;
	connect();
	$lastTrack = getLastTrack();
	disconnect();

	

?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
<title itemprop="name">Now playing on Beats 1</title>
<?php /*SEO */ ?>
<meta name="description" content="Curently playing on Beats 1 Radio by Apple Music" itemprop="description" />
<?php /*Header */ ?>
<?php require_once("partials/header.php"); ?>
<?php /*CSS */ ?>
	<style type="text/css" media="all">
		<?php require_once("css/now_inline.css"); ?>
	</style>
</head>
<?php flush(); ?>
<body id="now">

	<?php $lastTrack->artworkUrl = str_replace("100x100", "1500x1500", $lastTrack->artworkUrl100);
	echo $m->render($templates["now"],$lastTrack);
	require_once("partials/copy.php"); ?>
	<script>		
		<?php
		echo "var cacheBuster = '". $cacheBuster . "';\n";
		require_once("js/inline-now.min.js");
		?>
	</script>
	<script type="text/javascript" async src="js/script.min.<?php echo $cacheBuster; ?>.js"></script>
<?php /*Footer */ ?>
	<?php require_once("partials/footer.php"); ?>
</body>
</html>