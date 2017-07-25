<?php
/**
 * Insert GroupUser Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
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
		// Accounting
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
		// Board
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
		// Creative
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
		// Developer
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
		// Ergonom
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
		// Freelance
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
		// Human resource
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.human_resource-ping'),
			'group_id' => Common::uuid('group.id.human_resource'),
			'user_id' => Common::uuid('user.id.ping'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.human_resource-thelma'),
			'group_id' => Common::uuid('group.id.human_resource'),
			'user_id' => Common::uuid('user.id.thelma'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.human_resource-ursula'),
			'group_id' => Common::uuid('group.id.human_resource'),
			'user_id' => Common::uuid('user.id.ursula'),
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.human_resource-wang'),
			'group_id' => Common::uuid('group.id.human_resource'),
			'user_id' => Common::uuid('user.id.wang'),
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// IT support
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.it_support-ping'),
			'group_id' => Common::uuid('group.id.it_support'),
			'user_id' => Common::uuid('user.id.ping'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.it_support-thelma'),
			'group_id' => Common::uuid('group.id.it_support'),
			'user_id' => Common::uuid('user.id.thelma'),
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.it_support-ursula'),
			'group_id' => Common::uuid('group.id.it_support'),
			'user_id' => Common::uuid('user.id.ursula'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.it_support-wang'),
			'group_id' => Common::uuid('group.id.it_support'),
			'user_id' => Common::uuid('user.id.wang'),
			'is_admin' => 0,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Leadership team
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.leadership_team-admin'),
			'group_id' => Common::uuid('group.id.leadership_team'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Management
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.management-admin'),
			'group_id' => Common::uuid('group.id.management'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Marketing
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.marketing-admin'),
			'group_id' => Common::uuid('group.id.marketing'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Network
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.network-admin'),
			'group_id' => Common::uuid('group.id.network'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Operations
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.operations-admin'),
			'group_id' => Common::uuid('group.id.operations'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Procurement
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.procurement-admin'),
			'group_id' => Common::uuid('group.id.procurement'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Quality assurance
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.quality_assurance-admin'),
			'group_id' => Common::uuid('group.id.quality_assurance'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Resource planning
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.resource_planning-admin'),
			'group_id' => Common::uuid('group.id.resource_planning'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Sales
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.sales-admin'),
			'group_id' => Common::uuid('group.id.sales'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Traffic
		$gu[] = array('GroupUser' => array(
			'id' => Common::uuid('group_user.id.traffic-admin'),
			'group_id' => Common::uuid('group.id.traffic'),
			'user_id' => Common::uuid('user.id.admin'),
			'is_admin' => 1,
			'created' => '2016-01-29 13:39:25',
			'modified' => '2016-01-29 13:39:25',
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		return $gu;
	}
}
