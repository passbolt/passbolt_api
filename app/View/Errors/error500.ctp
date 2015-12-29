<h2><?php echo $message; ?></h2>
<strong><?php echo __d('cake', 'Error'); ?>: </strong>
<?php printf(
		__d('cake', 'An Internal Error occured when trying to reach the requested address %s.'),
		"<strong>'{$url}'</strong>"
); ?>
<?php
if (Configure::read('debug') > 0):
	echo $this->element('exception_stack_trace');
endif;
?>