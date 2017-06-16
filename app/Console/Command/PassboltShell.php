<?php

/**
 * Passbolt client console
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppShell', 'Console/Command');

class PassboltShell extends AppShell {

/**
 * Contains tasks to load and instantiate
 *
 * @var array
 */
	public $tasks = array('Healthcheck', 'RegisterUser', 'AppConfig', 'CoreConfig');

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
			->description(__('The Passbolt CLI offers an access to the passbolt API directly from the console.'))
            ->addSubcommand('healthcheck', array(
                'help' => __('Check the configuration of the passbolt installation and associated environment'),
                'parser' => $this->Healthcheck->getOptionParser()
            ))
            ->addSubcommand('register_user', array(
				'help' => __('Register new user'),
				'parser' => $this->RegisterUser->getOptionParser()
			))
			->addSubcommand('app_config', array(
				'help' => __('Manipulate the passbolt app configuration'),
				'parser' => $this->AppConfig->getOptionParser()
			))
			->addSubcommand('core_config', array(
				'help' => __('Manipulate the passbolt core configuration'),
				'parser' => $this->CoreConfig->getOptionParser()
			));

		return $parser;
	}

/**
 * Main shell entry point
 *
 * @return bool
 */
	public function main() {
	}

}
