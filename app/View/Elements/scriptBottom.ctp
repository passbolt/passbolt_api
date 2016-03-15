<?php echo $this->fetch('scriptBottom'); ?>
<?php echo $this->element('analytics/piwik'); ?>
<?php
// load devel materials.
if(Configure::read('debug') >= 2) {
	echo $this->element('devel/sqlTrace');
}
?>