<?php
/**
 * Header search form
 *
 */
?>
<div id="header-search">
<?php echo $this->Form->create('ApiClass', array(
	'url' => array(
		'plugin' => 'api_generator', 'controller' => 'api_classes',
		'action' => 'search'
	),
	'type' => 'get',
)); ?>
<fieldset id="search-bar">
<?php
	if ($this->action === 'search' && !empty($this->passedArgs[0])) {
		$value = $this->passedArgs[0];
	} else {
		$value = '';
	}
	echo $this->Form->text('Search.query', array(
		'class' => 'query search-input',
		'value' => $value
	)); ?>
<?php echo $this->Form->submit(__d('api_generator', 'Search'), array('div' => false, 'class' => 'button red submit')); ?>
</fieldset>
<?php echo $this->Form->end(null); ?>
</div>
