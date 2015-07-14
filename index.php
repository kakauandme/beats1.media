<!--<?php
include_once("config.php");
?>-->
<!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<title itemprop="name">Beats 1 Media</title>
<?php /*SEO */ ?>
<meta name="description" content="Curent song playing at Beats 1 Radio by Apple Music" itemprop="description" />
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
<meta name="apple-mobile-web-app-title" content="Beats 1 Media">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

<?php /*iOS */ ?>
<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">

<?php /*Chrome & Android */ ?>
<link rel="manifest" href="/manifest.json">
<meta name="mobile-web-app-capable" content="yes">
<meta name="application-name" content="Beats 1 Media">
<meta name="theme-color" content="#ffffff">
<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">

<meta name="msapplication-TileColor" content="#ffffff">
<meta name="msapplication-TileImage" content="/mstile-144x144.png">

</head>
<body id="body">
<?php include_once("content.php"); ?>
<p class="footer">&copy <?php echo date("Y"); ?> Beats 1 Media by <a href="https://twitter.com/KaKaUandME" alt="Twitter of KaKaUandME">@kakauandme</a></p>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-40067737-11', 'auto');
  ga('send', 'pageview');
</script>
</body>
</html>