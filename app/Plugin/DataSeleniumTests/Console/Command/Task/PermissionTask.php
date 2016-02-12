<?php
/**
 * Insert Permission Task.
 * This is a temporary file that replaces the permissions on group and categories.
 * This is following the decision to scale down the number of features for the release.
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.PermissionTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');

class PermissionTask extends ModelTask {

	public $model = 'Permission';

	protected function getData() {
		$permissions = array(
			Common::uuid('resource.id.apache') => array(
				Common::uuid('user.id.ada') => PermissionType::OWNER,
				Common::uuid('user.id.betty') => PermissionType::UPDATE,
				Common::uuid('user.id.carol') => PermissionType::READ,
				Common::uuid('user.id.dame') => PermissionType::READ,
				//Common::uuid('user.id.edith') => PermissionType::DENY,
			),
			Common::uuid('resource.id.april') => array(
				//Common::uuid('user.id.ada') => PermissionType::DENY,
				Common::uuid('user.id.betty') => PermissionType::OWNER,
				Common::uuid('user.id.carol') => PermissionType::UPDATE,
				Common::uuid('user.id.dame') => PermissionType::READ,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.bower') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				//Common::uuid('user.id.betty') => PermissionType::DENY,
				Common::uuid('user.id.carol') => PermissionType::OWNER,
				Common::uuid('user.id.dame') => PermissionType::UPDATE,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.centos') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				Common::uuid('user.id.betty') => PermissionType::READ,
				//Common::uuid('user.id.carol') => PermissionType::DENY,
				Common::uuid('user.id.dame') => PermissionType::OWNER,
				Common::uuid('user.id.edith') => PermissionType::UPDATE,
			),
			Common::uuid('resource.id.canjs') => array(
				Common::uuid('user.id.ada') => PermissionType::UPDATE,
				Common::uuid('user.id.betty') => PermissionType::READ,
				Common::uuid('user.id.carol') => PermissionType::READ,
				//Common::uuid('user.id.dame') => PermissionType::DENY,
				Common::uuid('user.id.edith') => PermissionType::OWNER,
			),
			Common::uuid('resource.id.cakephp') => array(
				Common::uuid('user.id.ada') => PermissionType::OWNER,
				Common::uuid('user.id.betty') => PermissionType::UPDATE,
				Common::uuid('user.id.carol') => PermissionType::READ,
				Common::uuid('user.id.dame') => PermissionType::READ,
				//Common::uuid('user.id.edith') => PermissionType::DENY,
			),
			Common::uuid('resource.id.chai') => array(
				//Common::uuid('user.id.ada') => PermissionType::DENY,
				Common::uuid('user.id.betty') => PermissionType::OWNER,
				Common::uuid('user.id.carol') => PermissionType::UPDATE,
				Common::uuid('user.id.dame') => PermissionType::READ,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.composer') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				//Common::uuid('user.id.betty') => PermissionType::DENY,
				Common::uuid('user.id.carol') => PermissionType::OWNER,
				Common::uuid('user.id.dame') => PermissionType::UPDATE,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.debian') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				Common::uuid('user.id.betty') => PermissionType::READ,
				//Common::uuid('user.id.carol') => PermissionType::DENY,
				Common::uuid('user.id.dame') => PermissionType::OWNER,
				Common::uuid('user.id.edith') => PermissionType::UPDATE,
			),
			Common::uuid('resource.id.docker') => array(
				Common::uuid('user.id.ada') => PermissionType::UPDATE,
				Common::uuid('user.id.betty') => PermissionType::READ,
				Common::uuid('user.id.carol') => PermissionType::READ,
				//Common::uuid('user.id.dame') => PermissionType::DENY,
				Common::uuid('user.id.edith') => PermissionType::OWNER,
			),
			Common::uuid('resource.id.enlightenment') => array(
				Common::uuid('user.id.ada') => PermissionType::OWNER,
				Common::uuid('user.id.betty') => PermissionType::UPDATE,
				Common::uuid('user.id.carol') => PermissionType::READ,
				Common::uuid('user.id.dame') => PermissionType::READ,
				//Common::uuid('user.id.edith') => PermissionType::DENY,
			),
			Common::uuid('resource.id.fosdem') => array(
				//Common::uuid('user.id.ada') => PermissionType::DENY,
				Common::uuid('user.id.betty') => PermissionType::OWNER,
				Common::uuid('user.id.carol') => PermissionType::UPDATE,
				Common::uuid('user.id.dame') => PermissionType::READ,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.framasoft') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				//Common::uuid('user.id.betty') => PermissionType::DENY,
				Common::uuid('user.id.carol') => PermissionType::OWNER,
				Common::uuid('user.id.dame') => PermissionType::UPDATE,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.fsfe') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				Common::uuid('user.id.betty') => PermissionType::READ,
				//Common::uuid('user.id.carol') => PermissionType::DENY,
				Common::uuid('user.id.dame') => PermissionType::OWNER,
				Common::uuid('user.id.edith') => PermissionType::UPDATE,
			),
			Common::uuid('resource.id.ftp') => array(
				Common::uuid('user.id.ada') => PermissionType::UPDATE,
				Common::uuid('user.id.betty') => PermissionType::READ,
				Common::uuid('user.id.carol') => PermissionType::READ,
				//Common::uuid('user.id.dame') => PermissionType::DENY,
				Common::uuid('user.id.edith') => PermissionType::OWNER,
			),
			Common::uuid('resource.id.grogle') => array(
				Common::uuid('user.id.ada') => PermissionType::OWNER,
				Common::uuid('user.id.betty') => PermissionType::UPDATE,
				Common::uuid('user.id.carol') => PermissionType::READ,
				Common::uuid('user.id.dame') => PermissionType::READ,
				//Common::uuid('user.id.edith') => PermissionType::DENY,
			),
			Common::uuid('resource.id.grunt') => array(
				//Common::uuid('user.id.ada') => PermissionType::DENY,
				Common::uuid('user.id.betty') => PermissionType::OWNER,
				Common::uuid('user.id.carol') => PermissionType::UPDATE,
				Common::uuid('user.id.dame') => PermissionType::READ,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.gnupg') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				//Common::uuid('user.id.betty') => PermissionType::DENY,
				Common::uuid('user.id.carol') => PermissionType::OWNER,
				Common::uuid('user.id.dame') => PermissionType::UPDATE,
				Common::uuid('user.id.edith') => PermissionType::READ,
			),
			Common::uuid('resource.id.git') => array(
				Common::uuid('user.id.ada') => PermissionType::READ,
				Common::uuid('user.id.betty') => PermissionType::READ,
				//Common::uuid('user.id.carol') => PermissionType::DENY,
				Common::uuid('user.id.dame') => PermissionType::OWNER,
				Common::uuid('user.id.edith') => PermissionType::UPDATE,
			),
			Common::uuid('resource.id.inkscape') => array(
				Common::uuid('user.id.ada') => PermissionType::UPDATE,
				Common::uuid('user.id.betty') => PermissionType::READ,
				Common::uuid('user.id.carol') => PermissionType::READ,
				//Common::uuid('user.id.dame') => PermissionType::DENY,
				Common::uuid('user.id.edith') => PermissionType::OWNER,
			)
		);

		$ps = array();

		foreach($permissions as $resourceID => $users) {
				foreach($users as $userID => $permission) {
				$ps[] = array('Permission' => array(
					'id' => Common::uuid('permission.id.'.$resourceID.'-'.$userID),
					'aco' => 'Resource',
					'aco_foreign_key' => $resourceID,
					'aro' => 'User',
					'aro_foreign_key' => $userID,
					'type' => $permission
				));
			}
		}

		return $ps;
	}
}
