<?php
/**
 * Insert Permission Task.
 * This is a temporary file that replaces the permissions on group and categories.
 * This is following the decision to scale down the number of features for the release.
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.PermissionTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');
App::uses('PermissionMatrix', 'DataSeleniumTests.Data');

class PermissionTask extends ModelTask {

	public $model = 'Permission';

	protected function getData() {
		$ps = [];

		$matrixPath = App::pluginPath('DataSeleniumTests') . '/Data/users_resources_permissions.csv';
		$permissionMatrix = PermissionMatrix::importCsv($matrixPath);
		foreach ($permissionMatrix as $resourceAlias => $usersExpectedPermissions) {
			$userResourcePermissions[Common::uuid('resource.id.' . $resourceAlias)] = [];
			foreach ($usersExpectedPermissions as $userAlias => $expectedPermissionType) {
				if ($expectedPermissionType == '0') {
					continue;
				}
				//var_dump('Insert permission ' . $expectedPermissionType . ' to access ' . $resourceAlias . ' for ' . $userAlias);
				$acoId = Common::uuid('resource.id.' . $resourceAlias);
				$aroId = Common::uuid('user.id.' . $userAlias);
				$ps[] = array('Permission' => array(
					'id' => Common::uuid('permission.id.'.$acoId.'-'.$aroId),
					'aco' => 'Resource',
					'aco_foreign_key' => $acoId,
					'aro' => 'User',
					'aro_foreign_key' => $aroId,
					'type' => $expectedPermissionType,
					'created_by' => Common::uuid('user.id.admin'),
					'modified_by' => Common::uuid('user.id.admin')
				));
			}
		}

		$matrixPath = App::pluginPath('DataSeleniumTests') . '/Data/groups_resources_permissions.csv';
		$groupPermissionMatrix = PermissionMatrix::importCsv($matrixPath);
		foreach ($groupPermissionMatrix as $resourceAlias => $groupsExpectedPermissions) {
			// Retrieve the direct users permissions defined for the resource
			$groupResourcePermissions[$resourceAlias] = [];
			foreach ($groupsExpectedPermissions as $groupAlias => $expectedPermissionType) {
				if ($expectedPermissionType == '0') {
					continue;
				}
				//var_dump('Insert permission ' . $expectedPermissionType . ' to access ' . $resourceAlias . ' for ' . $groupAlias);
				$acoId = Common::uuid('resource.id.' . $resourceAlias);
				$aroId = Common::uuid('group.id.' . $groupAlias);
				$ps[] = array('Permission' => array(
					'id' => Common::uuid('permission.id.'.$acoId.'-'.$aroId),
					'aco' => 'Resource',
					'aco_foreign_key' => $acoId,
					'aro' => 'Group',
					'aro_foreign_key' => $aroId,
					'type' => $expectedPermissionType,
					'created_by' => Common::uuid('user.id.admin'),
					'modified_by' => Common::uuid('user.id.admin')
				));
				$groupResourcePermissions[$resourceAlias][Common::uuid('group.id.' . $groupAlias)] = $expectedPermissionType;
			}
		}

		return $ps;
	}
}
