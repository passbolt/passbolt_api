<?php
/**
 * Api Packages Controller
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
 * @since         ApiGenerator 0.5
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class ApiPackagesController extends ApiGeneratorAppController {
/**
 * Name property
 *
 * @var string
 */
	public $name = 'ApiPackages';
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
 * Index of Packages + subpackages.
 *
 * @return void
 **/
	public function index() {
		$packageIndex = $this->ApiPackage->getPackageIndex();
		$this->set('packageIndex', $packageIndex);
	}
/**
 * View a package, and all contained classes.
 *
 * @return void
 **/
	public function view() {
		$path = null;
		if (!empty($this->passedArgs)) {
			$path = $this->ApiPackage->makePath($this->passedArgs);
		}
		if (!$path) {
			$this->Session->setFlash(__d('api_generator', 'No package name was given'));
			$this->redirect($this->referer());
		}
		$apiPackage = $this->ApiPackage->findByPath($path);
		if (empty($apiPackage)) {
			$this->_notFound(__d('api_generator', 'No package exists in the index with that name'));
		}
		$classIndex = $this->ApiPackage->ApiClass->getClassIndex();
		$packageIndex = $this->ApiPackage->getPackageIndex();

		$this->set('showSidebar', true);
		$this->set('sidebarElement', 'sidebar/package_sidebar');
		$this->set(compact('apiPackage', 'classIndex', 'packageIndex'));
	}
}