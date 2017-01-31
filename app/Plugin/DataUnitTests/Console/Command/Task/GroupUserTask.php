<?php
/**
 * Insert GroupUser Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataUnitTests.Console.Command.Task.GroupUserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');
App::uses('Group', 'Model');
App::uses('GroupUser', 'Model');

class GroupUserTask extends ModelTask {

	public $model = 'GroupUser';
	
	protected function getData() {
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.accounting-frances'),
			'group_id' => Common::uuid('group.id.accounting'),
			'user_id' => Common::uuid('user.id.frances'),
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.administration-grace'),
			'group_id' => Common::uuid('group.id.administration'),
			'user_id' => Common::uuid('user.id.grace'),
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.administration-hedy'),
			'group_id' => Common::uuid('group.id.administration'),
			'user_id' => Common::uuid('user.id.hedy'),
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		return $gu;
	}
}
