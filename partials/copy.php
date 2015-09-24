<?php

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)

?>

<p id="footer">
<span class="links">

<?php
$cnt = count($nav);
$i = 0;
 foreach ($nav as $key => $value) { 
	echo "<a ". (($value->href == $path)?"class='active'":"")."href='". $value->href . "' title='".$value->title."'>".$value->text."</a>";
	if(++$i != $cnt){
		echo " | ";
	}
}?>
</span>
<!-- <span class="links"><a href="/" title="Recent Hits on Beats 1">Recent Hits</a> | <a href="/top100" title="Top 100 Tracks on Beats 1">Top 100</a> | <a href="/now" title="Now playing on Beats 1">Now playing</a></span> -->
<span class="info">&copy;<?php echo date("Y") . " " .$siteName; ?> by <a href="https://twitter.com/KaKaUandME" title="Kirill Kliavin on Twitter" target="_blank">@kakauandme</a></span>
</p>