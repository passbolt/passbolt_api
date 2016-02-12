<?php
/**
 * Home Page
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
	$this->assign('title', __('Passbolt - The simple password management system'));

	$this->Html->css('main.min', null, array('inline' => false));

	// load the front end application
	if(!Configure::read('debug')) {
		$frontUri = 'steal/steal.production.js?app/passbolt.js';
	} else {
		$frontUri = 'steal/steal.js?app/passbolt.js';
	}

?>
<?php echo $this->element('loader'); ?>
<div id="js_app_controller">
</div>
