<?php
/**
 * Browse view.  Shows file listings and provides links to obtaining api docs from a file
 * Doubles as an ajax view by omitting certain tags when params['isAjax'] is set.
 */
$previousPath = explode('/', $previousPath);
$currentPath = explode('/', $currentPath);

$this->set('title_for_layout', $currentPath);
?>
<?php if (!$this->params['isAjax']): ?>
<h1 class="breadcrumb"><?php echo $this->element('breadcrumb'); ?></h1>
<ul id="file-list">
<?php endif; ?>

	<li class="folder previous-folder">
		<?php echo $this->Html->link(
			__d('api_generator', 'Up one folder'),
			array_merge(array('action' => 'source'), $previousPath)
		); ?>
	</li>
<?php foreach ($dirs as $dir): ?>
	<li class="folder">
		<?php echo $this->Html->link(
			$dir,
			array_merge(array('action' => 'source'), $currentPath,  array($dir))
		); ?>
	</li>
<?php endforeach; ?>
<?php if (!empty($files)): ?>
<?php foreach ($files as $file): ?>
	<li class="file">
		<?php echo $this->Html->link(
			$file,
			array_merge(array('action' => 'view_file'), $currentPath, array($file))
		); ?>
	</li>
<?php endforeach; ?>
<?php else: ?>
	<li class="file">
		<span><?php echo __d('api_generator', 'No files'); ?></span>
	</li>
<?php endif; ?>

<?php if (!$this->params['isAjax']): ?>
</ul>
<?php endif; ?>
