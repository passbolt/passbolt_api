<?php
/**
 * Permission  model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Permission
 * @since        version 2.12.7
 */
App::uses('User', 'Model');

class Permission extends AppModel {

	public $actsAs = array('Containable', 'Trackable');

}
