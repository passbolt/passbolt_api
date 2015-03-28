<?php
/**
 * View view.  Shows generated api docs from a file.
 * 
 */
$this->set('title_for_layout', $this->ApiDoc->trimFileName($currentPath));

$this->ApiDoc->setClassIndex($classIndex);
?>
<h1 class="breadcrumb"><?php echo $this->element('breadcrumb'); ?></h1>
<?php
echo $this->element('element_toc');

if (!empty($docs['function'])) :
	foreach ($docs['function'] as &$function):
		echo $this->element('function_summary', array('doc' => $function));
	endforeach;
endif;

if (!empty($docs['class'])):
	foreach ($docs['class'] as $class):
		echo $this->element('class_info', array('doc' => $class));
		echo $this->element('properties', array('doc' => $class));
		echo $this->element('method_summary', array('doc' => $class));
		echo $this->element('method_detail', array('doc' => $class));
	endforeach;
endif;
?>
