<?php
/**
 * Our Install command
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppShell', 'Console/Command');

class InstallShell extends AppShell
{

	/**
	 * @return ConsoleOptionParser
	 */
	public function getOptionParser()
	{
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
			->addOption('quick', array(
				'help' => 'Use a database dump if any to speed things up.',
				'default' => 'false',
			))
			->addOption('cache', array(
				'help' => 'Create a database dump to enable cache option use later on',
				'default' => 'true',
				'short' => 'c',
			))
			->addOption('delete-avatars', array(
				'help' => 'Delete existing public avatars',
				'default' => 'true',
				'short' => 'a',
			))
			->description(__('Installation shell for the passbolt application.'));

		return $parser;
	}

	/**
	 * Display the passbolt ascii banner
	 */
	protected function __welcome()
	{
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
	 */
	public function main()
	{
		$this->_config = Configure::read();
		$this->__welcome();
		$done = false;

		// init gnupg keyring
		$this->_initGpgKeyring();

		// try to build from cache if requested and possible
		if ($this->params['quick'] != 'false') {
			$done = $this->__installFromCache();
			if ($done) return true;
		}

		// Delete existing avatar if requested
		if (isset($this->params['delete-avatars']) && $this->params['delete-avatars'] == 'true') {
			$this->deleteAvatars();
		}

		// otherwise build from scratch
		if (!$done) {
			// create the schema & insert dummy data
			$this->schema();
			$this->data($this->params['data']);

			// @TODO in case of default install request admin password details
			// see. createAdminUser

			if (!isset($this->params['cache']) || $this->params['cache'] == 'true') {
				$this->__setCache();
			}
		}

		// that's all folks
		$this->hr();
		$this->out('');
		$this->out(' Passbolt installation success! Enjoy! ☮');
		$this->out('');
	}

/**
 * Init the gpg keyring
 *
 * @return void
 * @throws CakeException
 */
	protected function _initGpgKeyring() {
		// Check that a GPG configuration id is provided
		if (!isset($this->_config['GPG']['serverKey']['fingerprint'])
			|| !isset($this->_config['GPG']['serverKey']['private'])) {
			throw new CakeException('The GnuPG config for the server is not available or incomplete');
		}
		$keyid = $this->_config['GPG']['serverKey']['fingerprint'];
		$privateKeyPath = $this->_config['GPG']['serverKey']['private'];

		// Check that there is a key found at the given path
		if (!file_exists($privateKeyPath)) {
			throw new CakeException("No private key found at the given path $privateKeyPath");
		}
		$keydata = file_get_contents($privateKeyPath);

		// Import the private key in the GPG keyring
		$this->_gpg = new gnupg();
		$importResults = $this->_gpg->import($keydata);

		// Check if something went wrong during the import
		if (!$importResults || !isset($importResults['fingerprint'])) {
			throw new CakeException('The GnuPG key for the server could not be imported');
		}

		// check that the imported key match the fingerprint
		if ($importResults['fingerprint'] != $keyid) {
			throw new CakeException('The GnuPG server key for the authentication scheme is not available');
		}
	}

	/**
	 * Install the database schema
	 */
	public function schema()
	{
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
	 * @param string $options
	 */
	public function data($options = 'default')
	{
		//CakePlugin::load('DataExtras');
		$this->dispatchShell('data import --data=' . $options);
	}

	/**
	 * Build the cache for further use
	 * @return bool
	 */
	protected function __installFromCache()
	{
		$cmd = 'sql import';
		if (isset($this->params['data'])) {
			$cmd .= ' --data=' . $this->params['data'];
		}
		return $this->dispatchShell($cmd);

	}

	/**
	 * Build the cache for further use
	 * @return bool
	 */
	public function __setCache()
	{
		$cmd = 'sql export';
		if (isset($this->params['data'])) {
			$cmd .= ' --data=' . $this->params['data'];
		}
		return $this->dispatchShell($cmd);
	}

	/**
	 * Delete the avatars from public image director
	 */
	public function deleteAvatars()
	{
		$path = IMAGES . 'public' . DS . 'images';
		if (PHP_OS === 'Windows') {
			exec("rd /s /q {$path}", $output, $status);
		} else {
			exec("rm -rf {$path}", $output, $status);
		}
		if($status == 1) {
			$this->out(' Ooops, something went wrong when trying to delete the avatars!');
			return false;
		}
		else {
			$this->out(' Avatar deleted!');
			return true;
		}
	}
}