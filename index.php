<?php
	require_once("functions.php");
	connect();
	$topTracks = getTopTracks(22);
  $lastRecord = getLastRecord();
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
  $cnt= 0;
	foreach ($topTracks as  $track) {
		echo '<div class="grid-item"><a class="album-link" data-plays="' . $track->plays .'" target="_blank" href="' . $track->trackViewUrl .'" title="'.$track->trackName . ' &mdash; '.$track->artistName. '"><img class="artwork" src data-src= "'.$track->artworkUrl.'"  alt="'.$track->trackName. ' &mdash; '.$track->artistName. '" /></a></div>';
    if($cnt++ == 6){
      echo '<div class="grid-item now"><a class="album-link" data-plays="' . $lastRecord->plays .'" target="_blank" href="' . $lastRecord->trackViewUrl .'" title="Now playing '.$lastRecord->trackName . ' &mdash; '.$lastRecord->artistName. '"><img class="artwork" src data-src= "'.$lastRecord->artworkUrl.'"  alt="'.$lastRecord->trackName. ' &mdash; '.$lastRecord->artistName. '" /></a></div>';
    }
	}
	echo '<div class="grid-item footer">';
	   require_once("copy.php");
	echo '</div>';
	echo '</div>';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/masonry/3.3.1/masonry.pkgd.min.js"></script>
<script>

  var artworkSizes = [100,200,400,600,1200,1500];

  function getImgUrl(img){
    var src = img.getAttribute("data-src");
    var container = img.parentElement.parentElement;
    var width = container.offsetWidth;

    if(src && width){
      var newWidth  = artworkSizes[artworkSizes.length-1];
      for (var i = 0; i < artworkSizes.length; i++) {
        if(width < artworkSizes[i]){
            newWidth = artworkSizes[i];
            break;
        }
      };
      img.src = src.replace("###x###", newWidth+"x"+newWidth);
    }else{
      container.parentElement.removeChild(container);
    }
    img.onload=function(){
        this.className+=" ready";
    };
    img.onerror=function(){
      container.parentElement.removeChild(container);
    };
  }

  var images = document.getElementsByClassName("artwork");
  for (var i = 0; i < images.length; i++) {
    
    getImgUrl(images[i]);
  };
  var msnry = new Masonry( '.grid', {
    itemSelector: '.grid-item',
     //columnWidth: '.grid-sizer',
    percentPosition: true,
    transitionDuration: 0
  });
  
</script>
<?php /*Footer */ ?>
<?php require_once("footer.php"); ?>
</body>
</html>