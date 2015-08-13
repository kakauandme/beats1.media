<?php
require_once("functions.php");
connect();
$topTracks = getTopTracks(22);
$lastTrack = getLastTrack();
disconnect();
if(!$topTracks){
	die("No records in DB");
}
$topTrackId = $topTracks[0]->trackId;
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
		$winner = $track->trackId == $topTrackId;
		if($cnt++ == $randomPos){
			echo '<div class="grid-item now-playing" data-id="'. $lastTrack->trackId .'" data-src= "'.$lastTrack->artworkUrl100.'" data-plays="'.$lastTrack->plays.'">'.
				'<a class="preview" target="_blank" href="' . $lastTrack->trackViewUrl .'" title="Now playing: '.$lastTrack->trackName . ' &mdash; '.$lastTrack->artistName. '">'.
					'<img class="artwork" src= "'.$lastTrack->artworkUrl100.'"  alt="'.$lastTrack->trackName. ' &mdash; '.$lastTrack->artistName. '" />'.
				'<div class="badge"></div></a>'.
			'</div>';
		}
		echo '<div class="grid-item' . ($winner?' winner':'') . '" data-id="'. $track->trackId .'" data-src= "'.$track->artworkUrl100.'" data-plays="'.$track->plays.'">'.
			'<a class="preview" target="_blank" href="' . $track->trackViewUrl .'" title="'. ($winner?'Most played track: ':'') . $track->trackName . ' &mdash; '.$track->artistName. '">'.
				'<img class="artwork" src="'.$track->artworkUrl100.'"  alt="'.$track->trackName. ' &mdash; '.$track->artistName. '" />'.
			($winner?'<div class="badge"></div>':'') . '</a>'.'
		</div>';
		
	}
	echo '</div>';
	require_once("copy.php");
	?>
	<script>
		var body = document.getElementById("top"); body.className = ""; //remove no-js
		var grid = document.getElementById("grid");

		var artworkSizes = [200,400,600,1200,1500];

		var layout =  function(g){
			var msnry = new Masonry( g, {
					itemSelector: 'div.grid-item',
					//columnWidth: '.grid-sizer',
					percentPosition: true,
					transitionDuration: 0
			 });
		};
		var createCookie = function(name,value,days) {
		    if (days) {
		        var date = new Date();
		        date.setTime(date.getTime()+(days*24*60*60*1000));
		        var expires = "; expires="+date.toGMTString();
		    }
		    else var expires = "";
		    document.cookie = name+"="+value+expires+"; path=/";
		}

		var readCookie = function(name) {
		    var nameEQ = name + "=";
		    var ca = document.cookie.split(';');
		    for(var i=0;i < ca.length;i++) {
		        var c = ca[i];
		        while (c.charAt(0)==' ') c = c.substring(1,c.length);
		        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		    }
		    return null;
		};

		var eraseCookie = function(name) {
		    createCookie(name,"",-1);
		};


		var updateUrls = function(code){
			createCookie("country",code, 7);
			for(var i = 0; i < items.length; i++) {
				var a = items[i].getElementsByTagName("a")[0];
				a.href = a.href.replace("/us/","/"+code+"/");
			};		
		};
		function processGeolocation(response){
			if(response){
				updateUrls(response.country.toLowerCase());					
			}
		}

		

		var updateImage = function(item){
			var width = item.offsetWidth;
			var img = item.getElementsByTagName("img")[0];

			if(img.complete) {
     			item.className +=" complete";
     		}else{
	     		img.onload=function(){
	     			if((' ' + item.className + ' ').indexOf(' complete ') === -1){
	     				item.className +=" complete";
	     			}
		  			
			  	};
     		}
			if(width > 100){
					
			  	var src = item.getAttribute("data-src");
			  	
			  	if(src && width){		  		
			  		item.className+=" loading";		
			  		var newWidth  = artworkSizes[artworkSizes.length-1];
			  		for (var i = 0; i < artworkSizes.length; i++) {
			  			if(width <= artworkSizes[i]){
			  				newWidth = artworkSizes[i];
			  				break;
			  			}
			  		}; 		
		     		
				  	var tmp = new Image();
				  	tmp.onload=function(){
				  		img.src = tmp.src;
			  			item.className = item.className.replace(" loading","");
				  	};
				  	tmp.onerror=function(){
		     			item.className = item.className.replace(" loading","");
				  	};
			  		tmp.src = src.replace("100x100", newWidth+"x"+newWidth);
			  	}

			}else{//small image
			
     			img.onerror=function(){
			  		item.parentElement.removeChild(item);
			  	};
			}
		};		

		var loadScript = function(src, callback, arg){
			var r = false;
			var s = document.createElement('script');
			s.type = 'text/javascript';  
			s.async = "async";
			s.onload = s.onreadystatechange = function() {
				//console.log( this.readyState ); //uncomment this line to see which ready states are called.
				if ( !r && (!this.readyState || this.readyState == 'complete') )
				{
					r = true;
					if(callback){
						callback(arg);
					}					
				}
			};
			var t = document.getElementsByTagName('script')[0];
			t.parentNode.insertBefore(s, t);
			s.src = src; 
		};

		loadScript("https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js", layout, grid); 


		var items = grid.children;
		for(var i = 0; i < items.length; i++) {
			updateImage(items[i]);
		};
		var country = readCookie("country");	
		if(country){
			updateUrls(country);
		}else{
			loadScript("http://ipinfo.io/?callback=processGeolocation"); 
		}
			
		
		
  </script>
  <?php /*Footer */ ?>
  <?php require_once("footer.php"); ?>
</body>
</html>