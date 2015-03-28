<?php
/**
 * Package index sidebar element
 *
 */
?>
<h3><?php echo __d('api_generator', 'Package Index'); ?></h3>
<?php echo $this->ApiDoc->generatePackageTree($packageIndex); ?>