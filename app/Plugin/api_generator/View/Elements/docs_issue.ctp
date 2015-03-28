<?php
/**
 * Describes issues with documentation
 *
 **/
?>
<div class="doc-issue">
	<h4><?php echo $issue['subject']; ?></h4>
	<ul>
	<?php foreach ($issue['scores'] as $problem): ?>
		<li><strong><?php echo $problem['description']; ?></strong> <?php echo $problem['score']; ?></li>
	<?php endforeach; ?>
	</ul>
</div>