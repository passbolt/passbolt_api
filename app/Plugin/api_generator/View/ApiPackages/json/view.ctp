<?php
$this->ApiDoc->setClassIndex($classIndex);

$children = array();
foreach ($apiPackage['ChildPackage'] as $child) {
	$children[] = array(
		'name' => $child['name'],
		'url' => $this->ApiDoc->packageUrl(
			$child['name'], 
			array_merge($this->ApiDoc->path($child['path']), array('ext' => 'json'))
		)
	);
}

$classes = array();
foreach ($apiPackage['ApiClass'] as $packageClass) {
	$classes[] = array(
		'name' => $packageClass['name'],
		'url' => $this->ApiDoc->classUrl($packageClass['name'], array('ext' => 'json'))
	);
}

$output = array(
	'name' => $apiPackage['ApiPackage']['name'],
	'parent' => $apiPackage['ParentPackage']['name'],
	'children' => $children,
	'classes' => $classes
);

echo json_encode($output);
