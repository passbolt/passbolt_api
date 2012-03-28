<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * This is a placeholder class.
 * Create the same file in app/app_controller.php
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.controller
 * @link http://book.cakephp.org/view/957/The-App-Controller
 */

App::import('Model', 'Category');
App::import('Model', 'Permission');
App::import('Model', 'User');

define('ACO_CATEGORY', 1);
define('ACO_PASSWORD', 2);

class AppController extends Controller {
	var $components = array('RequestHandler', 'Auth', 'Session', 'Cookie', 'PermissionManager', 'Watcher', 'Aes', 'Aesctr', 'Image', "Settings");
	var $helpers = array('Form', 'Javascript', 'Session', 'Date', 'Event');
	
	var $user = null;
	var $debug = array();
	
	function beforeFilter(){
		$this->Cookie->name = 'passbolt';
		
		// SECURITY CHECKING
		// 1) Allow only the necessary actions
		$this->Auth->allow('install');
		$this->Auth->allow('installStep1');
		$this->Auth->allow('installStep2');
		
		// Check whether user is not deactivated (if deactivated, we log him out)
		$user = $this->Auth->user();
		if($user != null){
			$userModel = new User();
			$this->user = $userModel->findById($user['User']['id']);
			if($this->user['User']['active'] == 0 || $this->user['User']['active'] == 0){
				if($this->RequestHandler->isAjax()){
					$this->redirect('/users/ajaxDeactivate');
				}
				else{
					$this->redirect('/users/logout');
				}
			}
		}
		// END SECURITY CHECKING
		
		
		// ajax layout in case of ajax request
		if($this->RequestHandler->isAjax()){
	    	Configure::write('debug', 0); // and forget debug messages
	    	$this->disableCache(); // disable cache for ajax views
	    	$this->layout = 'ajax'; 
 		}
 		else{
 			//echo "-----------<br/>pas Ajax<br/>-----------<br/>";
 			// IMPLEMENTS HERE A KIND OF CACHE SYSTEM TO CHECK WHICH DATABASE THE USER CAN ACCESS
 			// THE CACHE IS STORED IN THE SESSION
 			// PROBLEM KNOWN : IN CASE OF A PERMISSION CHANGE IN THE SAME SESSION, THE CACHE IS NOT UPDATED, SO THE USER DOESN'T SEE THE NEW DATABASE HE CAN ACCESS TO
 			if($this->Auth->user() != null){
	 			// initialize the passwords databases
	 			$categoryModel = new Category();
				$databases = $categoryModel->find('all', array('conditions'=>array('level'=>'0', 'shared'=>'1')));
				$user = $this->Auth->user();
				$dbsess_change = false;
				
				if($exists = $this->Session->check('databases.perm')){
					$dbperms = $this->Session->read('databases.perm');
				}
				else{
					$dbperms = array();
				}
				
				//$dbperms = array(); // TODO : remove this line
				
				$perso_database = $categoryModel->findByName('::'.$user['User']['id'].'::');
				$perso_database['Category']['name'] = 'My passwords';
				$databases = array_merge(array($perso_database), $databases);
				$db_final = $databases;
				
				foreach($databases as $key=>$database){
					if(isset($dbperms[$database['Category']['id']])){
						 if($dbperms[$database['Category']['id']] == 0)
							unset($db_final[$key]);
					}
					else{
						$dbsess_change = true;
						if(!$this->PermissionManager->isDatabaseAccessible($database['Category']['id'], $user['User']['id'])){  // checks if the database is accessible
							unset($db_final[$key]);
							$dbperms[$database['Category']['id']] = 0;
						}
						else{
							$dbperms[$database['Category']['id']] = 1;
						}
					}
				}
				if($dbsess_change || !$exists){
					// regenerate the info in the session in case it didn't exist or if it has changed
					$this->Session->write('databases.perm', $dbperms); // write the new data into the session if the information have changed (creation of a new database for instance)
				}

				$databases = array();
				foreach($db_final as $db) $databases[] = $db; 
				
				$this->set('databases', $databases);
				
				$this->debug[] = "session : {$this->Session->read('database_id')}";
				$this->debug[] = "cookie : {$this->Cookie->read("database_id")}";
				
		 		if($this->Session->read('database_id')){
					$database_id = $this->Session->read('database_id');
		 		}
				else{
					$cookiedb = $this->Cookie->read("database_id");
					if(!$cookiedb || !isset($cookiedb[$user['User']['id']])){
						$database_id = $databases[0]['Category']['id'];
						$cookiedb[$user['User']['id']] = $database_id;
						$this->Cookie->write("database_id", $cookiedb, false, 3600 * 24 * 365); // cookie will be persistent for one year
					}
					else{
						$database_id  = $cookiedb[$user['User']['id']];
					}
					$this->Session->write('database_id', $database_id);
				}
 			}
 			/////////////////////////////// END CACHE SYSTEM ///////////////////////
 		}
	}
	
	function beforeRender(){
		if(!isset($this->section))
			$this->section = 'passwords';

		$this->set('section', $this->section);
		$this->set('debug', $this->debug);
		
		// set a few important settings
		// check if the initial setup is properly done
		if($this->user['User']['admin']){
 			$this->set('master_key_set', $this->Settings->getSetting('master_key_set'));
		}
	}
	
	function afterFilter(){
		
	}
}