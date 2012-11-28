<?php
/**
 * Function documentation element
 *
 */
?>
<a id="function-<?php echo $doc->name; ?>"></a>
<div class="function-info">
	<div class="doc-head">
		<h2><?php echo $doc->name; ?></h2>
		<a class="top-link scroll-link" href="#top-functions"><?php echo __d('api_generator', 'top'); ?></a>
	</div>

	<div class="doc-body">
		<div class="markdown-block"><?php echo $this->ApiDoc->parse($doc->info['comment']['description']); ?></div>
		<dl>
			<?php if (count($doc->params)): ?>
			<dt><?php echo __d('api_generator', 'Parameters:'); ?></dt>
			<dd>
				<ul class="argument-list">
					<?php foreach ($doc->params as $name => $paramInfo): ?>
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
				echo $this->ApiDoc->fileLink($doc->info['declaredInFile']);
				$pseudoClass = basename($doc->info['declaredInFile']);
				if ($this->ApiDoc->inClassIndex($pseudoClass)):
					__d('api_generator', ' on line ');
					echo $this->Html->link($doc->info['startLine'], array(
						'controller' => 'api_classes',
						'action' => 'view_source', 
						$pseudoClass,
						'#' => 'line-'. $doc->info['startLine']
					));
				endif;
			?> </dd>
			</dl>
	<dl>
	<?php 
		unset($doc->info['comment']['tags']['param']);
		echo $this->element('tag_block', array('tags' => $doc->info['comment']['tags'])); 
	?>
	</div>
</div>
