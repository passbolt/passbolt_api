<?php
/**
 * Insert Group Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
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
			'id' => Common::uuid('group.id.accounting'),
			'name' => 'Accounting',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.board'),
			'name' => 'Board',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.creative'),
			'name' => 'Creative',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.developer'),
			'name' => 'Developer',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.ergonom'),
			'name' => 'Ergonom',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.freelancer'),
			'name' => 'Freelancer',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.human_resource'),
			'name' => 'Human resource',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.it_support'),
			'name' => 'IT support',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.leadership_team'),
			'name' => 'Leadership team',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.management'),
			'name' => 'Management',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.marketing'),
			'name' => 'Marketing',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.network'),
			'name' => 'Network',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.operations'),
			'name' => 'Operations',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.procurement'),
			'name' => 'Procurement',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.quality_assurance'),
			'name' => 'Quality assurance',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.resource_planning'),
			'name' => 'Resource planning',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.sales'),
			'name' => 'Sales',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$g[] = array('Group' => array(
			'id' => Common::uuid('group.id.traffic'),
			'name' => 'Traffic',
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
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
