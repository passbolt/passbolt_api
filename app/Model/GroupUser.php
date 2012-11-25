<?php
/**
 * GroupUser Model
 *
 * @copyright		 Copyright 2012, Passbolt.com
 * @package			 app.Model.GroupUser
 * @since				 version 2.12.7
 * @license			 http://www.passbolt.com/license
 */

App::uses('Group', 'Model');
App::uses('User', 'Model');

class GroupUser extends AppModel {

	public $useTable = "groups_users";

	public $belongsTo = array(
		'Group', 'User'
	);

	public $actsAs = array('Trackable');
}
