<?php
/**
 * Method Detail element
 *
 */
echo $this->ApiUtils->element('before_method_detail');
?>
<?php foreach ($doc->methods as $method):
	if ($this->ApiDoc->excluded($method['access'], 'method')) :
		continue;
	endif;
	$definedInThis = ($method['declaredInClass'] == $doc->classInfo['name']);
?>
<div class="doc-block <?php echo $definedInThis ? '' : 'parent-method'; ?>">
	<a id="method-<?php echo $doc->name . $method['name']; ?>"></a>
	<div class="doc-head">
		<h2 class="<?php echo $this->ApiDoc->access($method); ?>"><?php echo $method['name']; ?></h2>
		<a class="top-link scroll-link" href="#top-<?php echo $doc->name; ?>"><?php echo __d('api_generator', 'top'); ?></a>
	</div>

	<div class="doc-body">
		<div class="markdown-block"><?php echo $this->ApiDoc->parse($method['comment']['description']); ?></div>
	<dl>
		<?php if (count($method['args'])): ?>
		<dt><?php echo __d('api_generator', 'Parameters:'); ?></dt>
		<dd>
			<ul class="argument-list">
				<?php foreach ($method['args'] as $name => $paramInfo): ?>
				<li>
					<div class="argument-properties">
						<span class="type"><?php echo $paramInfo['type']; ?></span>
						<span class="name">$<?php echo $name; ?></span>
						<span class="required"><?php echo $paramInfo['optional'] ? 'optional' : 'required' ?></span>
						<?php if ($paramInfo['optional'] == true): ?>
						<span class="default"><?php 
							echo ($paramInfo['hasDefault']) ? var_export($paramInfo['default'], true) : __d('api_generator', '(no default)'); ?>
						</span>
						<?php endif; ?>
					</div>
					<div class="markdown-block">
						<?php echo $this->ApiDoc->parse($paramInfo['comment']); ?>
					</div>
				</li>
				<?php endforeach; ?>
			</ul>
		</dd>
		<?php endif; ?>

		<dt><?php echo __d('api_generator', 'Method defined in:'); ?></dt>
		<dd><?php 
			echo $this->ApiDoc->fileLink($method['declaredInFile']);
			
			if ($this->ApiDoc->inClassIndex($method['declaredInClass'])):
				echo __d('api_generator', ' on line ');
				echo $this->Html->link($method['startLine'], array(
					'controller' => 'api_classes',
					'action' => 'view_source', 
					$this->ApiDoc->slug($method['declaredInClass']),
					'#' => 'line-'. $method['startLine']
				));
			endif;
		?> </dd>
		</dl>
		<?php echo $this->element('tag_block', array('tags' => $method['comment']['tags'])); ?>
	</div>
</div>
<?php
endforeach;
echo $this->ApiUtils->element('after_method_detail');
?>
