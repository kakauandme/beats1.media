<!--<?php
include_once("config.php");
?>-->
<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<title itemprop="name">Beats Media</title>
<?php /*SEO */ ?>
<meta name="description" content="Cureent song at Beats 1 radio" itemprop="description" />
<?php /*CSS */ ?>
<style>
*{
  box-sizing: border-box;
}
body{
	font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
	margin: 0;
	position: relative;
}
body > div{
	height: 100vh;
	background-size: cover;
	background-position: center center;
	background-repeat: no-repeat;	
}

h1{
	font-weight: 100;
	margin: 0;
	padding: 1em;
	position: absolute;
	left: 0;
	top: 0;
	color: white;
	width: 100%;
	background-color: black;
	opacity: 0.8;

}
.footer{
	position: absolute;
	padding: 1em;
	margin: 0;
	right: 0;
	bottom: 0;
	color: white;
	width: 100%;
	text-align: right;
	background-color: black;
	opacity: 0.8;
}
a{
	text-decoration: none;
	color: white;
}
</style>
<?php /*browsers */ ?>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<?php /* iOS meta */?>
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-title" content="Beats Media">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<?php /*iOS */ ?>
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">

<?php /*Android */ ?>
<meta name="mobile-web-app-capable" content="yes">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
<meta name="theme-color" content="#000000">

</head>
<body id="body">
<?php include_once("content.php"); ?>
<p class="footer">&copy <?php echo date("Y"); ?> Beats Media by <a href="https://twitter.com/KaKaUandME" alt="Twitter of KaKaUandME">@kakauandme</a></p>
</body>
</html>