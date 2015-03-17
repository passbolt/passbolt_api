<?php
/**
 * Displays a File Listing Sidebar Hopefully filled with Ajax love.
 *
 */
$previousPath = explode('/', $previousPath);
$upOneFolder = explode('/', $upOneFolder);
?>
<h3><?php echo __d('api_generator', 'File browser'); ?></h3>
<ul id="file-browser">
	<li class="up-dir folder">
	<?php echo $this->Html->link(
		__d('api_generator', 'Up one folder'),
		array_merge(array('action' => 'source'), $upOneFolder)
	); ?>
	</li>
	<?php foreach ($dirs as $dir): ?>
		<li class="folder">
			<?php echo $this->Html->link(
				$dir,
				array_merge(array('action' => 'source'), $previousPath,  array($dir))
			); ?>
		</li>
	<?php endforeach; ?>
	
	<?php if (!empty($files)): ?>
		<?php foreach ($files as $file): ?>
			<li class="file">
				<?php echo $this->Html->link(
					$file,
					array_merge(array('action' => 'view_file'), $previousPath, array($file))
				); ?>
			</li>
		<?php endforeach; ?>
	<?php else: ?>
		<li class="file">
			<?php echo __d('api_generator', 'No files'); ?>
		</li>
	<?php endif; ?>
</ul>
