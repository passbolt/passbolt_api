<?php
/**
 * Api File Model
 *
 * For interacting with the Filesystem specified by ApiGenerator.filePath
 *
 * PHP 5.2+
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2008-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org
 * @package       api_generator
 * @subpackage    api_generator.models
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
App::import('Lib', 'ApiGenerator.DocumentorFactory');

class ApiFile extends Object {
/**
 * Name
 *
 * @var string
 */
	public $name = 'ApiFile';
/**
 * A list of folders to ignore.
 *
 * @var array
 **/
	public $excludeDirectories = array();
/**
 * excludeMethods property
 *
 * @var array
 */
	public $excludeMethods = array();
/**
 * excludeProperties property
 *
 * @var array
 */
	public $excludeProperties = array();
/**
 * A list of files to ignore.
 *
 * @var array
 **/
	public $excludeFiles = array();
/**
 * a list of extensions to scan for
 *
 * @var array
 **/
	public $allowedExtensions = array();
/**
 * Array of class dependancies map
 *
 * @var array
 **/
	public $dependencyMap = array();
/**
 * Mappings of funny named classes to files
 *
 * @var string
 **/
	public $classMap = array();
/**
 * A regexp for file names. (will be made case insenstive)
 *
 * @var string
 **/
	public $fileRegExp = '[a-z_\-0-9]+';
/**
 * Folder instance
 *
 * @var Folder
 **/
	protected $_Folder;
/**
 * ApiConfig Model instance
 *
 * @var object
 **/
	public $ApiConfig;
/**
 * Current Extractor instance
 *
 * @var object
 **/
	protected $_extractor;
/**
 * storage for defined classes
 *
 * @var array
 **/
	protected $_definedClasses = array();
/**
 * storage for defined functions
 *
 * @var array
 **/
	protected $_definedFunctions = array();
/**
 * Constructor
 *
 * @return void
 **/
	public function __construct() {
		parent::__construct();
		$this->ApiConfig = ClassRegistry::init('ApiGenerator.ApiConfig');
		$this->_initConfig();
		$this->_Folder = new Folder(APP);
	}
/**
 * Read a path and return files and folders not in the excluded Folder list
 *
 * @param string $path The absolute path you wish to read.
 * @return array
 **/
	public function read($path) {
		if (preg_match('|\.\.|', $path)) {
			return array(array(), array());
		}
		$this->_Folder->cd($path);
		$length = strlen($path);
		if (substr($path, -1) !== DS) {
			$length++;
		}
		$ignore = $this->excludeFiles;
		$ignore[] = '.';
		$contents = $this->_Folder->read(true, $ignore);
		$this->_filterFolders($contents[0], false);
		$this->_filterFiles($contents[1]);
		return $contents;
	}
/**
 * Recursive Read a path and return files and folders not in the excluded Folder list
 *
 * @param string $path The path you wish to read.
 * @return array
 **/
	public function fileList($path) {
		$this->_Folder->cd($path);
		$filePattern =  $this->fileRegExp . '\.' . implode('|', $this->allowedExtensions);
		$contents = $this->_Folder->findRecursive($filePattern);
		$this->_filterFolders($contents);
		$this->_filterFiles($contents);
		return $contents;
	}
/**
 * _filterFiles
 *
 * Filter a file list and remove excludeDirectories
 *
 * @param array $files List of files to filter and ignore. (reference)
 * @return void
 **/
	protected function _filterFolders(&$fileList, $recursiveList = true) {
		$count = count($fileList);
		foreach ($this->excludeDirectories as $blackListed) {
			if ($recursiveList) {
				$blackListed = DS . $blackListed . DS;
			}
			for ($i = 0; $i < $count; $i++) {
				if (isset($fileList[$i]) && strpos($fileList[$i], $blackListed) !== false) {
					unset($fileList[$i]);
				}
			}
		}
		$fileList = array_values($fileList);
	}
/**
 * remove files that don't match the allowedExtensions
 * or are on the excludeFiles list
 *
 * @return void
 **/
	protected function _filterFiles(&$fileList) {
		foreach ($this->excludeFiles as $ignored) {
			$fileCount = count($fileList);
			$fileList = array_values($fileList);
			for ($i = 0; $i < $fileCount; $i++) {
				$basename = basename($fileList[$i]);
				if ($ignored == $basename) {
					unset($fileList[$i]);
				}
			}
		}
		if (!empty($this->allowedExtensions)) {
			$extPattern = '/\.' . implode('|', $this->allowedExtensions) . '$/i';
			foreach ($fileList as $i => $file) {
				if (!preg_match($extPattern, $file)) {
					unset($fileList[$i]);
				}
			}
		}
		$fileList = array_values($fileList);
	}
/**
 * Loads the documentation extractor for a given classname.
 *
 * @param string $name Name of class to load.
 * @access public
 * @return void
 */
	public function loadExtractor($type, $name) {
		$this->_extractor = DocumentorFactory::getReflector($type, $name);
	}
/**
 * Get the documentor extractor instance
 *
 * @access public
 * @return object
 */
	public function getExtractor() {
		return $this->_extractor;
	}
/**
 * Gets the parsed docs from the Extractor
 *
 * @return object Extractor with all docs processed.
 **/
	public function getDocs() {
		if (!$this->_extractor) {
			return array();
		}
		$this->_extractor->getAll();
		return $this->_extractor;
	}
/**
 * Load A File and extract docs for all classes contained in that file
 *
 * Options:
 * - 'useIndex' boolean whether or not a search should be done on the ApiClass index for any missing classes
 *   defaults to false.
 *
 * @param string $fullPath FullPath of the file you want to load.
 * @param array $options Options to use see above
 * @return array Array of all the docs from all the classes that were loaded as a result of the file being loaded.
 * @throws MissingClassException If a dependancy cannot be solved, an exception will be thrown.
 **/
	public function loadFile($filePath, $options = array()) {
		$docs = array('class' => array(), 'function' => array());
		if (preg_match('|\.\.|', $filePath)) {
			return $docs;
		}
		if (!$this->isAllowed($filePath)) {
			throw new Exception(__d('api_generator', '%s is not accesible or does not exist.', $filePath));
		}
		$this->_importCakeBaseClasses($filePath);
		$this->_resolveDependancies($filePath, $options);
		$this->_getDefinedObjects();
		$newObjects = $this->findObjectsInFile($filePath);
		foreach ($newObjects as $type => $objects) {
			foreach ($objects as $element) {
				$this->loadExtractor($type, $element);
				if ($type == 'function' && basename($this->_extractor->getFileName()) != basename($filePath)) {
					continue;
				}
				$docs[$type][$element] = $this->getDocs();
			}
		}
		return $docs;
	}
/**
 * Import the core classes (Controller, View, Helper, Model)
 *
 * @return void
 **/
	public function importCoreClasses() {
		App::uses('Controller', 'Controler');
		App::uses('Model', 'Model');
		App::uses('View', 'View');
		App::uses('Helper', 'View/Helper');
	}
/**
 * gets the currently defined functions and classes
 * so comparisons to new files can be made
 *
 * @return void
 **/
	protected function _getDefinedObjects() {
		$this->_definedClasses = array_merge(get_declared_classes(), get_declared_interfaces());
		$funcs = get_defined_functions();
		$this->_definedFunctions = $funcs['user'];
	}
/**
 * Fetches the class names and functions contained in the target file.
 * If first pass misses, a forceParse pass will be run.
 *
 * @param string $filePath Absolute file path to file you want to read.
 * @param boolean $forceParse Force the manual read of a file.
 * @return array
 **/
	public function findObjectsInFile($filePath) {
		$new = $tmp = array();
		$tmp['class'] = $this->_parseClassNamesInFile($filePath);
		$tmp['function'] = $this->_parseFunctionNamesInFile($filePath);

		$include = false;
		foreach ($tmp['class'] as $classInFile) {
			$include = false;
			if (!class_exists($classInFile, false) && !interface_exists($classInFile, false)) {
				$include = true;
			}
		}
		foreach ($tmp['function'] as $funcInFile) {
			if (!function_exists($funcInFile)) {
				$include = true;
			}
		}

		if (!$include) {
			$new = $tmp;
		} else {
			ob_start();
			include_once $filePath;
			ob_clean();
			$existingClasses = array_merge(get_declared_classes(), get_declared_interfaces());
			$new['class'] = array_diff($existingClasses, $this->_definedClasses);
			$funcs = get_defined_functions();
			$new['function'] = array_diff($funcs['user'], $this->_definedFunctions);
		}
		return $new;
	}
/**
 * Retrieves the classNames defined in a file.
 * Solves issues of reading docs from files that have already been included.
 *
 * @param string $filePath Absolute file path to file you want to parse.
 * @param boolean $getParents Get the parent classes instead.
 * @return array Array of class names that exist in the file.
 **/
	protected function _parseClassNamesInFile($fileName, $getParents = false) {
		$foundClasses = array();
		$fileContent = file_get_contents($fileName);
		$pattern = '/^\s*(?:abstract\s*)?(?:class|interface)\s+([^\s\{\:]+)\s*[^\{]*\{/mi';
		if ($getParents) {
			$pattern = '/^\s*(?:abstract\s*)?(?:class|interface)\s+[^\s]*\s*(?:extends\s+([^\s\{\:]*))?(?:\s*implements\s*([^\{]*))?/mi';
		}
		preg_match_all($pattern, $fileContent, $matches, PREG_SET_ORDER);

		foreach ($matches as $className) {
			if (!empty($className[1])) {
				$foundClasses[] = $className[1];
			}
			if (isset($className[2])) {
				$foundClasses = array_merge($foundClasses, explode(', ', trim($className[2])));
			}
		}
		foreach ($foundClasses as $i => $class) {
			if (strpos($fileContent, "App::uses('$class'") !== false) {
				unset($foundClasses[$i]);
			}
		}
		return $foundClasses;
	}
/**
 * Retrieves global function names defined in a file.
 * Unlike the class parser which can cheat with regex.
 * Functions are a bit trickier.
 *
 * @return array
 **/
	protected function _parseFunctionNamesInFile($fileName) {
		$foundFuncs = array();
		$fileContent = file_get_contents($fileName);
		$funcNames = implode('|', $this->_definedFunctions);
		preg_match_all('/^\t*function\s*(' . $funcNames . ')[\s|\(]+/mi', $fileContent, $matches, PREG_SET_ORDER);
		foreach ($matches as $function) {
			$foundFuncs[] = $function[1];
		}
		return $foundFuncs;
	}
/**
 * Parses the file for any parent classes required by the file being loaded.
 * Attempts to load those files.
 *
 * @param string $filePath absolute filepath to look in
 * @param array $options Options to use.
 * @return void
 **/
	protected function _resolveDependancies($filePath, $options = array()) {
		$defaults = array('useIndex' => false);
		$options = array_merge($defaults, $options);

		$parentClasses = $this->_parseClassNamesInFile($filePath, true);
		$classNamesInFile = $this->_parseClassNamesInFile($filePath);
		$solved = false;
		$loadClasses = array();
		while ($solved === false && !empty($parentClasses)) {
			$neededParent = array_pop($parentClasses);

			$exists = (
				class_exists($neededParent) ||
				interface_exists($neededParent) ||
				in_array($neededParent, $classNamesInFile)
			);
			if (!$exists && $options['useIndex']) {
				$ApiClass = ClassRegistry::init('ApiGenerator.ApiClass');
				$result = $ApiClass->findByName($neededParent);
				if (!empty($result['ApiClass']['file_name'])) {
					$this->classMap[$neededParent] = $result['ApiClass']['file_name'];
				}
			}

			if (!$exists && isset($this->classMap[$neededParent])) {
				array_unshift($loadClasses, $neededParent);
				$newNeeds = $this->_parseClassNamesInFile($this->classMap[$neededParent], true);
				$parentClasses = array_unique(array_merge($parentClasses, $newNeeds));
			} elseif (!$exists) {
				throw new MissingClassException($neededParent . ' could not be found using mappings, please add it to the mappings.');
			}
			if (empty($parentClasses)) {
				$solved = true;
			}
		}
		foreach ($loadClasses as $className) {
			require_once $this->classMap[$className];
		}
	}
/**
 * Attempts to solve class dependancies by importing base CakePHP classes
 *
 * @return void
 **/
	protected function _importCakeBaseClasses($filePath) {
		$baseClass = array();
		if (strpos($filePath, 'controllers') !== false) {
			$baseClass['Controller'] = 'AppController';
		}
		if (strpos($filePath, 'models') !== false) {
			$baseClass['Model'] = 'AppModel';
		}
		if (strpos($filePath, 'helpers') !== false) {
			$baseClass['View/Helper'] = 'AppHelper';
		}
		if (strpos($filePath, 'view') !== false) {
			$baseClass['View'] = 'View';
		}
		if (strpos($filePath, 'shell') !== false) {
			$baseClass['Command'] = 'Shell';
		}
		if (strpos($filePath, 'socket') !== false) {
			$baseClass['Network'] = 'Socket';
		}
		if (strpos($filePath, 'schema') !== false) {
			$baseClass['Model'] = 'CakeSchema';
		}
		foreach ($baseClass as $type => $class) {
			App::uses($class, $type);
		}
	}
/**
 * Get the Exclusions lists.
 *
 * @return array of stuff not allowed in views.
 **/
	public function getExclusions() {
		$return = array();
		$excludeProps = array('excludeMethods', 'excludeProperties');
		foreach ($excludeProps as $var) {
			$return[$var] = $this->{$var};
		}
		return $return;
	}
/**
 * Checks if a file/path has been excluded by any of the exclusions.
 *
 * @param string $filePath file to check
 * @return boolean True if the file has been excluded
 */
	public function isAllowed($filePath) {
		foreach ($this->excludeDirectories as $exclude) {
			if (strpos($filePath, $exclude . DS) !== false) {
				return false;
			}
		}
		$basename = basename($filePath);
		if (in_array($basename, $this->excludeFiles)) {
			return false;
		}
		list ($file, $ext) = explode('.', $basename);
		if (!in_array($ext, $this->allowedExtensions)) {
			return false;
		}
		return true;
	}
/**
 * Initialize the configuration for ApiFile.
 *
 * @return void
 **/
	protected function _initConfig() {
		$config = $this->ApiConfig->read();
		if (isset($config['exclude']) && is_array($config['exclude'])) {
			foreach ($config['exclude'] as $type => $exclusion) {
				$var = 'exclude' . Inflector::camelize($type);
				$this->{$var} = array_map('trim', explode(',', $exclusion));
			}
		}
		if (isset($config['file']['extensions'])) {
			$this->allowedExtensions = array_map('trim', explode(',', $config['file']['extensions']));
		}
		if (isset($config['file']['regex'])) {
			$this->fileRegExp = $config['file']['regex'];
		}
		$varMap = array('dependencies' => 'dependencyMap', 'mappings' => 'classMap');
		foreach ($varMap as $key => $var) {
			if (isset($config[$key]) && is_array($config[$key])) {
				foreach ($config[$key] as $name => $value) {
					if ($var == 'classMap') {
						$this->{$var}[$name] = $value;
					} else {
						$this->{$var}[$name] = array_map('trim', explode(',', $value));
					}
				}
			}
		}
	}
}

class MissingClassException extends Exception { }
