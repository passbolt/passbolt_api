<?php
/**
 * Insert User Task
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.Task.UserTask
 * @since        version 2.12.11
 */

require_once(ROOT . DS . APP_DIR . DS . 'Console' . DS . 'Command' . DS . 'Task' . DS . 'ModelTask.php');

App::uses('User', 'Model');

class UserTask extends ModelTask {

	public $model = 'User';

	public static function getAlias() {
		$User = ClassRegistry::init('User');
		$aliases = array (
			// anonymous user
			'ano' => $User->findByUsername('anonymous@passbolt.com'),

			// one user per default roles
			'gue' => $User->findByUsername('guest@passbolt.com'),
			'usr' => $User->findByUsername('user@passbolt.com'),
			'adm' => $User->findByUsername('admin@passbolt.com'),
			'roo' => $User->findByUsername('root@passbolt.com'),

			// famous scientists
			'ada' => $User->findByUsername('ada@passbolt.com'),
            'bet' => $User->findByUsername('betty@passbolt.com'),
            'car' => $User->findByUsername('carol@passbolt.com'),
            'dam' => $User->findByUsername('dame@passbolt.com'),
            'edi' => $User->findByUsername('edith@passbolt.com'),
            'fra' => $User->findByUsername('frances@passbolt.com'),
            'gra' => $User->findByUsername('grace@passbolt.com'),
            'hed' => $User->findByUsername('hedy@passbolt.com'),
            'ire' => $User->findByUsername('irene@passbolt.com'),
            'jea' => $User->findByUsername('jean@passbolt.com'),
            'kay' => $User->findByUsername('kathleen@passbolt.com'),
            'lyn' => $User->findByUsername('lynne@passbolt.com'),
            'mar' => $User->findByUsername('marlyn@passbolt.com'),

		);
		foreach ($aliases as $name=>$obj){
			$aliases[$name] = $obj['User']['id'];
		}
		return $aliases;
	}
	
	protected function getData() {
		// anonymous user
		$us[] = array('User' => array(
			'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4c',
			'username' => 'anonymous@passbolt.com',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));

		// one user per default roles
		$us[] = array('User' => array(
			'id' => '533d3564-03e8-4963-94a7-178cc0a895dc',
			'username' => 'guest@passbolt.com',
			'role_id' => '0208f3a4-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => '50cdab9c-4380-4eb6-b4cc-2f4fd7a10fce',
			'username' => 'user@passbolt.com',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => '533d37a0-bc80-4945-9b11-1663c0a895dc',
			'username' => 'admin@passbolt.com',
			'role_id' => '142c1188-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));
		$us[] = array('User' => array(
			'id' => 'dada6042-c5cd-11e1-a0c5-080027796c51',
			'username' => 'root@passbolt.com',
			'role_id' => '142c1340-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));

		// famous scientists
		$us[] = array('User' => array(
			'id' => '50cdea9c-a34c-406f-a9f1-2f4fd7a10fce',
			'username' => 'ada@passbolt.com',
			'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
			'password' => 'password',
			'active' => 1
		));
        $us[] = array('User' => array(
            'id' => '50cdea9c-aa88-46cb-a09b-2f4fd7a10fce',
            'username' => 'betty@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '50cdea9c-7e80-4eb6-b4cc-2f4fd7a10fce',
            'username' => 'carol@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => 'dada6042-c5cd-11e1-a0c5-080027796c4c',
            'username' => 'dame@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => 'bbd56042-c5cd-11e1-a0c5-080027796c4e',
            'username' => 'edith@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '533d346d-d378-4acc-affd-1663c0a895dc',
            'username' => 'frances@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '50cdea9c-f214-4549-9807-2f4fd7a10fce',
            'username' => 'grace@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '533d37a0-aa11-4945-9b11-1663a0a895dc',
            'username' => 'hedy@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '50cdea9c-af80-4e5e-86d0-2f4fd7a10fce',
            'username' => 'irene@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '50cdea9c-4380-4eb6-b4cc-2f4fd7a10fce',
            'username' => 'jean@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => 'eeee6042-c5cd-11e1-a0c5-080027796c51',
            'username' => 'kathleen@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '533d32c0-1f30-438c-8f26-1768c0a895dc',
            'username' => 'lynne@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
        $us[] = array('User' => array(
            'id' => '50cdea9c-fa10-47af-aaa8-2f4fd7a10fce',
            'username' => 'marlyn@passbolt.com',
            'role_id' => '0208f57a-c5cd-11e1-a0c5-080027796c4c',
            'password' => 'password',
            'active' => 1
        ));
		return $us;
	}
}
