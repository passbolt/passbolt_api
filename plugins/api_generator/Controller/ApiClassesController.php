<?php
/**
 * Api Classes Controller
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
class ApiClassesController extends ApiGeneratorAppController {
/**
 * Name property
 *
 * @var string
 */
	public $name = 'ApiClasses';

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
	public $components = array('Session', 'RequestHandler', 'Security');

/**
 * Helpers
 *
 * @var array
 **/
	public $helpers = array('ApiGenerator.ApiDoc', 'ApiGenerator.ApiUtils', 'Html', 'Text');

/**
 * beforeFilter callback
 *
 * @return void
 **/
	public function beforeFilter() {
		parent::beforeFilter();

		$this->ApiFile->ApiConfig->read();

		/*
		if (isset($this->ApiFile->ApiConfig->data['users'])) {
			$this->Security->loginUsers = $this->ApiFile->ApiConfig->data['users'];
		}
		$this->Security->loginOptions = array('type' => 'basic');
		$this->Security->blackHoleCallback = '_loginFail';
		$this->Security->requireLogin('admin_index', 'admin_docs_coverage', 'admin_calculate_coverage');
		 */
	}
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
	public function index() {
		$this->classes();
		if (!empty($this->viewVars['classIndex'])) {
			$this->render('classes');
			return;
		}
	}
/**
 * Browse the classes in the application / API files.
 *
 * @return void
 **/
	public function classes() {
		$classIndex = $this->ApiClass->getClassIndex();
		$this->set('classIndex', $classIndex);
	}
/**
 * View API docs for a single class used with browse_classes
 *
 * @return void
 **/
	public function view_class($classSlug = null) {
		if (!$classSlug) {
			$this->Session->setFlash(__d('api_generator', 'No class name was given'));
			$this->redirect($this->referer());
		}
		$classInfo = $this->ApiClass->findBySlug($classSlug);
		if (empty($classInfo['ApiClass']['file_name'])) {
			$this->_notFound(__d('api_generator', 'No class exists in the index with that name'));
		}
		try {
			$docs = $this->ApiFile->loadFile($classInfo['ApiClass']['file_name'], array('useIndex' => true));
			$doc = $docs['class'][$classInfo['ApiClass']['name']];
		} catch(Exception $e) {
			$this->_notFound($e->getMessage());
		}

		if (!empty($docs)) {
			$classIndex = $this->ApiClass->getClassIndex();
			$this->set('showSidebar', true);
			$this->set('sidebarElement', 'sidebar/class_sidebar');
			$this->set(compact('doc', 'classIndex'));
		} else {
			$this->_notFound(__d('api_generator', "Oops, seems we couldn't get the documentation for that class."));
		}
	}
/**
 * View the Source for a class identified by its slug.
 *
 * @return void
 **/
	public function view_source($classSlug = null) {
		$classInfo = $this->ApiClass->findBySlug($classSlug);

		if (empty($classInfo['ApiClass']['file_name'])) {
			$this->_notFound(__d('api_generator', 'No class exists in the index with that name'));
		}
		$fileContents = file_get_contents($classInfo['ApiClass']['file_name']);
		$this->set('contents', $fileContents);
		$this->set('filename', $classInfo['ApiClass']['file_name']);
	}
/**
 * Search through the class index.
 *
 * @return void
 **/
	public function search($term = null) {
		$conditions = array();
		if (!empty($this->params['url']['query'])) {
			$term = $this->params['url']['query'];
			return $this->redirect(array($term));
		}
		$term = trim($term);
		$terms = explode(' ', $term);
		foreach ($terms as $i => $j) {
			if (trim($j) === '') {
				unset ($terms[$i]);
			}
		}
		$docs = $this->ApiClass->search($terms);
		$classIndex = $this->ApiClass->getClassIndex();
		$this->set(compact('classIndex', 'terms', 'docs'));
	}
/**
 * Admin Class index. View a list of classes in the index and get admin actions for
 * them.
 *
 * @return void
 **/
	public function admin_index() {
		$this->set('apiClasses', $this->paginate('ApiClass'));
	}
/**
 * Get docs coverage for a class
 *
 * @return void
 **/
	public function admin_docs_coverage($className = null) {
		$apiClass = $this->ApiClass->findBySlug($className);
		if (empty($apiClass)) {
			$this->_notFound(__d('api_generator', 'No class exists with that name'));
		}
		try {
			$analysis = $this->ApiClass->analyzeCoverage($apiClass);
		} catch(Exception $e) {
			$this->_notFound($e->getMessage());
		}
		$backwards = $this->referer();
		$this->helpers[] = 'Number';
		$this->set(compact('apiClass', 'analysis', 'backwards'));
	}
/**
 * Calculates the coverage for a class, Used via XHR to get coverage as user
 * looks at index page.
 *
 * @return void
 **/
	public function admin_calculate_coverage($id = null) {
		if (!$this->RequestHandler->isAjax()) {
			$this->_notFound(__d('api_generator', 'Invalid request.'));
		}
		if (isset($this->Toolbar)) {
			$this->Toolbar->enabled = false;
		}
		$apiClass = $this->ApiClass->findById($id);
		if (empty($apiClass)) {
			$this->_notFound(__d('api_generator', 'No class exists with that name'));
		}
		try {
			$analysis = $this->ApiClass->analyzeCoverage($apiClass);
		} catch(Exception $e) {
			$analysis = $e->getMessage();
		}
		$this->set(compact('analysis', 'id'));
	}
}
