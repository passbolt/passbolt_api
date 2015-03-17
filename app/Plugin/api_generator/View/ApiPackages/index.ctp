<h1><?php echo __d('api_generator', 'Packages'); ?></h1>
<div id="main-package-index">
	<?php echo $this->ApiDoc->generatePackageTree($packageIndex); ?> 
</div>