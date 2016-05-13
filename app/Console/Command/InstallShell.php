<?php

/**
 * Our Install command
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppShell', 'Console/Command');

// Uses Gpg Utility.
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg' );
}

class InstallShell extends AppShell {

/**
 * Get command options parser
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->addOption('data', [
				'help' => 'Baseline data, minimal by default. Useful for testing and development.',
				'default' => 'default',
				'short' => 'd',
			])
			->addOption('password', [
				'help' => 'Password for admin user (only for non data install).',
				'default' => '',
				'short' => 'p',
			])
			->addOption('quick', [
				'help' => 'Use a database dump if any to speed things up.',
				'default' => 'false',
			])
			->addOption('cache', [
				'help' => 'Create a database dump to enable cache option use later on',
				'default' => 'true',
				'short' => 'c',
			])
			->addOption('delete-avatars', [
				'help' => 'Delete existing public avatars',
				'default' => 'true',
				'short' => 'a',
			])
			->addOption('no-admin', [
				'help' => 'Don\'t register an admin account during the installation',
				'boolean' => true,
			])
			->addOption('admin-username', [
				'help' => __('Admin\' username (email). If interactive mode enabled, and no-admin not set, it will be requested')
			])
			->addOption('admin-first-name', [
				'help' => __('Admin\' first name. If interactive mode enabled, and no-admin not set, it will be requested')
			])
			->addOption('admin-last-name', [
				'help' => __('Admin\' last name. If interactive mode enabled, and no-admin not set, it will be requested')
			])
			->description(__('Installation shell for the passbolt application.'));

		return $parser;
	}

/**
 * Display the passbolt ascii banner
 *
 * @return void
 */
	protected function _welcome() {
		$this->out('     ____                  __          ____  ');
		$this->out('    / __ \____  _____ ____/ /_  ____  / / /_ ');
		$this->out('   / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/ ');
		$this->out('  / ____/ /_/ (__  |__  ) /_/ / /_/ / / /    ');
		$this->out(' /_/    \__,_/____/____/_.___/\____/_/\__/   ');
		$this->out('');
		$this->out(' The open source password management solution for teams');
		$this->out(' (c) 2015-present passbolt.com');
		$this->out('');
		$this->hr();
	}

/**
 * Main shell entry point
 *
 * @return bool
 */
	public function main() {
		$this->_config = Configure::read();

		// init gnupg keyring
		$this->_initGpgKeyring();

		// try to build from cache if requested and possible
		if (isset($this->params['quick']) && $this->params['quick'] != 'false') {
			if ($this->_installFromCache()) {
				return true;
			}
		}

		// Delete existing avatar if requested
		if (isset($this->params['delete-avatars']) && $this->params['delete-avatars'] == 'true') {
			$this->deleteAvatars();
		}

		// install from scratch
		// create the schema
		$this->schema();

		// insert the data, if no set of data specified insert default data
		$data = $this->param('data');
		$this->data($data);

		// register the admin user
		$registerAdmin = $this->param('no-admin');
		if (!$registerAdmin) {
			$this->_registerAdmin($this->param('admin-username'), $this->param('admin-first-name'), $this->param('admin-last-name'));
		}

		if (!isset($this->params['cache']) || $this->params['cache'] == 'true') {
			$this->_setCache();
		}

		// that's all folks
		$this->hr();
		$this->out('');
		$this->out(' Passbolt installation success! Enjoy! ☮');
		$this->out('');
	}

/**
 * Check that a default app config variable has been overridden
 *
 * @param string $key The variable to check
 * @return bool
 * @throws CakeException
 */
	protected function _checkDefaultAppConfigOverriden($key) {
		include APP . DS . 'Config' . DS . 'app.php.default';
		if (!isset($config)) {
			throw new CakeException('Unable to load the default app config file');
		}
		if (Configure::read($key) != Set::get($config, $key)) {
			return true;
		}
		return false;
	}

/**
 * Init the gpg keyring
 *
 * @return void
 * @throws CakeException
 */
	protected function _initGpgKeyring() {
		$this->_gpg = new Passbolt\Gpg();

		// Check that a GPG configuration id is provided
		if (!isset($this->_config['GPG']['serverKey']['fingerprint'])
			|| !isset($this->_config['GPG']['serverKey']['private'])
			|| !isset($this->_config['GPG']['serverKey']['public'])
		) {
			throw new CakeException('The GnuPG config for the server is not available or incomplete');
		}
		$keyid = $this->_config['GPG']['serverKey']['fingerprint'];
		$privateKeyPath = $this->_config['GPG']['serverKey']['private'];
		$publicKeyPath = $this->_config['GPG']['serverKey']['public'];

		// In production don't accept default GPG server key
		if (!Configure::read('debug')) {
			if (!$this->_checkDefaultAppConfigOverriden('GPG.serverKey.fingerprint')) {
				throw new CakeException("Default GnuPG server key cannot be used in production. Please change the values of 'GPG.server' in 'APP/Config/app.php' with your server key information. If you don't have yet a server key, please generate one, take a look at the install documentation.");
			}
		}

		// Check that there is a private key found at the given path
		if (!file_exists($privateKeyPath)) {
			throw new CakeException("No private key found at the given path $privateKeyPath");
		}
		$privateKeydata = file_get_contents($privateKeyPath);

		// Check that the private key match the fingerprint
		$privateKeyInfo = $this->_gpg->getKeyInfo($privateKeydata);
		if ($privateKeyInfo['fingerprint'] != $keyid) {
			throw new CakeException('The private key does not match the fingerprint mentioned in the config');
		}

		// Check that there is a public key found at the given path
		if (!file_exists($publicKeyPath)) {
			throw new CakeException("No public key found at the given path $publicKeyPath");
		}
		$publicKeydata = file_get_contents($publicKeyPath);

		// Check that the public key match the fingerprint
		$publicKeyInfo = $this->_gpg->getKeyInfo($publicKeydata);
		if ($publicKeyInfo['fingerprint'] != $keyid) {
			throw new CakeException('The public key does not match the fingerprint mentioned in the config');
		}

		// Import the private key in the GPG keyring
		try {
			$this->_gpg->importKeyIntoKeyring($privateKeydata);
		} catch (Exception $e) {
			throw new CakeException('The GnuPG key for the server could not be imported');
		}
	}

/**
 * Install the database schema
 *
 * @return void
 */
	public function schema() {
		$this->out('Installing schema / database');
		$this->hr();
		$this->dispatchShell('schema create --force_drop --force_create -q');
		$this->out('passbolt schema deployed');
		$this->dispatchShell('schema create sessions --force_drop --force_create -q');
		$this->out('passbolt session table deployed');
		$this->dispatchShell('schema create --plugin FileStorage --force_drop --force_create -q');
		$this->out('plugins schemas deployed');
	}

/**
 * Insert the dummy data in database (dispatch)
 *
 * @param string $options
 * @return void
 */
	public function data($options = 'default') {
		$this->dispatchShell('data import --data=' . $options);
	}

/**
 * Register the admin user
 *
 * @return void
 */
	protected function _registerAdmin($username = null, $firstName = null, $lastName = null) {
		$this->out();
		$this->out('Register the passbolt admin account.');
		$cmd = 'passbolt register_user -r admin';
		if ($this->interactive) {
			$cmd .= ' -i';
		}
		if (!is_null($username)) {
			$cmd .= ' -u ' .$username;
		}
		if (!is_null($firstName)) {
			$cmd .= ' -f ' .$firstName;
		}
		if (!is_null($lastName)) {
			$cmd .= ' -l ' .$lastName;
		}
		$this->dispatchShell($cmd);
	}

/**
 * Build the cache for further use
 *
 * @return bool
 */
	protected function _installFromCache() {
		$cmd = 'sql import';
		if (isset($this->params['data'])) {
			$cmd .= ' --data=' . $this->params['data'];
		}
		return $this->dispatchShell($cmd);
	}

/**
 * Build the cache for further use
 *
 * @return bool
 */
	protected function _setCache() {
		$cmd = 'sql export';
		if (isset($this->params['data'])) {
			$cmd .= ' --data=' . $this->params['data'];
		}
		return $this->dispatchShell($cmd);
	}

/**
 * Delete the avatars from public image director
 *
 * @return bool
 */
	public function deleteAvatars() {
		$path = IMAGES . 'public' . DS . 'images';
		if (PHP_OS === 'Windows') {
			exec("rd /s /q {$path}", $output, $status);
		} else {
			exec("rm -rf {$path}", $output, $status);
		}
		if ($status == 1) {
			$this->out(' Ooops, something went wrong when trying to delete the avatars!');
			return false;
		} else {
			$this->out(' Avatar deleted!');
			return true;
		}
	}
}
