<?php
/**
 * Api Generator Plugin App Controller
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
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Folder', 'Utility');

class ApiGeneratorAppController extends AppController {

/**
 * Auto-Render
 *
 * @var boolean
 */
	public $autoRender = true;

/**
 * Helpers
 *
 * @var array
 */
	public $helpers = array('Html', 'Form', 'Session');

/**
 * beforeFilter callback
 *
 * @return void
 */
	public function beforeFilter() {
		parent::beforeFilter();
		$this->ApiConfig = ClassRegistry::init('ApiGenerator.ApiConfig');
		$this->ApiConfig->read();
		$path = $this->ApiConfig->getPath();
		if (empty($path)) {
			$path = APP;
			$this->ApiConfig->data['paths'][$path] = true;
		}
		$this->path = Folder::slashTerm(realpath($path));
	}

/**
 * Login failure
 *
 * @return void
 */
	public function _loginFail() {
		$this->response->send();
	}

/**
 * Error Generating Page.
 * Needs to be public for Security Blackhole.
 *
 * @return void
 **/
	public function _notFound($name = null, $message = null) {
		$name = ($name) ? $name : 'Page Not Found';
		$message = ($message) ? $message : $this->request->here;
		throw new NotFoundException($name);
	}
}

