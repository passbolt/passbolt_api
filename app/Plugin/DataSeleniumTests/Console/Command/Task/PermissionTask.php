<?php
/**
 * Insert Permission Task.
 * This is a temporary file that replaces the permissions on group and categories.
 * This is following the decision to scale down the number of features for the release.
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.PermissionTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');

class PermissionTask extends ModelTask {

	public $model = 'Permission';

	protected function getData() {
		$this->Resource = ClassRegistry::init('Resource');
		$UserTask = $this->Tasks->load('DataUnitTests.User');
		$users = $UserTask::getAlias();

		$permissions = array(
			'adm' => array(
				'facebook account' => PermissionType::ADMIN,
				'bank password' => PermissionType::ADMIN,
				'salesforce account' => PermissionType::ADMIN,
				'tetris license' => PermissionType::ADMIN,
				'shared resource' => PermissionType::ADMIN,
			),
			'lyn' => array(
				'facebook account' => PermissionType::ADMIN,
				'bank password' => PermissionType::ADMIN,
				'salesforce account' => PermissionType::ADMIN,
				'tetris license' => PermissionType::ADMIN,
				'shared resource' => PermissionType::ADMIN,
			),
			'kay' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'edi' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'roo' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'dam' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'ada' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'gra' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'mar' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'ire' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'bet' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'car' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'fra' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'jea' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'usr' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
			'hed' => array(
				'facebook account' => PermissionType::READ,
				'salesforce account' => PermissionType::READ,
				'tetris license' => PermissionType::UPDATE,
				'shared resource' => PermissionType::ADMIN,
			),
		);

		$ps = array();

		// Give access to 4 passwords to each user.
		foreach($permissions as $userAlias => $perms) {
			foreach($perms as $resourceName => $permissionType) {
				$resource = $this->Resource->findByName($resourceName);
				$ps[] = array('Permission' => array(
					'id' => Common::uuid(),
					'aco' => 'Resource',
					'aco_foreign_key' => $resource['Resource']['id'],
					'aro' => 'User',
					'aro_foreign_key' => $users[$userAlias],
					'type' => $permissionType
				));
			}
		}

		return $ps;
	}
}
