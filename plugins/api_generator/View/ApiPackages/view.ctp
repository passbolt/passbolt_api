<?php
$this->ApiDoc->setClassIndex($classIndex);
?>
<div class="api-package">
	<h1><?php printf(__d('api_generator', '%s Package'), $apiPackage['ApiPackage']['name']); ?></h1>

	<?php if(!empty($apiPackage['ParentPackage']['name'])): ?>
		<h3><?php echo __d('api_generator', 'Parent Package'); ?> </h3>
		<ul class="package-list">
			<li>
			<?php echo $this->ApiDoc->packageLink(
				$apiPackage['ParentPackage']['name'], 
				$this->ApiDoc->path($apiPackage['ParentPackage']['path'])
			); ?>
			</li>
		</ul>
	<?php endif; ?>

	<?php if (!empty($apiPackage['ChildPackage'])): ?>
		<h3><?php echo __d('api_generator', 'Child Packages'); ?></h3>
		<ul class="package-list">
		<?php foreach ($apiPackage['ChildPackage'] as $child): ?>
			<li>
			<?php echo $this->ApiDoc->packageLink(
				$child['name'], 
				$this->ApiDoc->path($child['path'])
			); ?>
			</li>
		<?php endforeach; ?>
		</ul>
	<?php endif;?>

	<h3><?php printf(__d('api_generator', 'Classes in %s'), $apiPackage['ApiPackage']['name']); ?> </h3>
	<ul class="package-list">
	<?php foreach ($apiPackage['ApiClass'] as $class): ?>
		<li><?php echo $this->ApiDoc->classLink($class['name']); ?></li>
	<?php endforeach; ?>
	</ul>
</div>