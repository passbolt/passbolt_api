<?php echo $this->Html->link(__d('api_generator', 'go back'), $backwards); ?>
<h1><?php echo __d('api_generator', 'Docs Coverage for '); echo $apiClass['ApiClass']['name']; ?></h1>
<div class="score-box">
	<div class="scorebar" style="width:<?php echo $this->Number->toPercentage($analysis['finalScore'] * 100); ?>;">
		<span class="score"><?php echo $this->Number->toPercentage($analysis['finalScore'] * 100); ?></span>
	</div>
</div>
<h2><?php echo __d('api_generator', 'Docs analysis:')?></h2>
<div class="class-info-coverage">
	<h3><?php echo __d('api_generator', 'Class info:'); ?></h3>
	<?php echo $this->element('docs_issue', array('issue' => $analysis['classInfo']));?>
</div>
<?php foreach (array('methods', 'properties') as $key): ?>
	<div class="<?php echo $key; ?>-coverage">
	<?php
	printf('<h3>%s (%s)</h3>', $key, $this->Number->toPercentage($analysis['sectionTotals'][$key]['average'] * 100));
	foreach ($analysis[$key] as $issue)  {
		echo $this->element('docs_issue', array('issue' => $issue));
	}
	?>
	</div>
<?php endforeach; ?>