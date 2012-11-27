<?php
/**
 * Class information element
 *
 */
echo $this->ApiUtils->element('before_class_info');
?>
<a id="class-<?php echo $doc->name; ?>"></a>
<div class="doc-block class-info">
	<div class="doc-head"><h2><?php printf(__d('api_generator', '%s Class Info:'), $doc->name); ?></h2></div>
	<div class="doc-body">
	  <dl>
		<dt><?php echo __d('api_generator', 'Class Declaration:'); ?></dt>
		<dd><?php echo $this->ApiDoc->parse($doc->classInfo['classDescription']); ?></dd>
		
		<dt><?php echo __d('api_generator', 'File name:'); ?></dt>
		<dd><?php echo $this->ApiDoc->fileLink($doc->classInfo['fileName']); ?></dd>
		
		<dt><?php echo __d('api_generator', 'Description:'); ?></dt>
		<dd class="markdown-block"><?php echo $this->ApiDoc->parse($doc->classInfo['comment']['description']); ?></dd>
		
		<?php if (!empty($doc->classInfo['parents'])): ?>
		<dt><?php echo __d('api_generator', 'Class Inheritance'); ?></dt>
		<dd><?php echo $this->ApiDoc->inheritanceTree($doc->classInfo['parents']); ?></dd>
		<?php endif;?>
		
		<?php if (!empty($doc->classInfo['interfaces'])): ?>
		<dt><?php echo __d('api_generator', 'Interfaces Implemented'); ?></dt>
		<dd>
			<?php foreach ($doc->classInfo['interfaces'] as $interfaces): ?>
		        <?php echo $this->ApiDoc->classLink($interfaces); ?>
			<?php endforeach; ?>
		</dd>
		<?php endif;?>
		
	  </dl>
	  <?php echo $this->element('tag_block', array('tags' => $doc->classInfo['comment']['tags'])); ?>
	</div>
</div>
<?php echo $this->ApiUtils->element('after_class_info'); ?>