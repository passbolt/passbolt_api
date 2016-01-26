<!-- Test piwik -->
<?php
/**
 * Script bottom element
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.View.Elements.scriptBottom
 * @since        version 2.12.9
 */
	echo $this->fetch('scriptBottom');
  if (!Configure::read('debug')) :
?>
	  <?php echo $this->element('analytics/piwik'); ?>
<?php endif; ?>
