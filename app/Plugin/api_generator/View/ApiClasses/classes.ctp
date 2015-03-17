<?php
/**
 * Browse Classes View file
 *
 */
$this->set('title_for_layout', __d('api_generator', 'Api Class index'));

/**
 * Tittle height to class name height ratio
 */
$titleWeight = 2;

/**
 * Number of columns to print
 */
$columns = 3;

$letterIndex = array_combine(range('A', 'Z'), array_fill(0, 26, null));
$maxWeight = 0;
foreach ($classIndex as $slug => $name):
	$firstLetter = strtoupper(substr($name, 0, 1));
	if (empty($letterIndex[$firstLetter])):
		$letterIndex[$firstLetter] = true;
		$maxWeight += $titleWeight;
	endif;
	$maxWeight += 1;
endforeach;

$maxWeight = floor($maxWeight / $columns);

$classChunks = array();

$chunk = 0;
$weight = 0;
$letter = '';
foreach ($classIndex as $slug => $name) {
	$firstLetter = strtoupper(substr($name, 0, 1));
	if ($firstLetter != $letter) {
		$weight += $titleWeight;
		$letter = $firstLetter;
	}

	if ($weight > $maxWeight) {
		$weight -= $maxWeight;
		if ($chunk < 2) {
			$chunk++;
		}
	}

	$classChunks[$chunk][$firstLetter][$slug] = $name;
	$weight ++;
}
?>
<h1><?php echo __d('api_generator', 'Index'); ?></h1>

<div class="letter-links show-on-desktops">
<?php

foreach (array_keys($letterIndex) as $letter):
	if (!$letterIndex[$letter]) {
		echo '<span>' . $letter . '</span>';
	} else {
		echo $this->Html->link($letter, '#letter-' . $letter);
	}
endforeach;
?>
</div>

<div class="row">
<?php $current = null; ?>
<?php foreach ($classChunks as $column): ?>
<div class="letter-section columns four">
	<?php foreach ($column as $letter => $classes): ?>
		<?php if ($current != $letter): ?>
			<h3><a id="letter-<?php echo $letter; ?>"></a><?php echo $letter; ?></h3>
		<?php else: ?>
			<h3><a id="letter-<?php echo $letter; ?>-cont"></a><?php echo $letter; ?> <?php echo __d('api_generator', '(cont.)') ?></h3>
		<?php endif; ?>
		<?php $current = $letter; ?>
		<ul class="class-index">
		<?php foreach ($classes as $slug => $name): ?>
			<li><?php
				echo $this->Html->link($name, array(
					'plugin' => 'api_generator', 'controller' => 'api_classes',
					'action' => 'view_class', $slug));
			?></li>
		<?php endforeach; ?>
		</ul>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>
</div>
