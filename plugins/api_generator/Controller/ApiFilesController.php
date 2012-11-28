<?php
/**
 * Api Generator Controller
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
 * @subpackage    api_generator.controllers
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class ApiFilesController extends ApiGeneratorAppController {
/**
 * Name property
 *
 * @var string
 */
	public $name = 'ApiFiles';
/**
 * Uses array
 *
 * @var array
 */
	public $uses = array('ApiGenerator.ApiFile', 'ApiGenerator.ApiClass');
/**
 * Components array
 *
 * @var array
 **/
	public $components = array('RequestHandler');
/**
 * Helpers
 *
 * @var array
 **/
	public $helpers = array('ApiGenerator.ApiDoc', 'ApiGenerator.ApiUtils', 'Html', 'Text');
/**
 * Extract all the useful config info out of the ApiConfig.
 *
 * @return void
 **/
	public function beforeRender() {
		$this->set('basePath', $this->path);
		$this->set($this->ApiFile->getExclusions());
	}
/**
 * Browse application files and find things you would like to generate API docs for.
 *
 * @return void
 **/
	public function source() {
		if (count($this->passedArgs) == 1 && $this->passedArgs[0] == 'index') {
			array_shift($this->passedArgs);
		}
		$currentPath = implode('/', $this->passedArgs);
		$previousPath = implode('/', array_slice($this->passedArgs, 0, count($this->passedArgs) -1));
		list($dirs, $files) = $this->ApiFile->read($this->path . $currentPath);
		$this->set(compact('dirs', 'files', 'currentPath', 'previousPath'));
	}
/**
 * all_files
 *
 * Gets a recursive list of all files that match documentor criteria.
 *
 * @access public
 * @return void
 */
	public function files() {
		$files = $this->ApiFile->fileList($this->path);
		$this->set('files', $files);
	}
/**
 * View the API docs for all interesting parts in a file.
 *
 * @return void
 **/
	public function view_file() {
		$currentPath = implode('/', $this->passedArgs);
		$fullPath = $this->path . $currentPath;
		$previousPath = implode('/', array_slice($this->passedArgs, 0, count($this->passedArgs) -1));
		$upOneFolder = implode('/', array_slice($this->passedArgs, 0, count($this->passedArgs) -2));
		if (!file_exists($fullPath) || empty($currentPath) || $currentPath == '/') {
			$this->_notFound(__d('api_generator', 'No file exists with that name'));
		}
		try {
			$docs = $this->ApiFile->loadFile($fullPath, array('useIndex' => true));
		} catch(Exception $e) {
			$this->_notFound($e->getMessage());
		}
		if (!empty($docs)) {
			$classIndex = $this->ApiClass->getClassIndex(true);
			list($dirs, $files) = $this->ApiFile->read($this->path . $previousPath);

			$this->set('showSidebar', true);
			$this->set('sidebarElement', 'sidebar/file_sidebar');
			$this->set(compact('currentPath', 'previousPath', 'upOneFolder', 'docs', 'dirs', 'files', 'classIndex'));
		} else {
			$this->set('previousPath', $previousPath);
			$this->render('no_class');
		}
	}
}
