<?php
/**
 * Insert User Task
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataExtras.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class UserTask extends ModelTask {

	public $model = 'User';
	
	protected function getData() {
		// admin
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.admin'),
			'username' => 'admin@passbolt.com',
			'role_id' => Common::uuid('role.id.admin'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		// anonymous user
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.anonymous'),
			'username' => 'anonymous@passbolt.com',
			'role_id' => Common::uuid('role.id.anonymous'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// one user per default roles
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.user'),
			'username' => 'user@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.admin'),
			'username' => 'admin@passbolt.com',
			'role_id' => Common::uuid('role.id.admin'),
			'active' => 1,
			'created' => date('Y-m-d H:i:s', strtotime('-2 years')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 years')),
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.root'),
			'username' => 'root@passbolt.com',
			'role_id' => Common::uuid('role.id.root'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// famous scientists
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.ada'),
			'username' => 'ada@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'created' => date('Y-m-d H:i:s', strtotime('-2 months')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 months')),
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.betty'),
            'username' => 'betty@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 weeks')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 weeks')),
			'created_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.carol'),
            'username' => 'carol@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.dame'),
            'username' => 'dame@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 hours')),
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.edith'),
            'username' => 'edith@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 minutes')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 minutes')),
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.frances'),
            'username' => 'frances@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.grace'),
            'username' => 'grace@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.hedy'),
            'username' => 'hedy@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.irene'),
            'username' => 'irene@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.jean'),
            'username' => 'jean@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.kathleen'),
            'username' => 'kathleen@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.lynne'),
            'username' => 'lynne@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
        ));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.marlyn'),
			'username' => 'marlyn@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.nancy'),
			'username' => 'nancy@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.orna'),
			'username' => 'orna@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.ping'),
			'username' => 'ping@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.ruth'),
			'username' => 'ruth@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.sofia'),
			'username' => 'sofia@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.thelma'),
			'username' => 'thelma@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.ursula'),
			'username' => 'ursula@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.wang'),
			'username' => 'wang@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		// Users with no access
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.orna'),
			'username' => 'orna@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 0,
			'deleted' => 0,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.ruth'),
			'username' => 'ruth@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 0,
			'deleted' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.sofia'),
			'username' => 'sofia@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'active' => 1,
			'deleted' => 1,
			'created_by' => Common::uuid('user.id.admin'),
			'modified_by' => Common::uuid('user.id.admin')
		));

		return $us;
	}

	protected function getValidationFields($item) {
		return [
			'id',
			'username',
			'active',
			'created_by'
		];
	}
}
