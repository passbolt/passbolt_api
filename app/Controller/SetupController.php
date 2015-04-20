<?php
/**
 * Setup Controller
 *
 * @copyright   Copyright 2015, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Controller.SetupController
 * @since       version 2.12.9
 */
class SetupController extends AppController {
	public $helpers = array();
	public $components = array();

/**
 * Install the plugin for the first time.
 *
 * @param string $token
 */
	public function install($token = null, $step = null) {
		$this->layout = 'html5';
	}
}
