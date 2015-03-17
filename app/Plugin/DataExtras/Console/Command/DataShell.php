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

	public $dataModels = array(
		'Role',
		'User',
		'CategoryType',
		'Category',
		'Resource',
		'CategoryResource',
		'Group',
		'GroupUser',
		'Tag',
		'ItemTag',
		'PermissionType',
		'Permission',
		'Comment',
		'Profile',
		'Avatar',
	);

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
 * Import data test for the defined models
 *
 * @return void
 */
	public function import() {
		foreach ($this->dataModels as $dataModel) {
			$Task = $this->Tasks->load('DataExtras.' . $dataModel);
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
 *
 * @return void
 */
	public function export() {
		// Export passbolt schema data
		$noFixtureTables = array(
			'gpgKeys'
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

/**
 * get the option parser
 *
 * @return void
 */
	public function getOptionParser() {
		return parent::getOptionParser();
		$plugin = array(
			'short' => 'p',
			'help' => __d('cake_console', 'The plugin to use.'),
		);
		$connection = array(
			'short' => 'c',
			'help' => __d('cake_console', 'Set the db config to use.'),
			'default' => 'default'
		);
		$path = array(
			'help' => __d('cake_console', 'Path to read and write schema.php'),
			'default' => APP . 'Config' . DS . 'Schema'
		);
		$file = array(
			'help' => __d('cake_console', 'File name to read and write.'),
			'default' => 'schema.php'
		);
		$name = array(
			'help' => __d('cake_console', 'Classname to use. If its Plugin.class, both name and plugin options will be set.')
		);
		$snapshot = array(
			'short' => 's',
			'help' => __d('cake_console', 'Snapshot number to use/make.')
		);
		$models = array(
			'short' => 'm',
			'help' => __d('cake_console', 'Specify models as comma separated list.'),
		);
		$dry = array(
			'help' => __d('cake_console', 'Perform a dry run on create and update commands. Queries will be output instead of run.'),
			'boolean' => true
		);
		$force_drop = array(
			'help' => __d('cake_console', 'Force "drop table" to create a new schema'),
			'boolean' => true,
			'default' => null
		);
		$force_create = array(
			'help' => __d('cake_console', 'Force "create table" to create a new schema'),
			'boolean' => true,
			'default' => null
		);
		$force = array(
			'short' => 'f',
			'help' => __d('cake_console', 'Force "generate" to create a new schema'),
			'boolean' => true
		);
		$write = array(
			'help' => __d('cake_console', 'Write the dumped SQL to a file.')
		);

		$parser = parent::getOptionParser();
		$parser->description(__d('cake_console', 'The Schema Shell generates a schema object from the database and updates the database from the schema.'))->addSubcommand('view', array(
				'help' => __d('cake_console', 'Read and output the contents of a schema file'),
				'parser' => array(
					'options' => compact('plugin', 'path', 'file', 'name', 'connection'),
					'arguments' => compact('name')
				)
			))->addSubcommand('generate', array(
				'help' => __d('cake_console', 'Reads from --connection and writes to --path. Generate snapshots with -s'),
				'parser' => array(
					'options' => compact('plugin', 'path', 'file', 'name', 'connection', 'snapshot', 'force', 'models'),
					'arguments' => array(
						'snapshot' => array('help' => __d('cake_console', 'Generate a snapshot.'))
					)
				)
			))->addSubcommand('dump', array(
				'help' => __d('cake_console', 'Dump database SQL based on a schema file to stdout.'),
				'parser' => array(
					'options' => compact('plugin', 'path', 'file', 'name', 'connection', 'write'),
					'arguments' => compact('name')
				)
			))->addSubcommand('create', array(
				'help' => __d('cake_console', 'Drop and create tables based on the schema file.'),
				'parser' => array(
					'options' => compact('plugin', 'path', 'file', 'name', 'connection', 'dry', 'snapshot', 'force_drop', 'force_create'),
					'args' => array(
						'name' => array(
							'help' => __d('cake_console', 'Name of schema to use.')
						),
						'table' => array(
							'help' => __d('cake_console', 'Only create the specified table.')
						)
					)
				)
			))->addSubcommand('update', array(
				'help' => __d('cake_console', 'Alter the tables based on the schema file.'),
				'parser' => array(
					'options' => compact('plugin', 'path', 'file', 'name', 'connection', 'dry', 'snapshot', 'force'),
					'args' => array(
						'name' => array(
							'help' => __d('cake_console', 'Name of schema to use.')
						),
						'table' => array(
							'help' => __d('cake_console', 'Only create the specified table.')
						)
					)
				)
			));
		return $parser;
	}

}
