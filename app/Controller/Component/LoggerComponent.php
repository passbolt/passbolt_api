<?php
/**
 * Logger Component
 * Class used for logging the actions
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Controller.LoggerComponent
 * @since        version 2.12.12
 */
class LoggerComponent extends Component {

	// controller shortcut
	public $Controller;

/**
 * Initialize
 * @param object $controller Controller using this component
 * @return boolean Proceed with component usage (true), or fail (false)
 */
	public function initialize(&$controller, $settings=array()) {
		$this->Controller = &$controller;
		return true;
	}
}