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
//
//	const DEFAULT_ADMIN_PASSWORD = 'ILovePassbolt!';

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->addOption('data', array(
				'help' => 'Baseline data, minimal by default. Useful for testing and development.',
				'default' => 'default',
				'short' => 'd',
			))
			->addOption('password', array(
					'help' => 'Password for admin user (only for non data install).',
					'default' => '',
					'short' => 'p',
				))
			->description(__('Installation shell for the passbolt application.'));
		return $parser;
	}

	/**
	 * Display the passbolt ascii banner
	 */
	private function __banner() {
		$this->out('     ____                  __          ____  ');
		$this->out('    / __ \____  _____ ____/ /_  ____  / / /_ ');
		$this->out('   / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/ ');
		$this->out('  / ____/ /_/ (__  |__  ) /_/ / /_/ / / /    ');
		$this->out(' /_/    \__,_/____/____/_.___/\____/_/\__/   ');
		$this->out('');
		$this->out('The password management solution');
		$this->out('(c) 2015-present passbolt.com');
		$this->out('---------------------------------------------------------------');
	}

	/**
	 * Main shell entry point
	 */
	public function main() {

		// display something fancy
		$this->__banner();

		// create the schema
		$this->schema();

		// insert dummy data
		$this->data($this->params['data']);

		// @TODO in case of default install request admin password details
		// see. createAdminUser

		$this->out('---------------------------------------------------------------');
		$this->out('Passbolt installation success! Enjoy!');
	}

	/**
	 * Install the database schema
	 */
	public function schema() {
		$this->out('Installing schema / database');
		$this->out('---------------------------------------------------------------');
		$this->dispatchShell('schema create --force_drop --force_create -q');
		$this->out('passbolt schema deployed');
		$this->dispatchShell('schema create sessions --force_drop --force_create -q');
		$this->out('passbolt session table deployed');
		$this->dispatchShell('schema create --plugin FileStorage --force_drop --force_create -q');
		$this->out('plugins schemas deployed');
	}

	/**
	 * Insert the dummy data in database (dispatch)
	 * @param string $options
	 */
	public function data($options = 'default') {
		$this->out('---------------------------------------------------------------');
		$this->out('Installing data set:' . $options );
		$this->out('---------------------------------------------------------------');
		//CakePlugin::load('DataExtras');
		$this->dispatchShell('data import --data='. $options);
		$this->out('Data deployed!');
	}

// @todo this needs to be an edit not a create
//	/**
//	 * Create admin user with given password
//	 * @param $password
//	 */
//	public function createAdminUser($password) {
//		$User = ClassRegistry::init('User');
//		$Role = ClassRegistry::init('Role');
//		$User->create();
//		$u = $User->save(array(
//				'username' => 'admin@passbolt.com',
//				'active' => true,
//				'password' => $password,
//				'role_id' => $Role->field('id', array('name' => 'admin')),
//			));
//		if (!$u) {
//			$this->out("<error>Could not save user</error>");
//		}
//
//		$Profile = ClassRegistry::init('Profile');
//		$Profile->create();
//		$p = $Profile->save(array(
//				'first_name' => 'admin',
//				'last_name' => 'admin',
//				'user_id' => $u['User']['id'],
//			));
//		if (!$p) {
//			$this->out("<error>Could not save profile</error>");
//		}
//
//		$gpgkeyPath = APP . 'Config' . DS . 'gpg' . DS . 'passbolt_dummy_key.asc';
//		$keyContent = file_get_contents($gpgkeyPath);
//		$Gpgkey = ClassRegistry::init('Gpgkey');
//		$gpgkey = $Gpgkey->save(array(
//				'user_id' => $u['User']['id'],
//				'key' => $keyContent,
//			));
//		if (!$gpgkey) {
//			$this->out("<error>Could not save gpg key</error>");
//		}
//	}
//
}