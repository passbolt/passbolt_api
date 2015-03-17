<?php
/**
 * No class view file. Displayed when no class is found.
 *
 */
?>
<h2><?php echo __d('api_generator', 'No classes were found in the requested file'); ?></h2>
<p class="folder">
	<?php echo $this->Html->link(__d('api_generator', 'Up one folder'), array('action' => 'source', $previousPath)); ?>
</p>
