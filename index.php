<?php
	require_once("functions.php");
	connect();
	$topTracks = getTopTracks(149);
	disconnect();
	if(!$topTracks){
		die("No records in DB");
	}
	shuffle ( $topTracks);
?><!doctype html>
<html lang="en" itemscope itemtype="http://schema.org/Website">
<head>
<title itemprop="name">Beats 1 Top Tracks</title>
<?php /*SEO */ ?>
<meta name="description" content="Most played songs on Beats 1 Radio by Apple Music" itemprop="description" />
<?php /*Header */ ?>
<?php require_once("header.php"); ?>


</head>
<body id="body">
<?php 
	echo '<div class="grid">';
	foreach ($topTracks as  $track) {
		echo '<div class="grid-item"><a class="album" data-plays="' . $track->plays .'" target="_blank" href="' . $track->trackViewUrl .'" title="'.$track->trackName . ' &mdash; '.$track->artistName. '"><img src="'.$track->artworkUrl100.'" alt="'.$track->trackName. ' &mdash; '.$track->artistName. '" /></a></div>';
	}
	echo '<div class="grid-item info">';
	require_once("copy.php");
	echo '</div>';
	echo '</div>';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.imagesloaded/3.1.8/imagesloaded.pkgd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js"></script>
<script>
  var grid = document.querySelector('.grid');
  var msnry;
  document.addEventListener("DOMContentLoaded", function() {
	  var imgLoad = imagesLoaded(grid);

	  imgLoad.on( 'always', function() {

	    // init Isotope after all images have loaded
	    msnry = new Masonry( grid, {
	      itemSelector: '.grid-item',
	      //columnWidth: '.grid-sizer',
	      percentPosition: true
	    });
	    for ( var i = 0, len = imgLoad.images.length; i < len; i++ ) {
		    imgLoad.images[i].img.className="ready";
		}
		console.log(imgLoad.images.length + " tracks are loaded");
	  });
	});
  
</script>
<?php /*Footer */ ?>
<?php require_once("footer.php"); ?>
</body>
</html>