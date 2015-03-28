<?php
/**
 * Method Summary Element
 *
 */
echo $this->ApiUtils->element('before_method_summary');
$this->ApiUtils->sortByName($doc->methods); 
$title = (empty($isSearch)) ? __d('api_generator', 'Method Summary:') : __d('api_generator', 'Methods:');
?>
<div class="doc-block">
	<a id="top-<?php echo $doc->name; ?>"></a>
	<div class="doc-head"><h3><?php echo $title; ?></h3></div>
	<div class="doc-body">
<?php if (empty($isSearch)  && $doc->hasParentMethods()): ?>
		<span class="doc-controls">
			<a href="#" id="hide-parent-methods"><?php echo __d('api_generator', 'Show/Hide parent methods'); ?></a>
		</span>
<?php endif; ?>
		<ul class="method-summary">
			<?php foreach ($doc->methods as $method): ?>
				<?php 
				if ($this->ApiDoc->excluded($method['access'], 'method')) :
					continue;
				endif;
				$parent = ($method['declaredInClass'] == $doc->classInfo['name']) ? '' : 'parent-method'; 
				?>
				<li class="<?php echo $parent; ?>">
					<span class="<?php echo $this->ApiDoc->access($method); ?>">
					<?php
						if (empty($isSearch)):
							echo $this->Html->link($method['signature'],
								'#method-' . $doc->name . $method['name'],
								array('class' => 'scroll-link')
							);
						else:
							echo $this->Html->link($method['signature'],
								array('action' => 'view_class', $this->ApiDoc->slug($doc->name),
								'#' => 'method-' . $doc->name . $method['name']),
								array('class' => 'scroll-link')
							);
						endif;
					?>
					</span>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php echo $this->ApiUtils->element('after_method_summary'); ?>