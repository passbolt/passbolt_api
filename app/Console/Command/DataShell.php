<?php
/**
 * Our Data Extras command
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('AppShell', 'Console/Command');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('CakeSchema', 'Model');

class DataShell extends AppShell {

/**
 * Define the parameters accepted by the task
 *
 * @return ConsoleOptionParser
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->addOption('data', array(
				'help' => 'Baseline data, minimal by default. Useful for testing and development.',
				'default' => 'default',
				'short' => 'd',
			))
			->description(__('Data import/export shell for the passbolt application.'));

		$parser
			->addOption('connection', array(
					'help' => 'Which db connection to use.',
					'default' => 'default',
					'short' => 'c',
				))
			->description(__('Which db connection to use, usually default or test'));

		return $parser;
	}

/**
 * Display a welcome message
 *
 * @return void
 */
	protected function _welcome() {
		parent::_welcome();
		$this->out('Installing data set:' . $this->params['data']);
		$this->hr();
	}

/**
 * Execution method always used for tasks
 * Handles dispatching to interactive, named, or all processes.
 *
 * @return void
 */
	public function execute() {
		parent::execute();
		$this->interactive = false;
		if (!isset($this->connection)) {
			$this->connection = 'default';
		}
	}

/**
 * Get the models/tasks for a given data install context
 *
 * @param string $options default, unittests or seleniumtests
 * @return array
 */
	private function __getModels($options = 'default') {
		switch ($options) {
			case 'default':
			default :
				return array(
					'DataDefault.SchemaMigration',
					'DataDefault.Role',
					'DataDefault.User',
					'DataDefault.Gpgkey',
					'DataDefault.PermissionType',
					'DataDefault.Profile',
				);
			case 'seleniumtests':
				return array(
					// defaults
					'DataDefault.SchemaMigration',
					'DataDefault.Role',
					'DataDefault.PermissionType',
					// same users than unit tests
					'DataUnitTests.User',
					'DataDefault.Gpgkey', // needs to be done when all users are inserted
					'DataUnitTests.Profile',
					'DataUnitTests.Avatar',
					// resource and permission are different though
					'DataSeleniumTests.Resource',
					'DataUnitTests.Group',
					'DataUnitTests.GroupUser',
					'DataSeleniumTests.Permission',
					'DataSeleniumTests.Secret',
					//'DataUnitTests.Comment'
				);
			case 'unittests':
				return array(
					// defaults
					'DataDefault.SchemaMigration',
					'DataDefault.Role',
					'DataDefault.PermissionType',
					// all the things!
					'DataUnitTests.User',
					'DataDefault.Gpgkey',
					'DataUnitTests.Profile',
					'DataUnitTests.Avatar',
					'DataSeleniumTests.Resource',
					// testing only
					'DataUnitTests.Group',
					'DataUnitTests.GroupUser',
					'DataSeleniumTests.Permission',
					'DataSeleniumTests.Secret',
					'DataUnitTests.Comment'
				);
		}
	}

/**
 * Import data test for the defined models
 *
 * @param string $options default, unittests or seleniumtests
 * @return void
 */
	public function import($options = 'default') {
		if (isset($this->params['data'])) {
			$options = $this->params['data'];
		}
		$dataModels = $this->__getModels($options);

		foreach ($dataModels as $dataModel) {
			$Task = $this->Tasks->load($dataModel);
			$Task->params['quiet'] = isset($this->params['quiet']) && $this->params['quiet'] == 1 ? 1 : 0;
			$Task->params['connection'] = isset($this->params['connection']) ? $this->params['connection'] : 'default';
			if (method_exists($Task, "beforeExecute")) {
				$Task->beforeExecute();
			}
			$Task->execute();
			if (method_exists($Task, "afterExecute")) {
				$Task->afterExecute();
			}
		}
	}

/**
 * Export passbolt data into fixtures.
 *
 * @return void
 */
	public function export() {
		// Export passbolt schema data
		$noFixtureTables = array(
			//'gpgkeys'
		);
		$options = array(
			"name" => "",
			"path" => APP . "/Config/Schema",
			"file" => "schema",
			"connection" => "default",
			"plugin" => null
		);
		$this->exportSchema($options, $noFixtureTables);

		// Export
		$noFixtureTables = array();
		$options = array(
			"name" => "FileStorage",
			"path" => APP . "Plugin/FileStorage/Config/Schema",
			"file" => "schema.php",
			"connection" => "default",
			"plugin" => ""
		);
		$this->exportSchema($options, $noFixtureTables);
	}

/**
 * Export data from a schema into fixtures
 *
 * @param array $options for cakeschema
 * @param array $noFixtureTables tables without fixtures
 * @return void
 */
	public function exportSchema($options, $noFixtureTables) {
		$cakeSchema = new CakeSchema($options);
		$schema = $cakeSchema->load($options);

		foreach ($schema->tables as $name => $table) {
			if (in_array($name, $noFixtureTables)) {
				continue;
			}
			$command = "bake fixture --count 1000 --records {$name}";
			if (isset($this->params['quiet']) && $this->params['quiet'] == 1) {
				$command .= ' --quiet';
			}

			$this->dispatchShell($command);
		}

		$this->out('Data deployed!');
	}

}
