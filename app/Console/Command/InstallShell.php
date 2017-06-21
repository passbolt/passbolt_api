<?php
/**
 * Our Install command
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppShell', 'Console/Command');
App::uses('AnonymousStatistic', 'Model');

// Uses Gpg Utility.
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg' );
}

class InstallShell extends AppShell {

/**
 * Display the passbolt ascii banner
 *
 * @return void
 */
	protected function _welcome() {
		$this->hr();
		$this->out('     ____                  __          ____  ');
		$this->out('    / __ \____  _____ ____/ /_  ____  / / /_ ');
		$this->out('   / /_/ / __ `/ ___/ ___/ __ \/ __ \/ / __/ ');
		$this->out('  / ____/ /_/ (__  |__  ) /_/ / /_/ / / /    ');
		$this->out(' /_/    \__,_/____/____/_.___/\____/_/\__/   ');
		$this->out('');
		$this->out(' Open source password manager for teams');
		$this->hr();
	}

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
			->addOption('send-anonymous-statistics', [
				'help' => 'Whether or not anonymous usage statistics should be sent to passbolt servers.
				(Check our privacy policy for more information: https://www.passbolt.com/privacy#statistics).',
				'default' => '',
				'choices' => [
					'true',
					'false',
				],
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
 * Main shell entry point
 *
 * @return bool
 */
	public function main() {
        $this->out(' Install shell');
        $this->hr();

		// init gnupg keyring
		try {
			$this->initGpgKeyring();
		} catch(Exception $e) {
			$this->out($e->getMessage());
			$this->out('<error>Installation failed.</error>');
			return false;
		}

		// try to build from cache if requested and possible
		if (isset($this->params['quick']) && $this->params['quick'] != 'false') {
			if ($this->_installFromCache() && $this->_restoreCacheAvatars()) {
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

		// Configure anonymous statistics.
		$this->_configureAnonymousStatistics();

		// register the admin user
		$registerAdmin = $this->param('no-admin');
		if (!$registerAdmin) {
			$this->_registerAdmin($this->param('admin-username'), $this->param('admin-first-name'), $this->param('admin-last-name'));
		}

		// Check whether anonymous statistics should be sent.
		AnonymousStatistic::reloadConfigFile();
		if (Configure::read('AnonymousStatistics.send') === true) {
			$AnonymousStatistic = Common::getModel('AnonymousStatistic');
			$AnonymousStatistic->send(AnonymousStatistic::CONTEXT_INSTALL);
		}

		if (!isset($this->params['cache']) || $this->params['cache'] == 'true') {
			// Set cache for SQL.
			$this->_setCache();
			// Set cache for avatars.
			$this->_setCacheAvatars();
		}

		// that's all folks
		$this->hr();
		$this->out('');
		$this->out(' Passbolt installation success! Enjoy! ☮');
		$this->out('');
		return true;
	}


	/**
	 * Configure anonymous statistics.
	 */
	protected function _configureAnonymousStatistics() {
		AnonymousStatistic::reloadConfigFile();
		$param = $this->param('send-anonymous-statistics');
		$instanceId = Configure::read('AnonymousStatistics.instanceId');
		$isConfigured = !empty($instanceId) && Common::isUuid($instanceId);

		// User choice.
		$choice = false;

		if (!$isConfigured) {
			$instanceId = Common::uuid();
		}

		// If param was provided, we store param value as user choice.
		if (!empty($param)) {
			$choice = $param == 'true' ? true : false;
		}
		// if param was not provided, ask the user.
		else {
			$input = $this->in(__d(
				'cake_console',
				__("We need you to help make passbolt better by sending anonymous usage statistics. Ok?\n(see: %s)", Configure::read('AnonymousStatistics.help'))
			), array('y', 'n'), 'n');
			$choice = $input == 'y' ? true : false;
		}

		// Write config file.
		$AnonymousStatistic = Common::getModel('AnonymousStatistic');
		$AnonymousStatistic->writeConfigFile($instanceId, $choice);
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
	public function initGpgKeyring() {
		$gpg = new Passbolt\Gpg();
		$config = Configure::read();

		// Check that a GPG configuration id is provided
		if (!isset($config['GPG']['serverKey']['fingerprint'])
			|| !isset($config['GPG']['serverKey']['private'])
			|| !isset($config['GPG']['serverKey']['public'])
		) {
			throw new CakeException('The GnuPG config for the server is not available or incomplete');
		}
		$keyid = $config['GPG']['serverKey']['fingerprint'];
		$privateKeyPath = $config['GPG']['serverKey']['private'];
		$publicKeyPath = $config['GPG']['serverKey']['public'];

		// Check if keyring is present and writable
		$keyring = getenv('GNUPGHOME');
		if (!is_writable($keyring)) {
			throw new CakeException("GPG Keyring is not available or not writable. Check: " . $keyring);
		}

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
		$privateKeyInfo = $gpg->getKeyInfo($privateKeydata);
		if ($privateKeyInfo['fingerprint'] != $keyid) {
			throw new CakeException('The private key does not match the fingerprint mentioned in the config');
		}

		// Check that there is a public key found at the given path
		if (!file_exists($publicKeyPath)) {
			throw new CakeException("No public key found at the given path $publicKeyPath");
		}
		$publicKeydata = file_get_contents($publicKeyPath);

		// Check that the public key match the fingerprint
		$publicKeyInfo = $gpg->getKeyInfo($publicKeydata);
		if ($publicKeyInfo['fingerprint'] != $keyid) {
			throw new CakeException('The public key does not match the fingerprint mentioned in the config');
		}

		// Import the private key in the GPG keyring
		try {
			$gpg->importKeyIntoKeyring($privateKeydata);
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
		$this->dispatchShell('schema create --yes' . (isset($this->params['quiet'] ) && $this->params['quiet'] == 1 ? ' -q' : '') . (isset($this->params['connection']) ? ' --connection ' . $this->params['connection'] : ''));
		$this->out('passbolt schema deployed');
		$this->dispatchShell('schema create sessions --yes' . (isset($this->params['quiet'] ) && $this->params['quiet'] == 1 ? ' -q' : '') . (isset($this->params['connection']) ? ' --connection ' . $this->params['connection'] : ''));
		$this->out('passbolt session table deployed');
		$this->dispatchShell('schema create --plugin FileStorage --yes' . (isset($this->params['quiet'] ) && $this->params['quiet'] == 1 ? ' -q' : '') . (isset($this->params['connection']) ? ' --connection ' . $this->params['connection'] : ''));
		$this->out('plugins schemas deployed');
	}

/**
 * Insert the dummy data in database (dispatch)
 *
 * @param string $options default, seleniumtests or unittests
 * @return void
 */
	public function data($options = 'default') {
		$this->dispatchShell('data import --data=' . $options . (isset($this->params['quiet'] ) && $this->params['quiet'] == 1 ? ' -q' : '') . (isset($this->params['connection']) ? ' --connection ' . $this->params['connection'] : ''));
	}

/**
 * Register the admin user
 *
 * @param string $username admin email
 * @param string $firstName admin first name
 * @param string $lastName admin last name
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
			$cmd .= ' -u ' . $username;
		}
		if (!is_null($firstName)) {
			$cmd .= ' -f ' . $firstName;
		}
		if (!is_null($lastName)) {
			$cmd .= ' -l ' . $lastName;
		}
		if (isset($this->params['quiet']) && $this->params['quiet'] == 1) {
			$cmd .= ' -q';
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
		if (isset($this->params['quiet']) && $this->params['quiet'] == 1) {
			$cmd .= ' -q';
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
		if (isset($this->params['quiet']) && $this->params['quiet'] == 1) {
			$cmd .= ' -q';
		}
		return $this->dispatchShell($cmd);
	}

/**
 * Set the cache for the avatars.
 *
 * @return bool
 */
	protected function _setCacheAvatars() {
		$path = IMAGES . 'public' . DS . 'images';
		$cachePath = IMAGES . 'public' . DS . 'images_bk';

		exec("rm -rf {$cachePath} ", $output, $status);
		if ($status == 1) {
			$this->out(' Ooops, something went wrong when trying to delete the avatars!');
			return false;
		}

		if (file_exists($path)) {
			exec("cp -fr -T {$path} {$cachePath} ", $output, $status);
			if ($status == 1) {
				$this->out(' Ooops, something went wrong when trying to set the avatars cache!');
				return false;
			} else {
				$this->out(' Avatar cache set!');
				return true;
			}
		}
	}

/**
 * Restore the cache for the avatars.
 *
 * @return bool
 */
	protected function _restoreCacheAvatars() {
		$path = IMAGES . 'public' . DS . 'images';
		$cachePath = IMAGES . 'public' . DS . 'images_bk';
		if (file_exists($cachePath)) {
			exec("rm -rf {$path} ", $output, $status);
			if ($status == 1) {
				$this->out(' Ooops, something went wrong when trying to delete the avatars!');
				return false;
			}

			exec("cp -fr -T {$cachePath} {$path}", $output, $status);
			if ($status == 1) {
				$this->out(' Ooops, something went wrong when trying to restore the avatars!');
				return false;
			} else {
				$this->out(' Avatars restored!');
				return true;
			}
		}
		return true;
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
