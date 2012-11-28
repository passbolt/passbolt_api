<?php
/**
 * Properties Element
 *
 */
echo $this->ApiUtils->element('before_properties');
$this->ApiUtils->sortByName($doc->properties);

if (empty($doc->properties)) {
	return;
}
?>
<div class="doc-block">
	<div class="doc-head"><h3><?php echo __d('api_generator', 'Properties:'); ?></h3></div>
	<div class="doc-body">
	<?php if (!empty($doc->properties)): ?>
<?php if (empty($isSearch) && $doc->hasParentProperties()): ?>
		<span class="doc-controls">
			<a href="#" id="hide-parent-properties"><?php echo __d('api_generator', 'Show/Hide parent properties'); ?></a>
		</span>
<?php endif; ?>
		<ul class="property-list">
			<?php foreach ($doc->properties as $prop):
				if ($this->ApiDoc->excluded($prop['access'], 'property')) :
					continue;
				endif;
				$definedInThis = ($prop['declaredInClass'] == $doc->classInfo['name']);
				$classname = ($definedInThis ? '' : 'parent-property');
				?>
				<li class="<?php echo $classname; ?>">
					<h3 class="access <?php echo $prop['access']; ?>">
						<?php echo $prop['name']; ?>
					<?php
					if (!empty($prop['comment']['tags']['var'])):
						printf('<span class="property-type">%s</span>', $prop['comment']['tags']['var']);
					endif;
					?>
					</h3>
					<div class="markdown-block">
					<?php echo $this->ApiDoc->parse($prop['comment']['description']); ?>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
	</div>
</div>
<?php echo $this->ApiUtils->element('after_properties'); ?>