<?php

require_once("partials/functions.php");

$title = "Page not found :("; 
$description = "";


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

<body id="top" class="no-js">
	<?php 
	echo "<h1 style='text-align: center;'>" . $title . "</h1>";
	require_once("partials/copy.php");
	?>
</body>
</html>