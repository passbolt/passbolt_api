<ul class="events">
<?php
$eventHelper = $event;
foreach($events as $event){
?>
	<li rel="<?php echo $event["Event"]["id"] ?>">
		<div class="entry">
		<?php 
		  echo $eventHelper->toText($event);
		?>
		</div>
		<div class="date">
			<?php
				echo $date->relativeTime(strtotime($event["Event"]["date"]));
			?>
		</div>
	</li>
<?php
}
?>
</ul>
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
