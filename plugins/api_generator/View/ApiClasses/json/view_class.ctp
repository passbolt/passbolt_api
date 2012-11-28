<?php

function extract_tags($tags, $apiDoc) {
	$return = array();
	foreach ($tags as $tag => $value) {
		if (!is_array($value)) {
			$return[$tag] = $this->ApiDoc->parse($value);
		} else {
			$return[$tag] = '';
			foreach ($value as $val) {
				$return[$tag] .= $this->ApiDoc->parse($val);
			}
		}
	}
	return $return;
}

$this->ApiDoc->setClassIndex($classIndex);

// Generate the output array which is a datastructure of all the values to be returned.
$output = array();

$output['classInfo'] = array(
	'classDescription' => $doc->classInfo['classDescription'],
	'filename' => $doc->classInfo['fileName'],
	'description' => $this->ApiDoc->parse($doc->classInfo['comment']['description']),
	'parents' => $doc->classInfo['parents'],
	'interfaces' => array(),
	'tags' => array()
);

if (!empty($doc->classInfo['interfaces'])) {
	foreach ($doc->classInfo['interfaces'] as $interface) {
		$output['classInfo']['interfaces'][] = $this->ApiDoc->classUrl($interface);
	}
}

if (!empty($doc->classInfo['comment']['tags'])) {
	$output['classInfo']['tags'] = extract_tags($doc->classInfo['comment']['tags'], $apiDoc);
}

//properties
$properties = array();

foreach ($doc->properties as $prop) {
	if ($this->ApiDoc->excluded($prop['access'], 'property')) {
		continue;
	}
	$definedInThis = ($prop['declaredInClass'] == $doc->classInfo['name']);
	$properties[] = array(
		'name' => $prop['name'],
		'access' => $prop['access'],
		'parentMethod' => !$definedInThis,
		'description' => $this->ApiDoc->parse($prop['comment']['description'])
	);
}
$output['properties'] = $properties;

//methods
$methods = array();

foreach ($doc->methods as $method) {
	if ($this->ApiDoc->excluded($method['access'], 'method')) {
		continue;
	}
	$definedInThis = ($method['declaredInClass'] == $doc->classInfo['name']);

	$parameters = array();
	foreach ($method['args'] as $name => $paramInfo) {
		$parameters[] = array(
			'name' => $name,
			'type' => $paramInfo['type'],
			'comment' => $paramInfo['comment'],
			'optional' => $paramInfo['optional'],
			'default' => ($paramInfo['hasDefault']) ? var_export($paramInfo['default'], true) : __d('api_generator', '(no default)')
		);
	}

	$methods[] = array(
		'name' => $method['name'],
		'access' => $method['access'],
		'description' => $this->ApiDoc->parse($method['comment']['description']),
		'parameters' => $parameters,
		'declaredIn' => array(
			'name' => $method['declaredInClass'],
			'url' => $this->ApiDoc->classUrl($method['declaredInClass'])
		),
		'line' => $method['startLine'],
		'tags' => extract_tags($method['comment']['tags'], $apiDoc)
	);
}

$output['methods'] = $methods;

echo json_encode($output);