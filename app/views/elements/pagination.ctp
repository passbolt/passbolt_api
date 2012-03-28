<?php
if($offset > 0){
	$offsetPrev = $offset - $nb_results;
	//$urlPrev = "/events/retrieveForCategory/$offsetPrev";
}
else{
	$offsetPrev = "#";
}

if($offset < $count - $nb_results){
	$offsetNext = $offset + $nb_results;
	//$urlNext = "/events/retrieveForCategory/$offsetNext";
}
else{
	$offsetNext = "#";
}
	?>
<div id="activity-nav"><a href="<?= $offsetPrev ?>" class="prev">Prev</a><span class="activity-page">Page <?= ($offset / $nb_results) + 1?> / <?= round($count / $nb_results) ?></span><a href="<?= $offsetNext ?>" class="next">Next</a></div>
