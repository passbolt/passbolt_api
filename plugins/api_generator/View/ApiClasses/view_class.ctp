<?php
/**
 * View a single class
 *
 */
$this->set('title_for_layout', $doc->classInfo['name']);

$this->ApiDoc->setClassIndex($classIndex);

echo $this->element('class_info');
echo $this->element('properties');
echo $this->element('method_summary');
echo $this->element('method_detail');
