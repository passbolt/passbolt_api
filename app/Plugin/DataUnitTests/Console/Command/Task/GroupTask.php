<?php
/**
 * Insert Group Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
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
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.no_privilege'),
			'name' => 'no privilege',
			'created' => '2016-02-02 18:59:05', 'modified' => '2016-02-02 18:59:05', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.deleted'),
			'name' => 'deleted',
			'deleted' => 1,
			'created' => '2016-02-02 18:59:05', 'modified' => '2016-02-02 18:59:05', 'created_by' => Common::uuid('user.id.anonymous'), 'modified_by' => Common::uuid('user.id.anonymous')
		));

		return $g;
	}
}
