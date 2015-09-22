<?php
/**
 * Our Data Extras command
 *
 * @copyright    copyright 2012 Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.plugins.DataExtras.Console.Command.DataShell
 * @since        version 2.12.11
 */

App::uses('AppShell', 'Console/Command');
App::uses('File', 'Utility');
App::uses('Folder', 'Utility');
App::uses('CakeSchema', 'Model');

class DataShell extends AppShell {

	public function getOptionParser() {
		$parser = parent::getOptionParser();
		$parser
			->addOption('data', array(
				'help' => 'Baseline data, minimal by default. Useful for testing and development.',
				'default' => 'default',
				'short' => 'd',
			))
			->description(__('Data import/export shell for the passbolt application.'));
		return $parser;
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
	 * @param string $options
	 * @return array
	 */
	private function __getModels($options = 'default') {
		switch ($options) {
			case 'default':
			default :
				return array(
					'DataDefault.Role',
					'DataDefault.User',
					'DataDefault.Gpgkey',
					'DataDefault.PermissionType',
					'DataDefault.Profile',
				);
			case 'seleniumtests':
				return array(
					// defaults
					'DataDefault.Role',
					'DataDefault.PermissionType',
					// same users than unit tests
					'DataUnitTests.User',
					'DataDefault.Gpgkey', // needs to be done when all users are inserted
					'DataUnitTests.Profile',
					'DataUnitTests.Avatar',
					// resource and permission are different though
					'DataSeleniumTests.Resource',
					'DataSeleniumTests.Permission',
					'DataUnitTests.Secret',
					'DataUnitTests.Comment'
				);
			case 'unittests':
				return array(
					// defaults
					'DataDefault.Role',
					'DataDefault.PermissionType',
					// all the things!
					'DataUnitTests.User',
					'DataDefault.Gpgkey',
					'DataUnitTests.Profile',
					'DataUnitTests.Avatar',
					'DataUnitTests.Resource',
					'DataUnitTests.Secret',
					'DataUnitTests.Comment',
					// testing only
					'DataUnitTests.CategoryType',
					'DataUnitTests.Category',
					'DataUnitTests.CategoryResource',
					'DataUnitTests.Group',
					'DataUnitTests.GroupUser',
					'DataUnitTests.Tag',
					'DataUnitTests.ItemTag',
					'DataUnitTests.Permission'
				);
		}
	}

	/**
	 * Import data test for the defined models
	 * @param $options string
	 * @return void
	 */
	public function import($options = 'default') {
		if(isset($this->params['data'])) {
			$options = $this->params['data'];
		}
		$dataModels = $this->__getModels($options);

		foreach ($dataModels as $dataModel) {
			$Task = $this->Tasks->load($dataModel);
			if (method_exists($Task, "beforeExecute")) {
				$Task->beforeExecute();
			}
			$Task->execute();
			if (method_exists($Task, "afterExecute")) {
				$Task->afterExecute();
			}
			$this->out('Data for model ' . $dataModel . ' inserted');
		}
	}

	/**
	 * Export passbolt data into fixtures.
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
	 * @return void
	 */
	public function exportSchema($options, $noFixtureTables) {
		$cakeSchema = new CakeSchema($options);
		$schema = $cakeSchema->load($options);

		foreach ($schema->tables as $name => $table) {
			if (in_array($name, $noFixtureTables)) {
				continue;
			}
			$this->dispatchShell("bake fixture --count 1000 --records --schema {$name}");
		}
	}

}
