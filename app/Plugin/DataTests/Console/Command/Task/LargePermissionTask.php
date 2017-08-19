<?php
/**
 * Insert Permission Task.
 * This is a temporary file that replaces the permissions on group and categories.
 * This is following the decision to scale down the number of features for the release.
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataTests.Console.Command.Task.PermissionTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Permission', 'Model');
App::uses('PermissionType', 'Model');
App::uses('PermissionMatrix', 'DataTests.Data');

class LargePermissionTask extends ModelTask {

	public $model = 'Permission';

	protected function getData() {
		$ps = [];

		$Resource = $this->_getModel('Resource');
		$Resource->Behaviors->disable('Permissionable'); // cannot do a findAll otherwise
		$resources = $Resource->find('all');

		// All resources should be shared with the group Root.
		$groupId = Common::uuid('group.id.root');
		foreach ($resources as $resource) {

			//var_dump('Insert permission ' . $expectedPermissionType . ' to access ' . $resourceAlias . ' for ' . $groupAlias);
			$acoId = $resource['Resource']['id'];
			$aroId = $groupId;
			$ps[] = array('Permission' => array(
				'id' => Common::uuid('permission.id.'.$acoId.'-'.$aroId),
				'aco' => 'Resource',
				'aco_foreign_key' => $acoId,
				'aro' => 'Group',
				'aro_foreign_key' => $aroId,
				'type' => PermissionType::READ,
				'created_by' => Common::uuid('user.id.admin'),
				'modified_by' => Common::uuid('user.id.admin')
			));
		}

		return $ps;
	}
}
