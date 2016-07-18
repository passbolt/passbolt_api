<?php
/**
 * Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2014, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Shell', 'Console');
App::uses('AppShell', 'Console/Command');
App::uses('CakeSchema', 'Model');
App::uses('MigrationVersion', 'Migrations.Lib');
App::uses('String', 'Utility');
App::uses('ClassRegistry', 'Utility');
App::uses('ConnectionManager', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

/**
 * Migration shell.
 */
class MigrationShell extends AppShell {

/**
 * Connection used for the migration_schema table of the migration versions
 *
 * @var null|string
 */
	public $connection = null;

/**
 * Connection used for the migration
 *
 * This can be used to override the connection of migration file
 *
 * @var null|string
 */
	public $migrationConnection = null;

/**
 * Current path to load and save migrations
 *
 * @var string
 */
	public $path;

/**
 * Type of migration, can be 'app' or a plugin name
 *
 * @var string
 */
	public $type = 'app';

/**
 * MigrationVersion instance
 *
 * @var MigrationVersion
 */
	public $Version;

/**
 * Messages used to display action being performed
 *
 * @var array
 */
	protected $_messages = array();

/**
 * Override startup
 *
 * @return void
 */
	public function startup() {
		$this->out(__d('migrations', 'Cake Migration Shell'));
		$this->hr();

		if (!empty($this->params['connection'])) {
			$this->connection = $this->params['connection'];
		}

		$this->migrationConnection = $this->_startMigrationConnection();

		if (!empty($this->params['plugin'])) {
			$this->type = $this->params['plugin'];
		}

		$this->path = $this->_getPath() . 'Config' . DS . 'Migration' . DS;

		$options = array(
			'precheck' => $this->params['precheck'],
			'autoinit' => !$this->params['no-auto-init'],
			'dry' => $this->params['dry']);

		if (!empty($this->connection)) {
			$options['connection'] = $this->connection;
		}

		if (!empty($this->migrationConnection)) {
			$options['migrationConnection'] = $this->migrationConnection;
		}

		$this->Version = new MigrationVersion($options);

		$this->_messages = array(
			'create_table' => __d('migrations', 'Creating table ":table".'),
			'drop_table' => __d('migrations', 'Dropping table ":table".'),
			'rename_table' => __d('migrations', 'Renaming table ":old_name" to ":new_name".'),
			'add_field' => __d('migrations', 'Adding field ":field" to table ":table".'),
			'drop_field' => __d('migrations', 'Dropping field ":field" from table ":table".'),
			'change_field' => __d('migrations', 'Changing field ":field" from table ":table".'),
			'rename_field' => __d('migrations', 'Renaming field ":old_name" to ":new_name" on table ":table".'),
			'add_index' => __d('migrations', 'Adding index ":index" to table ":table".'),
			'drop_index' => __d('migrations', 'Dropping index ":index" from table ":table".'),
		);
	}

/**
 * Get a list of connection names.
 *
 * @return array The list of connection names
 */
	protected function _connectionNamesEnum() {
		return array_keys(ConnectionManager::enumConnectionObjects());
	}

/**
 * Set a migration connection
 *
 * @return string The name of the migration connection.
 */
	protected function _startMigrationConnection() {
		if (!empty($this->params['connection']) && empty($this->params['migrationConnection'])) {
			return $this->in(
				"You did not set a migration connection (-i), which connection do you want to use?",
				$this->_connectionNamesEnum(),
				$this->params['connection']
			);
		}

		if (!empty($this->params['migrationConnection'])) {
			return $this->params['migrationConnection'];
		}
	}

/**
 * Get the option parser.
 *
 * @return void
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(
				'The Migration shell.' .
				'')
			->addOption('plugin', array(
				'short' => 'p',
				'help' => __d('migrations', 'Plugin name to be used')))
			->addOption('precheck', array(
				'short' => 'm',
				'default' => 'Migrations.PrecheckException',
				'help' => __d('migrations', 'Precheck migrations')))
			->addOption('force', array(
				'short' => 'f',
				'boolean' => true,
				'help' => __d('migrations', 'Force \'generate\' to compare all tables.')))
			->addOption('overwrite', array(
				'short' => 'o',
				'boolean' => true,
				'help' => __d('migrations', 'Overwrite the schema.php file after generated a migration.')))
			->addOption('connection', array(
				'short' => 'c',
				'default' => null,
				'help' => __d('migrations', 'Overrides the \'default\' connection of the MigrationVersion')))
			->addOption('migrationConnection', array(
				'short' => 'i',
				'default' => null,
				'help' => __d('migrations', 'Overrides the \'default\' connection of the CakeMigrations that are applied')))
			->addOption('dry', array(
				'short' => 'd',
				'boolean' => true,
				'default' => false,
				'help' => __d('migrations', 'Output the raw SQL queries rather than applying them to the database.')))
			->addOption('no-auto-init', array(
				'short' => 'n',
				'boolean' => true,
				'default' => false,
				'help' => __d('migrations', 'Disables automatic creation of migrations table and running any internal plugin migrations')))
			->addOption('schema-class', array(
				'short' => 's',
				'boolean' => false,
				'default' => false,
				'help' => __d('migrations', 'CamelCased Classname without the `Schema` suffix to use when reading or generating schema files. See `Console/cake schema generate --help`.')))
			->addSubcommand('status', array(
				'help' => __d('migrations', 'Displays a status of all plugin and app migrations.')))
			->addSubcommand('run', array(
				'help' => __d('migrations', 'Run a migration to given direction or version.')))
			->addSubcommand('generate', array(
				'help' => __d('migrations', 'Generates a migration file.')));
	}

/**
 * Override main
 *
 * @return void
 */
	public function main() {
		$this->out($this->getOptionParser()->help());
	}

/**
 * Run the migrations
 *
 * @return void
 */
	public function run() {
		try {
			$mapping = $this->Version->getMapping($this->type);
		} catch (MigrationVersionException $e) {
			$this->err($e->getMessage());
			return false;
		}

		if ($mapping === false) {
			$this->out(__d('migrations', 'No migrations available.'));
			return $this->_stop(1);
		}
		$latestVersion = $this->Version->getVersion($this->type);

		if (!isset($this->params['dry'])) {
			$this->params['dry'] = false;
		}

		$options = array(
			'precheck' => isset($this->params['precheck']) ? $this->params['precheck'] : null,
			'type' => $this->type,
			'dry' => $this->params['dry'],
			'callback' => &$this);

		$once = false; //In case of exception run shell again (all, reset, migration number)
		if (isset($this->args[0]) && in_array($this->args[0], array('up', 'down'))) {
			$once = true;
			$options = $this->_singleStepOptions($mapping, $latestVersion, $options);
		} elseif (isset($this->args[0]) && $this->args[0] === 'all') {
			end($mapping);
			$options['version'] = key($mapping);
			$options['direction'] = 'up';
		} elseif (isset($this->args[0]) && $this->args[0] === 'reset') {
			$options['version'] = 0;
			$options['direction'] = 'down';
		} else {
			$options = $this->_promptVersionOptions($mapping, $latestVersion);
		}

		$this->out(__d('migrations', 'Running migrations:'));
		if ($options === false) {
			return false;
		}
		$options += array(
			'type' => $this->type,
			'callback' => &$this,
			'dry' => $this->params['dry']
		);

		if ($options['dry']) {
			$this->out('');
			$this->out(__d('migrations', 'Migration will run dry, no database changes will be made'));
			$this->out('');
		}

		$result = $this->_execute($options, $once);
		if ($result !== true) {
			$this->out($result);
		}

		$this->out(__d('migrations', 'All migrations have completed.'));
		$this->out('');
		return true;
	}

/**
 * Execute migration
 *
 * @param array $options Options for migration
 * @param bool $once True to only run once, false to retry
 * @return bool True if success
 */
	protected function _execute($options, $once) {
		$result = true;
		try {
			$result = $this->Version->run($options);
			$this->_outputLog($this->Version->log);
		} catch (MigrationException $e) {
			$this->out(__d('migrations', 'An error occurred when processing the migration:'));
			$this->out('  ' . sprintf(__d('migrations', 'Migration: %s'), $e->Migration->info['name']));
			$this->out('  ' . sprintf(__d('migrations', 'Error: %s'), $e->getMessage()));

			$this->hr();

			$response = $this->in(__d('migrations', 'Do you want to mark the migration as successful?. [y]es or [a]bort.'), array('y', 'a'));

			if (strtolower($response) === 'y') {
				$this->Version->setVersion($e->Migration->info['version'], $this->type, $options['direction'] === 'up');
				if (!$once) {
					return $this->run();
				}
			} elseif (strtolower($response) === 'a') {
				return $this->_stop();
			}
			$this->hr();
		}
		return $result;
	}

/**
 * Output the SQL log in dry mode
 *
 * @param array $log List of queries per migration
 * @return void
 */
	protected function _outputLog($log) {
		foreach ($log as $migrationName => $queries) {
			if (empty($queries)) {
				continue;
			}

			$this->hr();
			$this->out(sprintf('SQL for migration %s:', $migrationName));
			$this->hr();
			foreach ($queries as $query) {
				$this->out(str_replace(array("\n", "\t"), '', $query));
			}
		}
		$this->hr();
	}

/**
 * Single step options for up/down migrations
 *
 * @param array $mapping Migration version mappings
 * @param string $latestVersion Latest migration version
 * @param array $default Default options for migration
 * @return array Modified options for migration
 */
	protected function _singleStepOptions($mapping, $latestVersion, $default = array()) {
		$options = $default;
		$versions = array_keys($mapping);
		$flipped = array_flip($versions);
		$versionNumber = isset($flipped[$latestVersion]) ? $flipped[$latestVersion] : -1;
		$options['direction'] = $this->args[0];

		if ($options['direction'] === 'up') {
			$latestVersion = isset($versions[$versionNumber + 1]) ? $versions[$versionNumber + 1] : -1;
		}
		if (!isset($mapping[$latestVersion])) {
			$this->out(__d('migrations', 'Not a valid migration version.'));
			return $this->_stop(2);
		}
		$options['version'] = $mapping[$latestVersion]['version'];
		return $options;
	}

/**
 * Output prompt with different migration versions to choose from
 *
 * @param array $mapping Migration version mappings
 * @param string $latestVersion Latest migration version
 * @return array User-chosen options for migration
 */
	protected function _promptVersionOptions($mapping, $latestVersion) {
		if (isset($this->args[0]) && is_numeric($this->args[0])) {
			$options['version'] = (int)$this->args[0];

			$valid = isset($mapping[$options['version']]) || ($options['version'] === 0 && $latestVersion > 0);
			if (!$valid) {
				$this->out(__d('migrations', 'Not a valid migration version.'));
				return $this->_stop();
			}
		} else {
			$this->_showInfo($mapping, $this->type);
			$this->hr();

			while (true) {
				$response = $this->in(__d('migrations', 'Please choose which version you want to migrate to. [q]uit or [c]lean.'));
				if (strtolower($response) === 'q') {
					return $this->_stop();
				} elseif (strtolower($response) === 'c') {
					$this->clear();
					continue;
				}

				$valid = is_numeric($response) && isset($mapping[(int)$response]);
				if ($valid) {
					$options['version'] = (int)$response;
					$direction = 'up';
					if (empty($mapping[(int)$response]['migrated'])) {
						$direction = 'up';
					} elseif ((int)$response <= $latestVersion) {
						$direction = 'down';
					}
					break;
				} else {
					$this->out(__d('migrations', 'Not a valid migration version.'));
				}
			}
			$this->hr();
		}
		return compact('direction') + $options;
	}

/**
 * Generate a new migration file
 *
 * @return void
 */
	public function generate() {
		$fromSchema = false;
		$this->Schema = $this->_getSchema();
		$migration = array('up' => array(), 'down' => array());
		$migrationName = '';
		$comparison = array();

		if (!empty($this->args)) {
			// If args are passed in from the command line, we just want to
			// generate a migration based on them - don't offer to compare to database
			$this->_generateFromCliArgs($migration, $migrationName, $comparison);
		} else {
			$oldSchema = $this->_getSchema($this->type);
			if ($oldSchema !== false) {
				$response = $this->in(__d('migrations', 'Do you want to compare the schema.php file to the database?'), array('y', 'n'), 'y');
				if (strtolower($response) === 'y') {
					$this->_generateFromComparison($migration, $oldSchema, $comparison);
					$this->_migrationChanges($migration);
					$fromSchema = true;
				} else {
					$response = $this->in(__d('migrations', 'Do you want to compare the database to the schema.php file?'), array('y', 'n'), 'y');
					if(strtolower($response) === 'y') {
						$this->_generateFromInverseComparison($migration, $oldSchema, $comparison);
						$this->_migrationChanges($migration);
						$fromSchema = false;
					}
				}
			} else {
				$response = $this->in(__d('migrations', 'Do you want to generate a dump from the current database?'), array('y', 'n'), 'y');
				if (strtolower($response) === 'y') {
					$this->_generateDump($migration);
					$fromSchema = true;
				}
			}
		}

		$this->_finalizeGeneratedMigration($migration, $migrationName, $fromSchema);

		if ($this->params['overwrite'] === true) {
			$this->_overwriteSchema();
		}

		if ($fromSchema && isset($comparison)) {
			$response = $this->in(__d('migrations', 'Do you want to update the schema.php file?'), array('y', 'n'), 'y');
			if (strtolower($response) === 'y') {
				$this->_updateSchema();
			}
		}
	}

	protected function _migrationChanges($migration) {
		if (empty($migration)) {
			$this->hr();
			$this->out(__d('migrations', 'No database changes detected.'));
			return $this->_stop();
		}
	}

/**
 * Generate a migration by comparing schema.php with the database.
 *
 * @param array &$migration Reference to variable of the same name in generate() method
 * @param array &$oldSchema Reference to variable of the same name in generate() method
 * @param array &$comparison Reference to variable of the same name in generate() method
 * @return void (The variables passed by reference are changed; nothing is returned)
 */
	protected function _generateFromComparison(&$migration, &$oldSchema, &$comparison) {
		$this->hr();
		$this->out(__d('migrations', 'Comparing schema.php to the database...'));

		if ($this->type !== 'migrations') {
			unset($oldSchema->tables['schema_migrations']);
		}
		$newSchema = $this->_readSchema();
		$comparison = $this->Schema->compare($oldSchema, $newSchema);
		$migration = $this->_fromComparison($migration, $comparison, $oldSchema->tables, $newSchema['tables']);
	}

/**
 * Generate a migration by comparing the database with schema.php.
 *
 * @param array &$migration Reference to variable of the same name in generate() method
 * @param array &$oldSchema Reference to variable of the same name in generate() method
 * @param array &$comparison Reference to variable of the same name in generate() method
 * @return void (The variables passed by reference are changed; nothing is returned)
 */
	protected function _generateFromInverseComparison(&$migration, &$oldSchema, &$comparison) {
		$this->hr();
		$this->out(__d('migrations', 'Comparing database to the schema.php...'));

		if ($this->type !== 'migrations') {
			unset($oldSchema->tables['schema_migrations']);
		}
		$database = $this->_readSchema();
		$comparison = $this->Schema->compare($database, $oldSchema);
		$migration = $this->_fromComparison($migration, $comparison, $oldSchema->tables, $database['tables']);
	}

/**
 * Generate a migration from arguments passed in at the command line
 *
 * @param array &$migration Reference to variable of the same name in generate() method
 * @param array &$migrationName Reference to variable of the same name in generate() method
 * @param array &$comparison Reference to variable of the same name in generate() method
 * @return void (The variables passed by reference are changed; nothing is returned)
 */
	protected function _generateFromCliArgs(&$migration, &$migrationName, &$comparison) {
		$this->hr();
		$this->out(__d('migrations', 'Generating migration from commandline arguments...'));

		$migrationName = array_shift($this->args);
		if (empty($migrationName)) {
			$this->error('Missing argument', "Missing required argument 'name' for migration");
		}

		$cli = $this->_parseCommandLineFields($migrationName);

		$action = $cli['action'];
		$table = $cli['table'];
		$tables = $cli['tables'];
		$fields = $cli['fields'];

		if ($action == 'create_table') {
			$migration['up']['create_table'][$table] = $fields;
			$migration['down']['drop_table'] = array($table);
		} elseif ($action == 'drop_table') {
			$migration['up']['drop_table'] = array($table);
			// We don't have a down case as the migration is irreversible
		} elseif ($action == 'create_field') {
			$migration['up']['create_field'][$table] = $fields;
			$migration['down']['drop_field'][$table] = $this->_fieldNamesArray($fields);
		} elseif ($action == 'drop_field') {
			$fieldsToDrop = array();
			$migration['up']['drop_field'][$table] = $this->_fieldNamesArray($fields);
			// We don't have a down case as the migration is irreversible
		} else {
			$this->error(__d('migrations', 'Invalid argument'), __d('migrations', "Migration name (%s) is invalid. It cannot be used to generate a migration from the CLI."));
		}
	}

/**
 * Return list of field names from array of field/index definitions
 *
 * @param array $fields Field/index definitions
 * @return array List of field names
 */
	protected function _fieldNamesArray($fields) {
		$fieldNames = array();
		foreach ($fields as $name => $value) {
			if ($name !== 'indexes') {
				$fieldNames[] = $name;
			}
		}
		return $fieldNames;
	}

/**
 * Generate a dump of the current database.
 *
 * @param array &$migration Reference to variable of the same name in generate() method
 * @return void (The variables passed by reference are changed; nothing is returned)
 */
	protected function _generateDump(&$migration) {
		$this->hr();
		$this->out(__d('migrations', 'Generating dump from the current database...'));

		$dump = $this->_readSchema();
		$dump = $dump['tables'];
		unset($dump['missing']);

		if (!empty($dump)) {
			$migration['up']['create_table'] = $dump;
			$migration['down']['drop_table'] = array_keys($dump);
		}
	}

/**
 * Finalizes the generated migration - offers to preview it,
 * prompts for a name, writes the file, and updates db version if needed.
 *
 * @param array &$migration Reference to variable of the same name in generate() method
 * @param array &$migrationName Reference to variable of the same name in generate() method
 * @param bool &$fromSchema Reference to variable of the same name in generate() method
 * @return void
 */
	protected function _finalizeGeneratedMigration(&$migration, &$migrationName, &$fromSchema) {
		$response = $this->in(__d('migrations', 'Do you want to preview the file before generation?'), array('y', 'n'), 'y');
		if (strtolower($response) === 'y') {
			$this->out($this->_generateMigration('Preview of migration', 'PreviewMigration', $migration));
		}

		$name = $migrationName;
		if (empty($name)) {
			$name = $this->_promptForMigrationName();
		}

		$this->out(__d('migrations', 'Generating Migration...'));
		$time = gmdate('U');
		$this->_writeMigration($name, $time, $migration);

		if ($fromSchema) {
			$this->Version->setVersion($time, $this->type);
		}

		$this->out('');
		$this->out(__d('migrations', 'Done.'));
	}

/**
 * Prompt the user for a name for their new migration.
 *
 * @return string
 */
	protected function _promptForMigrationName() {
		while (true) {
				$name = $this->in(__d('migrations', 'Please enter the descriptive name of the migration to generate:'));
			if (!preg_match('/^([A-Za-z0-9_]+|\s)+$/', $name) || is_numeric($name[0])) {
				$this->out('');
				$this->err(__d('migrations', 'Migration name (%s) is invalid. It must only contain alphanumeric characters and start with a letter.', $name));
			} elseif (strlen($name) > 255) {
				$this->out('');
				$this->err(__d('migrations', 'Migration name (%s) is invalid. It cannot be longer than 255 characters.', $name));
			} else {
				$name = str_replace(' ', '_', trim($name));
				break;
			}
		}
		return $name;
	}

/**
 * Displays a status of all plugin and app migrations
 *
 * @return void
 */
	public function status() {
		$types = CakePlugin::loaded();
		ksort($types);
		array_unshift($types, 'App');

		$outdated = (isset($this->args[0]) && $this->args[0] === 'outdated');
		foreach ($types as $name) {
			try {
				$type = Inflector::underscore($name);
				$mapping = $this->Version->getMapping($type);
				if ($mapping === false) {
					continue;
				}

				$version = $this->Version->getVersion($type);
				$latest = end($mapping);
				if ($outdated && $latest['version'] == $version) {
					continue;
				}

				$this->out(($type === 'app') ? 'Application' : $name . ' Plugin');
				$this->out('');
				$this->out(__d('migrations', 'Current version:'));
				if ($version != 0) {
					$info = $mapping[$version];
					$this->out('  #' . number_format($info['version'] / 100, 2, '', '') . ' ' . $info['name']);
				} else {
					$this->out('  ' . __d('migrations', 'None applied.'));
				}

				$this->out(__d('migrations', 'Latest version:'));
				$this->out('  #' . number_format($latest['version'] / 100, 2, '', '') . ' ' . $latest['name']);
				$this->hr();
			} catch (MigrationVersionException $e) {
				continue;
			}
		}
	}

/**
 * Shows a list of available migrations
 *
 * @param array $mapping Migration mapping
 * @param string $type Can be 'app' or a plugin name
 * @return void
 */
	protected function _showInfo($mapping, $type = null) {
		if ($type === null) {
			$type = $this->type;
		}

		$version = $this->Version->getVersion($type);
		if ($version != 0) {
			$info = $mapping[$version];
			$this->out(__d('migrations', 'Current migration version:'));
			$this->out('  #' . number_format($version / 100, 2, '', '') . '  ' . $info['name']);
			$this->hr();
		}

		$this->out(__d('migrations', 'Available migrations:'));
		foreach ($mapping as $version => $info) {
			$this->out('  [' . number_format($version / 100, 2, '', '') . '] ' . $info['name']);

			$this->out('        ', false);
			if ($info['migrated'] !== null) {
				$this->out(__d('migrations', 'applied') . ' ' . date('r', strtotime($info['migrated'])));
			} else {
				$this->out(__d('migrations', 'not applied'));
			}
		}
	}

/**
 * Generate a migration string using comparison
 *
 * @param array $migration Migration instructions array
 * @param array $comparison Result from CakeSchema::compare()
 * @param array $oldTables List of tables on schema.php file
 * @param array $currentTables List of current tables on database
 * @return array
 */
	protected function _fromComparison($migration, $comparison, $oldTables, $currentTables) {
		foreach ($comparison as $table => $actions) {
			if (!isset($oldTables[$table])) {
				if (isset($actions['add'])) {
					$migration['up']['create_table'][$table] = $actions['add'];
				} elseif (isset($actions['create'])) {
					$migration['up']['create_table'][$table] = $actions['create'];
				}
				$migration['down']['drop_table'][] = $table;
				continue;
			}

			foreach ($actions as $type => $fields) {
				$indexes = array();
				if (!empty($fields['indexes'])) {
					$indexes = array('indexes' => $fields['indexes']);
					unset($fields['indexes']);
				}

				if ($type === 'add') {
					$migration['up']['create_field'][$table] = array_merge($fields, $indexes);

					$migration['down']['drop_field'][$table] = array_keys($fields);
					if (!empty($indexes['indexes'])) {
						$migration['down']['drop_field'][$table]['indexes'] = array_keys($indexes['indexes']);
					}
				} elseif ($type === 'change') {
					foreach ($fields as $name => $col) {
						if (!empty($oldTables[$table][$name]['length']) && substr($col['type'], 0, 4) === 'date') {
							$fields[$name]['length'] = null;
						}
					}
					$migration['up']['alter_field'][$table] = $fields;
					$migration['down']['alter_field'][$table] = array_intersect_key($oldTables[$table], $fields);
				} else {
					$migration['up']['drop_field'][$table] = array_keys($fields);
					if (!empty($indexes['indexes'])) {
						$migration['up']['drop_field'][$table]['indexes'] = array_keys($indexes['indexes']);
					}

					$migration['down']['create_field'][$table] = array_merge($fields, $indexes);
				}
			}
		}

		foreach ($oldTables as $table => $fields) {
			if (!isset($currentTables[$table])) {
				$migration['up']['drop_table'][] = $table;
				$migration['down']['create_table'][$table] = $fields;
			}
		}
		return $migration;
	}

/**
 * Gets the schema class name
 *
 * @param string $name Can be 'app' or a plugin name
 * @param bool $suffix Return the class name with or without the "Schema" suffix, default is true
 * @return string Returns the schema class name
 */
	protected function _getSchemaClassName($name = null, $suffix = true) {
		if (empty($name)) {
			$name = $this->type;
		}
		if (!empty($this->params['schema-class'])) {
			$name = $this->params['schema-class'];
		}
		$name = preg_replace('/[^a-zA-Z0-9]/', '', $name);
		$name = Inflector::camelize($name);
		if ($suffix === true && (substr($name, -6) !== 'Schema')) {
			$name .= 'Schema';
		} elseif ($suffix === false && (substr($name, -6) === 'Schema')) {
			$name = substr($name, 0, -6);
		}
		return $name;
	}

/**
 * Load and construct the schema class if exists
 *
 * @param string $type Can be 'app' or a plugin name
 * @return mixed False in case of no file found, schema object
 */
	protected function _getSchema($type = null) {
		if ($type === null) {
			$plugin = ($this->type === 'app') ? null : $this->type;
			return new CakeSchema(array('connection' => $this->connection, 'plugin' => $plugin));
		}

		$folder = new Folder($this->_getPath($type) . 'Config' . DS . 'Schema');
		$schema_files = $folder->find('.*schema.*.php');

		if (count($schema_files) === 0) {
			return false;
		}

		$name = $this->_getSchemaClassName($type);
		$file = $this->_findSchemaFile($folder, $schema_files, $name);

		if ($type === 'app' && empty($file)) {
			$appDir = preg_replace('/[^a-zA-Z0-9]/', '', APP_DIR);
			$name = Inflector::camelize($appDir) . 'Schema';
			$file = $this->_getPath($type) . 'Config' . DS . 'Schema' . DS . 'schema.php';
		}

		require_once $file;

		$plugin = ($type === 'app') ? null : $type;
		$schema = new $name(array('connection' => $this->connection, 'plugin' => $plugin));
		return $schema;
	}

/**
 * Finds schema file
 *
 * @param Folder $folder Folder object with schema folder path.
 * @param string $schema_files Schema files inside schema folder.
 * @param string $name Schema-class name.
 * @return mixed null in case of no file found, schema file.
 */
	protected function _findSchemaFile($folder, $schema_files, $name) {
		foreach ($schema_files as $schema_file) {
			$file = new File($folder->pwd() . DS . $schema_file);
			$content = $file->read();
			if (strpos($content, $name) !== false) {
				return $file->path;
			}
		}
		return null;
	}

/**
 * Reads the schema data
 *
 * @return array
 */
	protected function _readSchema() {
		$read = $this->Schema->read(array('models' => !$this->params['force']));
		if ($this->type !== 'migrations') {
			unset($read['tables']['schema_migrations']);
		}
		return $read;
	}

/**
 * Update the schema, making a call to schema shell
 *
 * @return void
 */
	protected function _updateSchema() {
		$command = 'schema generate --connection ' . $this->connection;
		if (!empty($this->params['plugin'])) {
			$command .= ' --plugin ' . $this->params['plugin'];
		}
		if ($this->params['force']) {
			$command .= ' --force';
		}
		$command .= ' --file schema.php --name ' . $this->_getSchemaClassName($this->type, false);
		$this->dispatchShell($command);
	}

/**
 * Overwrite the schema.php file
 *
 * @return void
 */

	protected function _overwriteSchema() {
		$options = array();
		if ($this->params['force']) {
			$options['models'] = false;
		} elseif (!empty($this->params['models'])) {
			$options['models'] = String::tokenize($this->params['models']);
		}

		$cacheDisable = Configure::read('Cache.disable');
		Configure::write('Cache.disable', true);

		$content = $this->Schema->read($options);
		$file = 'schema.php';

		Configure::write('Cache.disable', $cacheDisable);

		if (!empty($this->params['exclude']) && !empty($content)) {
			$excluded = String::tokenize($this->params['exclude']);
			foreach ($excluded as $table) {
				unset($content['tables'][$table]);
			}
		}

		if ($this->Schema->write($content)) {
			$this->out(__d('cake_console', 'Schema file: %s generated', $file));
			return $this->_stop();
		}
		$this->err(__d('cake_console', 'Schema file: %s generated'));
		return $this->_stop();
	}

/**
 * Parse fields from the command line for use with generating new migration files
 *
 * @param string $name Name of migration
 * @return array
 */
	protected function _parseCommandLineFields($name) {
		$connection = $this->connection;
		if (empty($connection)) {
			$connection = 'default';
		}
		$db = ConnectionManager::getDataSource($connection);
		$validTypes = array_keys($db->columns);

		$fields = array();
		$indexes = array();
		foreach ($this->args as $field) {
			$this->_parseSingleCommandLineField($fields, $indexes, $field, $validTypes);
		}

		// indexes should be the last key in the $fields array - hence why we only add it now.
		if (!empty($indexes)) {
			$fields['indexes'] = $indexes;
		}

		$action = null;
		$table = null;
		$tables = array();
		if (preg_match('/^(create|drop)_(.*)/', $name, $matches)) {
			$action = $matches[1] . '_table';
			$table = Inflector::tableize(Inflector::pluralize($matches[2]));
		} elseif (preg_match('/^(add)_.*_(?:to)_(.*)/', $name, $matches)) {
			$action = 'create_field';
			$table = Inflector::tableize(Inflector::pluralize($matches[2]));
		} elseif (preg_match('/^(remove)_.*_(?:from)_(.*)/', $name, $matches)) {
			$action = 'drop_field';
			$table = Inflector::tableize(Inflector::pluralize($matches[2]));
		} else {
			$this->error(__d('migrations', 'Invalid argument'), __d('migrations', "Missing required argument 'name' for migration"));
		}

		return compact('action', 'table', 'tables', 'fields');
	}

/**
 * Parse a single argument from the command line into the fields array
 *
 * @param array &$fields Reference to variable of same name in _parseCommandLineFields()
 * @param array &$indexes Reference to variable of same name in _parseCommandLineFields()
 * @param string $field A single command line argument - eg. 'id:primary_key' or 'name:string'
 * @param array $validTypes Valid data types for the relevant database - eg. string, integer, biginteger, etc.
 * @return void
 */
	protected function _parseSingleCommandLineField(&$fields, &$indexes, $field, $validTypes) {
		if (preg_match('/^(\w*)(?::(\w*))?(?::(\w*))?(?::(\w*))?/', $field, $matches)) {
			$field = $matches[1];
			$type = empty($matches[2]) ? null : $matches[2];
			$indexType = empty($matches[3]) ? null : $matches[3];
			$indexName = empty($matches[4]) ? null : $matches[4];
			$indexUnique = false;

			$type = $this->_getFieldType($field, $type, $validTypes);

			if ($type == 'primary_key') {
				$type = 'integer';
				$indexType = 'primary';
			}

			$fields[$field] = array(
				'type' => $type,
				'null' => false,
				'default' => null,
			);

			if ($indexType == null && $field == 'id') {
				$indexType = 'primary';
			}

			if ($indexType !== null) {
				$fields[$field]['key'] = $indexType;

				if ($indexType == 'primary') {
					$indexName = 'PRIMARY';
					$indexUnique = true;
					$indexType = null;
				} elseif ($indexType == 'unique') {
					$indexUnique = true;
					$indexType = null;
				}

				if (empty($indexName)) {
					if ($indexUnique) {
						$indexName = strtoupper('UNIQUE_' . $field);
					} else {
						$indexName = strtoupper('BY_' . $field);
					}
				}

				$indexes[$indexName] = array(
					'column' => $field,
					'unique' => $indexUnique,
				);

				if ($indexType !== null) {
					$fields['indexes'][$indexName]['type'] = $indexType;
				}
			}
		}
	}

/**
 * Return valid field type based on name of field
 *
 * @param string $field Name of field
 * @param string $type Current type
 * @param array $validTypes List of valid types
 * @return string Recognized type (eg. integer vs bigint)
 */
	protected function _getFieldType($field, $type, $validTypes) {
		if (!in_array($type, $validTypes)) {
			if ($field == 'id') {
				$type = 'integer';
			} elseif (in_array($field, array('created', 'modified', 'updated'))) {
				$type = 'datetime';
			} else {
				$type = 'string';
			}
		}

		return $type;
	}

/**
 * Generate a migration
 *
 * @param string $name Name of migration
 * @param string $class Class name of migration
 * @param array $migration Migration instructions array
 * @return string
 */
	protected function _generateMigration($name, $class, $migration) {
		$content = '';
		foreach ($migration as $direction => $actions) {
			$content .= "\t\t'" . $direction . "' => array(\n";
			foreach ($actions as $type => $tables) {
				$content .= "\t\t\t'" . $type . "' => array(\n";
				if ($type === 'create_table' || $type === 'create_field' || $type === 'alter_field') {
					foreach ($tables as $table => $fields) {
						$content .= "\t\t\t\t'" . $table . "' => array(\n";
						foreach ($fields as $field => $col) {
							if ($field === 'indexes') {
								$content .= "\t\t\t\t\t'indexes' => array(\n";
								foreach ($col as $index => $key) {
									$content .= "\t\t\t\t\t\t'" . $index . "' => array(" . implode(', ', $this->_values($key)) . "),\n";
								}
								$content .= "\t\t\t\t\t),\n";
							} else {
								$content .= "\t\t\t\t\t'" . $field . "' => ";
								if (is_string($col)) {
									$content .= "'" . $col . "',\n";
								} else {
									$content .= 'array(' . implode(', ', $this->_values($col)) . "),\n";
								}
							}
						}
						$content .= "\t\t\t\t),\n";
					}
				} elseif ($type === 'drop_table') {
					$content .= "\t\t\t\t'" . implode("', '", $tables) . "'\n";
				} elseif ($type === 'drop_field') {
					foreach ($tables as $table => $fields) {
						$indexes = array();
						if (!empty($fields['indexes'])) {
							$indexes = $fields['indexes'];
						}
						unset($fields['indexes']);

						$content .= "\t\t\t\t'" . $table . "' => array(";
						if (!empty($fields)) {
							$content .= "'" . implode("', '", $fields) . "'";
						}
						if (!empty($fields) && !empty($indexes)) {
							$content .= ", ";
						}
						if (!empty($indexes)) {
							$content .= "'indexes' => array('" . implode("', '", $indexes) . "')";
						}
						$content .= "),\n";
					}
				}
				$content .= "\t\t\t),\n";
			}
			$content .= "\t\t),\n";
		}
		$content = $this->_generateTemplate('migration', array('name' => $name, 'class' => $class, 'migration' => $content));
		$content = str_replace('=> NULL', '=> null', $content);
		return $content;
	}

/**
 * Write a migration with given name
 *
 * @param string $name Name of migration
 * @param int $version The version number (timestamp)
 * @param array $migration Migration instructions array
 * @return bool
 */
	protected function _writeMigration($name, $version, $migration) {
		$content = '';
		$content = $this->_generateMigration($name, Inflector::camelize($name), $migration);
		$File = new File($this->path . $version . '_' . strtolower($name) . '.php', true);
		return $File->write($content);
	}

/**
 * Format a array/string into a one-line syntax
 *
 * @param array $values Array to be converted
 * @return string
 */
	protected function _values($values) {
		$_values = array();
		if (is_array($values)) {
			foreach ($values as $key => $value) {
				if (is_array($value)) {
					$_values[] = "'" . $key . "' => array('" . implode("', '", $value) . "')";
				} elseif (!is_numeric($key)) {
					$value = var_export($value, true);
					$_values[] = "'" . $key . "' => " . $value;
				}
			}
		}
		return $_values;
	}

/**
 * Include and generate a template string based on a template file
 *
 * @param string $template Template file name
 * @param array $vars List of variables to be used on tempalte
 * @return string
 */
	protected function _generateTemplate($template, $vars) {
		extract($vars);
		ob_start();
		ob_implicit_flush(0);
		include dirname(__FILE__) . DS . 'Templates' . DS . $template . '.ctp';
		$content = ob_get_clean();

		return $content;
	}

/**
 * Return the path used
 *
 * @param string $type Can be 'app' or a plugin name
 * @return string Path used
 */
	protected function _getPath($type = null) {
		if ($type === null) {
			$type = $this->type;
		}
		if ($type !== 'app') {
			return App::pluginPath($type);
		}
		return APP;
	}

/**
 * Callback used to display what migration is being runned
 *
 * @param CakeMigration &$Migration Migration being performed
 * @param string $direction Direction being runned
 * @return void
 */
	public function beforeMigration(&$Migration, $direction) {
		$this->out('  [' . number_format($Migration->info['version'] / 100, 2, '', '') . '] ' . $Migration->info['name']);
	}

/**
 * Callback used to create a new line after the migration
 *
 * @param CakeMigration &$Migration Migration being performed
 * @param string $direction Direction being runned
 * @return void
 */
	public function afterMigration(&$Migration, $direction) {
		$this->out('');
	}

/**
 * Callback used to display actions being performed
 *
 * @param CakeMigration &$Migration Migration being performed
 * @param string $type Type of action. i.e: create_table, drop_table, etc.
 * @param array $data Data to send to the callback
 * @return void
 */
	public function beforeAction(&$Migration, $type, $data) {
		if (isset($this->_messages[$type])) {
			$message = String::insert($this->_messages[$type], $data);
			$this->out('      > ' . $message);
		}
	}

}
