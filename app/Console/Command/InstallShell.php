<?php
/**
 * Our Install command
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Console.Command.InstallShell
 * @since        version 2.12.11
 */

class InstallShell extends AppShell {

	const DEFAULT_ADMIN_PASSWORD = 'ILovePassbolt!';

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser->addOption('data', array(
				'help' => 'Dummy data from plugin dataExtras. Useful for development.',
				'boolean' => true,
				'default' => false,
				'short' => 'd',
			))
			->addOption('password', array(
					'help' => 'Password for admin user (only for non data install).',
					'default' => '',
					'short' => 'p',
				))
			->description(__('Installation shell for the application.'));
		return $parser;
	}

	public function main() {
		$this->schema();
		// If data is requested, we install dummy data.
		if (isset($this->params['data'])) {
			$this->data();
		}
		else {
			$password = isset($this->params['password']) ? $this->params['password'] : self::DEFAULT_ADMIN_PASSWORD;
			// Validate password.
			$User = ClassRegistry::init('User');
			$User->set(array('password' => $password));
			if (!$User->validates(array('fieldList' => array('password')))) {
				$this->out("<error>Password is in wrong format</error>");
				$this->out("<info>Debug info :\n" . print_r($User->invalidFields()['password'], true) . "</info>");
				return;
			}
			$this->createRoles();
			$this->createAnonUser();
			$this->createAdminUser($password);
		}
		$this->out("\npassbolt installation success");
	}

	public function schema() {
		$this->dispatchShell('schema create --force_drop --force_create -q');
		$this->out('passbolt schema deployed');
		$this->dispatchShell('schema create sessions --force_drop --force_create -q');
		$this->out('passbolt session table deployed');
		$this->dispatchShell('schema create --plugin FileStorage --force_drop --force_create -q');
		$this->out('plugins schemas deployed');
	}

	public function data() {
		CakePlugin::load('DataExtras');
		$this->dispatchShell('DataExtras.Data import');
		$this->out('data deployed');
	}

	public function createAdminUser($password) {
		$User = ClassRegistry::init('User');
		$Role = ClassRegistry::init('Role');
		$User->create();
		$u = $User->save(array(
				'username' => 'admin@passbolt.com',
				'active' => true,
				'password' => $password,
				'role_id' => $Role->field('id', array('name' => 'admin')),
			));
		if (!$u) {
			$this->out("<error>Could not save user</error>");
		}

		$Profile = ClassRegistry::init('Profile');
		$Profile->create();
		$p = $Profile->save(array(
				'first_name' => 'admin',
				'last_name' => 'admin',
				'user_id' => $u['User']['id'],
			));
		if (!$p) {
			$this->out("<error>Could not save profile</error>");
		}

		$gpgkeyPath = APP . 'Config' . DS . 'gpg' . DS . 'passbolt_dummy_key.asc';
		$keyContent = file_get_contents($gpgkeyPath);
		$Gpgkey = ClassRegistry::init('Gpgkey');
		$gpgkey = $Gpgkey->save(array(
				'user_id' => $u['User']['id'],
				'key' => $keyContent,
			));
		if (!$gpgkey) {
			$this->out("<error>Could not save gpg key</error>");
		}
	}

	public function createAnonUser() {
		$User = ClassRegistry::init('User');
		$Role = ClassRegistry::init('Role');
		$User->create();
		$u = $User->save(array(
				'username' => 'anonymous@passbolt.com',
				'active' => true,
				'password' => 'thYu!76hn54(§7yhT(',
				'role_id' => $Role->field('id', array('name' => 'guest')),
			));
		if (!$u) {
			$this->out("<error>Could not save user</error>");
		}

		$Profile = ClassRegistry::init('Profile');
		$Profile->create();
		$p = $Profile->save(array(
				'first_name' => 'anon',
				'last_name' => 'ymous',
				'user_id' => $u['User']['id'],
			));
		if (!$p) {
			$this->out("<error>Could not save profile</error>");
		}
	}

	public function createRoles() {
		$Role = ClassRegistry::init('Role');
		$Role->create();
		$Role->save(array('name' => 'guest', 'description' => 'guest user'));
		$Role->create();
		$Role->save(array('name' => 'user', 'description' => 'normal user'));
		$Role->create();
		$Role->save(array('name' => 'admin', 'description' => 'god almighty'));
	}
}