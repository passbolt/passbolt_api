<?php
/**
 * Recursive Listing of all allowed files.
 *
 */
$this->set('title_for_layout', __d('api_generator', 'All Files'));
?>
<h1><?php echo __d('api_generator', 'All files')?></h1>

<ul id="file-list">
<?php if (!empty($files)): ?>
<?php foreach ($files as $file): ?>
	<li class="file">
		<?php echo $this->ApiDoc->fileLink($file); ?>
	</li>
<?php endforeach; ?>
<?php else: ?>
	<li class="file">
		<?php echo __d('api_generator', 'No files'); ?>
	</li>
<?php endif; ?>
</ul>