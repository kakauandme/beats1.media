<?php
require_once("functions.php");
connect();
$topTracks = getTopTracks(22);
$lastTrack = getLastTrack();
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
	<?php /*CSS */ ?>
	<link rel="stylesheet" type="text/css" href="/css/screen.css" media="all">
</head>
<body id="top" class="no-js">
	<?php 
	echo '<div id="grid">';
	$cnt= 0;
	$randomPos = rand(0, count($topTracks));
	foreach ($topTracks as  $track) {
		if($cnt++ == $randomPos){
			echo '<div class="grid-item now-playing" data-id="'. $lastTrack->trackId .'" data-src= "'.$lastTrack->artworkUrl100.'">'.
				'<a class="preview" data-plays="' . $lastTrack->plays .'" target="_blank" href="' . $lastTrack->trackViewUrl .'" title="Now playing '.$lastTrack->trackName . ' &mdash; '.$lastTrack->artistName. '">'.
					'<img class="artwork" src= "'.$lastTrack->artworkUrl100.'"  alt="'.$lastTrack->trackName. ' &mdash; '.$lastTrack->artistName. '" />'.
				'<div class="badge"></div></a>'.
			'</div>';
		}
		echo '<div class="grid-item" data-id="'. $track->trackId .'" data-src= "'.$track->artworkUrl100.'">'.
			'<a class="preview" data-plays="' . $track->plays .'" target="_blank" href="' . $track->trackViewUrl .'" title="'.$track->trackName . ' &mdash; '.$track->artistName. '">'.
				'<img class="artwork" src="'.$track->artworkUrl100.'"  alt="'.$track->trackName. ' &mdash; '.$track->artistName. '" />'.
			'</a>'.'
		</div>';
		
	}
	echo '</div>';
	require_once("copy.php");
	?>
	<script>
		var body = document.getElementById("top"); body.className = ""; //remove no-js
		var grid = document.getElementById("grid");

		var artworkSizes = [200,400,600,1200,1500];

		var r = false;
		var s = document.createElement('script');
		s.type = 'text/javascript';  
		s.async = "async";
		s.onload = s.onreadystatechange = function() {
			//console.log( this.readyState ); //uncomment this line to see which ready states are called.
			if ( !r && (!this.readyState || this.readyState == 'complete') )
			{
				r = true;
				reorderGrid(grid);
			}
		};
		var t = document.getElementsByTagName('script')[0];
		t.parentNode.insertBefore(s, t);
		s.src = "https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js"; 


		var items = grid.children;
		for (var i = 0; i < items.length; i++) {
				loadImage(items[i]);
		};		

		function loadImage(item){
			var width = item.offsetWidth;

			if(width > 100){
				var img = item.getElementsByTagName("img")[0];
										
				img.src = "";
			  	var src = item.getAttribute("data-src");
			  	img.onload=function(){
	  				item.className+=" ready";
			  	};
			  	img.onerror=function(){
			  		item.parentElement.removeChild(item);
			  	};
			  	if(src && width){
			  		

			  		var newWidth  = artworkSizes[artworkSizes.length-1];
			  		for (var i = 0; i < artworkSizes.length; i++) {
			  			if(width <= artworkSizes[i]){
			  				newWidth = artworkSizes[i];
			  				break;
			  			}
			  		};
			  		img.src = src.replace("100x100", newWidth+"x"+newWidth);
			  	}else{
			  		item.parentElement.removeChild(item);
			  	}

			}else{
				item.className+= " ready";
			}
			
			//detect country and swap link url  	
		}

		function reorderGrid(grid){

			var msnry = new Masonry( grid, {
				itemSelector: 'div.grid-item',
				//columnWidth: '.grid-sizer',
				percentPosition: true,
				transitionDuration: 0
		 	});

		}
		
  </script>
  <?php /*Footer */ ?>
  <?php require_once("footer.php"); ?>
</body>
</html>