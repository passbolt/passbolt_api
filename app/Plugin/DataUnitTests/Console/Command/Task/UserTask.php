<?php
/**
 * Insert User Task
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 * @package      app.plugins.DataExtras.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class UserTask extends ModelTask {

	public $model = 'User';
	
	protected function getData() {
		// anonymous user
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.anonymous'),
			'username' => 'anonymous@passbolt.com',
			'role_id' => Common::uuid('role.id.anonymous'),
			'password' => 'password',
			'active' => 1
		));

		// one user per default roles
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.guest'),
			'username' => 'guest@passbolt.com',
			'role_id' => Common::uuid('role.id.anonymous'),
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.user'),
			'username' => 'user@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.admin'),
			'username' => 'admin@passbolt.com',
			'role_id' => Common::uuid('role.id.admin'),
			'password' => 'password',
			'active' => 1,
			'created' => date('Y-m-d H:i:s', strtotime('-2 years')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 years')),
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.root'),
			'username' => 'root@passbolt.com',
			'role_id' => Common::uuid('role.id.root'),
			'password' => 'password',
			'active' => 1
		));

		// famous scientists
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.ada'),
			'username' => 'ada@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'password' => 'password',
			'active' => 1,
			'created' => date('Y-m-d H:i:s', strtotime('-2 months')),
			'modified' => date('Y-m-d H:i:s', strtotime('-1 months')),
		));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.betty'),
            'username' => 'betty@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 weeks')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 weeks')),
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.carol'),
            'username' => 'carol@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 days')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 days')),
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.dame'),
            'username' => 'dame@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 hours')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 hours')),
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.edith'),
            'username' => 'edith@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1,
            'created' => date('Y-m-d H:i:s', strtotime('-2 minutes')),
            'modified' => date('Y-m-d H:i:s', strtotime('-1 minutes')),
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.frances'),
            'username' => 'frances@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.grace'),
            'username' => 'grace@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.hedy'),
            'username' => 'hedy@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.irene'),
            'username' => 'irene@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.jean'),
            'username' => 'jean@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.kathleen'),
            'username' => 'kathleen@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => Common::uuid('user.id.lynne'),
            'username' => 'lynne@passbolt.com',
            'role_id' => Common::uuid('role.id.user'),
            'password' => 'password',
            'active' => 1
        ));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.marlyn'),
			'username' => 'marlyn@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => Common::uuid('user.id.nancy'),
			'username' => 'nancy@passbolt.com',
			'role_id' => Common::uuid('role.id.user'),
			'password' => 'password',
			'active' => 1
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
