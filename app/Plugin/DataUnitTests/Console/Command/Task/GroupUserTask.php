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
			'id' => Common::uuid('group_user.id.management-dame'),
			'group_id' => Common::uuid('group.id.management'),
			'user_id' => Common::uuid('user.id.dame'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-2adc-4aa6-8005-2173c0a895dc',
			'group_id' => Common::uuid('group.id.accounting'),
			'user_id' => Common::uuid('user.id.grace'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-2dfc-45f7-85c7-2173c0a895dc',
			'group_id' => Common::uuid('group.id.human'),
			'user_id' => Common::uuid('user.id.irene'),
			'is_admin' => 0,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.human-marlyn'),
			'group_id' => Common::uuid('group.id.human'),
			'user_id' => Common::uuid('user.id.marlyn'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-33d8-4206-a198-2173c0a895dc',
			'group_id' => Common::uuid('group.id.developers'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-3694-4552-b45b-2173c0a895dc',
			'group_id' => Common::uuid('group.id.developers'),
			'user_id' => Common::uuid('user.id.betty'),
			'is_admin' => 0,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-3950-46a7-bce2-2173c0a895dc',
			'group_id' => Common::uuid('group.id.developers'),
			'user_id' => Common::uuid('user.id.carol'),
			'is_admin' => 0,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-3c0c-47c8-a8e6-2173c0a895dc',
			'group_id' => Common::uuid('group.id.developers_team_leads'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-3ec8-4c41-b813-2173c0a895dc',
			'group_id' => Common::uuid('group.id.developers_drupal'),
			'user_id' => Common::uuid('user.id.betty'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865f1f-4184-4b03-b78e-2173c0a895dc',
			'group_id' => Common::uuid('group.id.developers_drupal'),
			'user_id' => Common::uuid('user.id.carol'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865fa5-0624-49d5-a802-204fc0a895dc',
			'group_id' => Common::uuid('group.id.developers_cakephp'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865fa5-1178-4480-b80f-204fc0a895dc',
			'group_id' => Common::uuid('group.id.developers_cakephp'),
			'user_id' => Common::uuid('user.id.frances'),
			'is_admin' => 0,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865fa5-1179-4480-b80f-204fc0a895dc',
			'group_id' => Common::uuid('group.id.freelancers'),
			'user_id' => Common::uuid('user.id.frances'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865fa5-0624-49d5-a802-215fc0a895dc',
			'group_id' => Common::uuid('group.id.company_a'),
			'user_id' => Common::uuid('user.id.hedy'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865fa5-0625-49d5-a802-215fc0a895dc',
			'group_id' => Common::uuid('group.id.company_b'),
			'user_id' => Common::uuid('user.id.hedy'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865fa5-0625-49d6-a802-215fc0a895dc',
			'group_id' => Common::uuid('group.id.users'),
			'user_id' => Common::uuid('user.id.ada'),
			'is_admin' => 1,
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53865fa5-1178-4580-b80f-204fc0a995dc',
			'group_id' => Common::uuid('group.id.no_privilege'),
			'user_id' => Common::uuid('user.id.nancy'),
			'is_admin' => 1,
			'created' => '2016-02-02 18:59:05', 'modified' => '2016-02-02 18:59:05', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$gu[] = array('GroupUser' => array(
			'id' => '53866fb5-1178-4580-b80f-204fc0a995dc',
			'group_id' => Common::uuid('group.id.deleted'),
			'user_id' => Common::uuid('user.id.nancy'),
			'is_admin' => 1,
			'created' => '2016-02-02 18:59:05', 'modified' => '2016-02-02 18:59:05', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		return $gu;
	}
}
