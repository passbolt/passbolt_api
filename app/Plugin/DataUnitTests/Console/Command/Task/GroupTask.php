<?php
/**
 * Insert Group Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataUnitTests.Console.Command.Task.GroupTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('Group', 'Model');

class GroupTask extends ModelTask {

	public $model = "Group";

	protected function getData() {
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.management'),
			'name' => 'management',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.accounting'),
			'name' => 'accounting dpt',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.human'),
			'name' => 'human resources',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.developers'),
			'name' => 'developers',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.developers_team_leads'),
			'name' => 'developers team leads',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		// $g[] = array('Group' => array(
			// 'id' => '5ad56042-c5cd-11e1-a0c5-080038896c4c',
			// 'name' => 'developers team leads junior',
			// 'created' => '2013-01-24 13:39:25', 'modified' => '2013-01-24 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		// ));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.developers_drupal'),
			'name' => 'developers drupal',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.developers_cakephp'),
			'name' => 'developers cakephp',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.freelancers'),
			'name' => 'freelancers',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.company_a'),
			'name' => 'company a',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.company_b'),
			'name' => 'company b',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.users'),
			'name' => 'Users',
			'created' => '2012-12-17 13:39:25', 'modified' => '2012-07-04 13:39:25', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		return $g;
	}
}
