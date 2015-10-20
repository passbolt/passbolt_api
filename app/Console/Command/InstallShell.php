<?php
/**
 * Our Install command
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Console.Command.InstallShell
 * @since        version 2.12.11
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
		$this->__welcome();
		$done = false;

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
		$path = IMAGES . 'public' . DS . 'imagess';
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