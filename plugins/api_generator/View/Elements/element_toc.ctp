<?php
/**
 * A Table of Contents for the elements in the consumed file.
 *
 */
?>
<div id="element-toc" class="clearfix">
	<?php if (!empty($docs['class'])): ?>
	<div class="classes">
		<h3><?php echo __d('api_generator', 'Defined Classes'); ?></h3>
		<ul class="element-list">
		<?php foreach (array_keys($docs['class']) as $class): ?>
			<li class="class">
				<?php echo $this->Html->link($class, "#class-" . $class, array('class' => 'scroll-link')); ?>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
	
	<?php if (!empty($docs['function'])): ?>
	<div class="functions">
		<a id="top-functions"></a>
		<h3><?php echo __d('api_generator', 'Declared Functions'); ?></h3>
		<ul class="element-list">
		<?php foreach (array_keys($docs['function']) as $function): ?>
			<li class="function">
				<?php echo $this->Html->link($function, "#function-" . $function, array('class' => 'scroll-link')); ?>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>
</div>