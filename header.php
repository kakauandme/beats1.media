<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
<?php /*CSS */ ?>
<style>
*{
  box-sizing: border-box;
}
body{
	font-family: -apple-system, "Helvetica Neue", Helvetica, Arial, sans-serif;
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
	font-weight: 200;
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
.grid{
	background-color: #000;
}

.grid-item{
	width: 25%;
	background-color: #000;
}
.grid-item a.album{
	width: 100%;
	display: block;
}
.grid-item.info{
	width: 100%;

}
@media (min-width: 800px) {
	.grid-item.info{
		width: 25% !important;

	}
}
.grid-item .footer{
	position: relative;
}
.grid-item img{
	width: 100%;
	opacity: 0;
	transition: opacity 1s ease;
	display: block;
}

.grid-item:nth-child(3n+1) img{
	transition-delay: 300ms;
	transition-duration: 600ms;
}
.grid-item:nth-child(3n+2) img{
	transition-delay: 500ms;
	transition-duration: 400ms;
}
.grid-item:nth-child(3n+3) img{
	transition-delay: 100ms;
	transition-duration: 800ms;

}
.grid-item img.ready{
	opacity: 1;
}
@media (min-width: 800px) {
	.grid-item{
		width: 5%;
	}
	.grid-item:nth-child(4n+4){
		width: 10%;
	}
	
	
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