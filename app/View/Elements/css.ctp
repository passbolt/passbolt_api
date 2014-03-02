<?php
/**
 * CSS Elements
 *
 * @copyright     copyright 2012 passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.View.Elements.css
 * @since         version 2.12.6
 */

  if (Configure::read('debug') > 1) {
	$this->Html->css('default/debug.min', null, array('inline' => false));
  }
?>
<!--  <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Lato" />-->
<?php echo $this->fetch('css'); ?>
