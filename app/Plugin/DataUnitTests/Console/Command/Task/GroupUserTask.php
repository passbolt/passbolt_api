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
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.accounting-grace'),
			'group_id' => Common::uuid('group.id.accounting'),
			'user_id' => Common::uuid('user.id.grace'),
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.board-hedy'),
			'group_id' => Common::uuid('group.id.board'),
			'user_id' => Common::uuid('user.id.hedy'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.creative-irene'),
			'group_id' => Common::uuid('group.id.creative'),
			'user_id' => Common::uuid('user.id.irene'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.anonymous'),
			'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.developer-irene'),
			'group_id' => Common::uuid('group.id.developer'),
			'user_id' => Common::uuid('user.id.irene'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.ergonom-irene'),
			'group_id' => Common::uuid('group.id.ergonom'),
			'user_id' => Common::uuid('user.id.irene'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.freelancer-jean'),
			'group_id' => Common::uuid('group.id.freelancer'),
			'user_id' => Common::uuid('user.id.jean'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.freelancer-kathleen'),
			'group_id' => Common::uuid('group.id.freelancer'),
			'user_id' => Common::uuid('user.id.kathleen'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.freelancer-lynne'),
			'group_id' => Common::uuid('group.id.freelancer'),
			'user_id' => Common::uuid('user.id.lynne'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.freelancer-marlyn'),
			'group_id' => Common::uuid('group.id.freelancer'),
			'user_id' => Common::uuid('user.id.marlyn'),
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.freelancer-nancy'),
			'group_id' => Common::uuid('group.id.freelancer'),
			'user_id' => Common::uuid('user.id.nancy'),
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.human_resource-nancy'),
			'group_id' => Common::uuid('group.id.human_resource'),
			'user_id' => Common::uuid('user.id.nancy'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.management-ada'),
			'group_id' => Common::uuid('group.id.management'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.marketing-ada'),
			'group_id' => Common::uuid('group.id.marketing'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.network-frances'),
			'group_id' => Common::uuid('group.id.network'),
			'user_id' => Common::uuid('user.id.frances'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.sales-ada'),
			'group_id' => Common::uuid('group.id.sales'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.traffic-ada'),
			'group_id' => Common::uuid('group.id.traffic'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.deleted-ada'),
			'group_id' => Common::uuid('group.id.deleted'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		return $gu;
	}
}
