<?php
$output = array();

foreach ($classIndex as $slug => $name) {
	$output[] = array(
		'name' => $name,
		'url' => $this->Html->url(array(
			'plugin' => 'api_generator', 'controller' => 'api_classes',
			'action' => 'view_class', $slug,
			'ext' => 'json'
		))
	);
}

echo json_encode($output);