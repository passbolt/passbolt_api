<?php
/**
 * Api Index generation shell
 *
 * Helps generate and maintain Api Class index.
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.vendors.shells
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::import('Core', 'ClassRegistry');

/**
* Api Index Shell
*/
class ApiIndexShell extends Shell {
/**
 * Tasks used in the shell
 *
 * @var Array
 **/
	public $tasks = array('DbConfig');
/**
 * Holds ApiClass instance
 *
 * @var ApiClass
 **/
	public $ApiClass;
/**
 * instance of ApiFile
 *
 * @var ApiFile
 **/
	public $ApiFile;
/**
 * Holds current config
 *
 * @var ApiClass
 **/
	public $config = array();
/**
 * startup method
 *
 * @return void
 **/
	public function startup() {
		if ($this->command && !in_array($this->command, array('help'))) {
			if (!config('database')) {
				$this->out(__d('api_generator', "Your database configuration was not found. Take a moment to create one."), true);
				$this->args = null;
				return $this->DbConfig->execute();
			}

			if (!in_array($this->command, array('initdb', 'help'))) {
				$this->ApiFile = ClassRegistry::init('ApiGenerator.ApiFile');
			}
		}
	}

/**
 * Initialize the database and insert the schema.
 *
 * @return void
 **/
	public function initdb() {
		$this->dispatchShell('schema create --name ApiGenerator --plugin ApiGenerator');
	}

/**
 * Initialize the database and insert the schema.
 *
 * @return void
 **/
	public function set_routes() {
		$Routes = new File(CONFIGS . 'routes.php');
		$add = array(
			"Router::connect('/classes', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'index'));",
			"Router::connect('/class/*', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'view_class'));",
			"Router::connect('/source/*', array('plugin' => 'api_generator', 'controller' => 'api_files', 'action' => 'source'));",
			"Router::connect('/files/*', array('plugin' => 'api_generator', 'controller' => 'api_files', 'action' => 'files'));",
			"Router::connect('/packages', array('plugin' => 'api_generator', 'controller' => 'api_packages', 'action' => 'index'));",
			"Router::connect('/package/*', array('plugin' => 'api_generator', 'controller' => 'api_packages', 'action' => 'view'));",
			"Router::connect('/file/*', array('plugin' => 'api_generator', 'controller' => 'api_files', 'action' => 'view_file'));",
			"Router::connect('/view_source/*', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'view_source'));",
			"Router::connect('/search/*', array('plugin' => 'api_generator', 'controller' => 'api_classes', 'action' => 'search'));"
		);
		$currentRoutes = trim($Routes->read());
		$new = array();
		foreach ($add as $newRoute) {
			if (strpos($currentRoutes, $newRoute) === false) {
				$new[] = $newRoute;
			}
		}
		$data = rtrim($currentRoutes, "?>") . "\n\n\t" . join("\n\t", $new);
		if ($Routes->write($data)) {
			$this->out(__d('api_generator', 'Routes file updated'));
			return;
		}
		$this->out(__d('api_generator', 'Routes file NOT updated'));
		return;
	}

/**
 * Update the Api Class index.
 *
 * @return void
 **/
	public function update() {
		$config = $this->config();

		if (empty($config['paths'])) {
			$this->err('Config could not be found');
			return false;
		}

		$this->out('Clearing index and regenerating class index...');
		$this->ApiClass = ClassRegistry::init('ApiGenerator.ApiClass');
		$this->ApiPackage = ClassRegistry::init('ApiGenerator.ApiPackage');

		$this->ApiClass->clearIndex();
		$this->ApiPackage->clearIndex();
		$this->ApiFile->importCoreClasses();

		$foundClasses = array();
		foreach (array_keys($config['paths']) as $path) {
			$fileList = $this->ApiFile->fileList($path);
			foreach ($fileList as $file) {
				try {
					$docsInFile = $this->ApiFile->loadFile($file);
				} catch (Exception $e) {
					$this->err(__('<error>Error:</error> %s', $e->getMessage()));
				}
				foreach ($docsInFile['class'] as $classDocs) {
					$className = $classDocs->getName();
					$this->ApiClass->create();
					if (!isset($foundClasses[$className]) && $this->ApiClass->saveClassDocs($classDocs)) {
						$this->out('Added docs for ' . $classDocs->name . ' to index',  1, Shell::VERBOSE);
						$foundClasses[$className] = true;
						try {
							$packages = $this->ApiPackage->parsePackage($classDocs->classInfo['comment']);
							$lastPackage = $this->ApiPackage->updatePackageTree($packages);
							if (!$lastPackage) {
								$lastPackage = $this->ApiPackage->findEndPackageId($packages);
							}
							$this->ApiClass->saveField('api_package_id', $lastPackage);
						} catch (Exception $e) {
							$this->out(sprintf(
								'<warning>Warning:</warning> %s does not have any packages.',
								$classDocs->getName()
							));
						}
					}
				}
				if (!empty($docsInFile['function'])) {
					$this->ApiClass->create();
					if ($this->ApiClass->savePseudoClassDocs($docsInFile['function'], $file)) {
						$this->out('Added docs for global functions in ' . basename($file), 1, Shell::VERBOSE);
					}
				}
			}
		}

		$this->out('<success>Class index Regenerated.</success>');
	}
/**
 * Show the list of files that will be parsed.
 *
 * @return void
 **/
	public function showfiles() {
		$config = $this->config();

		if (empty($config['paths'])) {
			$this->err('Config could not be found');
			return false;
		}

		$this->out('The following files will be parsed when generating the API class index:');
		$this->hr();
		$this->out('');
		foreach (array_keys($config['paths']) as $path) {
			$files = $this->ApiFile->fileList($path);
			$this->_paginate($files);
		}
	}
/**
 * Pagiantion of long file lists
 *
 * @return void
 **/
	protected function _paginate($list) {
		if (count($list) > 20) {
			$chunks = array_chunk($list, 10);
			$chunkCount = count($chunks);
			$this->out(implode("\n", array_shift($chunks)));
			$chunkCount--;
			while ($chunkCount && null == $this->in('Press <return> to see next 10 files')) {
				$this->out(implode("\n", array_shift($chunks)));
				$chunkCount--;
			}
		} else {
			$this->out(implode("\n", $list));
		}
	}
/**
 * Shows a warning about default / no filePath been stored in Configure.
 *
 * @return void
 **/
	protected function config() {
		$this->ApiConfig = ClassRegistry::init('ApiGenerator.ApiConfig');

		if (empty($this->config)) {
			$config = $this->ApiConfig->read();
			if (!empty($config)) {
				return $config;
			}
		}

		$config = array();

		$this->hr();
		$this->out('api_config.ini could not be located.');
		$this->out('Answer some questions to build it.');
		$this->hr();

		$path = null;

		while($path == null && $path != 'q') {
			$path = $this->in('Enter the path to the codebase.', '', APP);
			if ($path[0] != '/' && $path[1] != ':') {
				$path = $this->params['working'] . DS . $path;
			}
			if (file_exists($path)) {
				$config['paths'][$path] = true;
			}

			$stop = $this->in('Would you like to add another path?', array('y', 'n', 'q'), 'n');
			if ($stop == 'y') {
				$path = null;
			}
		}
		$this->hr();
		$this->out('Setup some excludes');
		$this->out('excludes remove files, folders, properties and methods from the index.');
		$this->out('Input a comma separated list for multiple options');
		$this->out('to continue, just answer "n"');
		$this->hr();

		$exclude = null;
		$exclude = $this->in('Exclude properties of the following types (private, protected, static)', '', 'private');
		if ($exclude != 'q') {
			$config['exclude']['properties'] = $exclude;
		}

		$exclude = $this->in('Exclude methods of the following types (private, protected, static)', '', 'private');
		if ($exclude != 'n') {
			$config['exclude']['methods'] = $exclude;
		}

		$exclude = $this->in('Comma separated list of directories to exclude', '', 'n');
		if ($exclude != 'n') {
			$config['exclude']['directories'] = $exclude;
		}

		$exclude = $this->in('Comma separated list of files to exclude', '', 'n');
		if ($exclude != 'n') {
			$config['exclude']['files'] = $exclude;
		}

		$this->hr();
		$this->out('About the files in your codebase');
		$this->out('input a comma separated list for multiple options');
		$this->out('to continue, just answer "n"');
		$this->hr();

		$extensions = null;
		while($extensions == null && $extensions != 'n') {
			$extensions = $this->in('Extensions to parse (php, ctp, tpl)', '', 'php');
			if ($extensions != 'n') {
				$config['file']['extensions'] = $extensions;
			}
		}

		$regex = null;
		while($regex == null && $regex != 'n') {
			$regex = $this->in('Regex for matching files', '', '[A-Za-z_\-0-9]+');
			if ($regex != 'n') {
				$config['file']['regex'] = $regex;
			}
		}

		$this->hr();
		$this->out('Do you have some classes that do not map to a filename?');
		$this->out('to continue, just answer "n"');
		$this->hr();

		$mapping = null;
		while ($mapping == null && $mapping != 'n') {
			$class = $this->in('Class to map', '', 'n');
			if ($class == 'n') {
				$mapping = 'n';
			} else {
				$file = null;
				while ($file == null && $file != 'n') {
					$file = $this->in('Enter the path to the file that holds ' . $class .'. this can be relative to the default path, or add a / in front to use an absolute path', '', $path);
					if ($file[0] != '/') {
						$file = $path . DS . $file;
					}
					if (file_exists($file)) {
						$mapping = true;
						$config['mappings'][$class] = $file;
					} else {
						$this->out('File could not be found');
					}
				}
				$stop = $this->in('Add another mapping?', array('y', 'n', 'q'), 'n');
				if ($stop == 'y') {
					$mapping = null;
				}
			}
		}

		$this->hr();
		$this->out('Create a username/password for accessing the admin areas');

		$config = $this->_addUsers($config);

		$this->out('Verify the config');
		$this->hr();
		$string = $this->ApiConfig->toString($config);
		$this->out($string);
		$this->hr();
		$looksGood = $this->in('Does the config look correct?', array('y', 'n'), 'n');

		if ($this->ApiConfig->save($string)) {
			$this->out('The config was saved');
		}
		$this->config = $config;
		return $config;
	}
	
/**
 * Add Users to your config file
 *
 * @return void
 **/
	public function users() {
		$config = $this->config();
		if (empty($config['users']) || isset($this->args[0]) && strtolower($this->args[0]) == 'add') {
			$config = $this->_addUsers($config);
		} else {
			$add = $this->_listUsers($config);
			if (strtolower($add) == 'y') {
				$config = $this->_addUsers($config);
			}
		}
		$string = $this->ApiConfig->toString($config);
		if ($this->ApiConfig->save($string)) {
			$this->out('The config was saved');
		}
	}
/**
 * List users in the config file.
 *
 * @param array $config Config values to display
 * @return void
 **/
	protected function _listUsers($config) {
		$this->out('Current Users:');
		$this->hr();
		if (empty($config['users'])) {
			$this->out(__d('api_generator', 'You have no users :('));
		} else {
			foreach ($config['users'] as $user => $pass) {
				$this->out($user . ' : ' . $pass);
			}
		}
		return $this->in('Create new users?', array('y', 'n'), 'n');
	}
/**
 * Add users to Api list
 *
 * @param array $config Config values to mess with
 * @return void
 **/
	protected function _addUsers($config) {
		$continue = true;
		while ($continue === true) {
			$user = $this->in('Enter a username for the admin areas');
			$password = $this->in('Enter a password for ' . $user);
			$config['users'][$user] = $password;
			$again = $this->in('Add another user?', array('y', 'n'), 'n');
			if ($again == 'n') {
				$continue = false;
			}
		}
		return $config;
	}

/**
 * get the option parser
 *
 * @return void
 */
	public function getOptionParser() {
		return parent::getOptionParser()
			->description("Api Generator Class index tool.

Used for building the index tables that ApiGenerator uses.  Also allows you to create the schema, add admin users and routes to your application."
			)->addSubcommand('initdb', array(
				'help' => 'Create the schema used for the ApiGenerator plugin.'
			))->addSubcommand('showfiles', array(
				'help' => 'Show the list of files that will be parsed for classes based on your configuration.' .
					'Use to check if your config is going to parse the files you want.'
			))->addSubcommand('update', array(
				'help' => 'Clear the existing class index and regenerate it.'
			))->addSubcommand('set_routes', array(
				'help' => 'Add routes for Api generator to your routes file.'
			))->addSubcommand('users', array(
				'help' => 'View list of users able to access admin features, and create new ones.'
			));
	}

}
