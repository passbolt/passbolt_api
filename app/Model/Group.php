<?php
/**
 * Group  model
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Group
 * @since        version 2.12.7
 */
App::uses('User', 'Model');

class Group extends AppModel {

/**
 * Model behave as a tree with left, right, parent_id
 */
	public $actsAs = array('Tree', 'Containable', 'Trackable');

	// TODO : link it with GroupUser
	/*public $hasMany = array(
		'GroupUser'
	);*/

}
