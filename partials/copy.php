<?php
$nav = [];

$nav[0]  = new stdClass();
$nav[0]->href="/";
$nav[0]->title="Recent Hits on Beats 1";
$nav[0]->text="Recent Hits";

$nav[1]  = new stdClass();
$nav[1]->href="/top100";
$nav[1]->title="Top 100 Tracks on Beats 1";
$nav[1]->text="Top 100";
$nav[2]  = new stdClass();
$nav[2]->href="/now";
$nav[2]->title="Now playing on Beats 1";
$nav[2]->text="Now playing";

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)

?>

<p class="footer">
<span class="links">
<?php for ($i=0; $i < ($cnt = count($nav)); $i++) { 
	echo "<a ". (($nav[$i]->href == $path)?"class='active'":"")."href='". $nav[$i]->href . "' title='".$nav[$i]->title."'>".$nav[$i]->text."</a>";
	if($i+1 != $cnt){
		echo " | ";
	}
}?>
</span>
<!-- <span class="links"><a href="/" title="Recent Hits on Beats 1">Recent Hits</a> | <a href="/top100" title="Top 100 Tracks on Beats 1">Top 100</a> | <a href="/now" title="Now playing on Beats 1">Now playing</a></span> -->
<span class="info">&copy;<?php echo date("Y") . " " .$siteName; ?> by <a href="https://twitter.com/KaKaUandME" title="Kirill Kliavin on Twitter" target="_blank">@kakauandme</a></span>
</p>