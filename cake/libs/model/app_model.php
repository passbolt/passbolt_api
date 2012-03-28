<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @subpackage    cake.cake.libs.model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Application model for Cake.
 *
 * This is a placeholder class.
 * Create the same file in app/app_model.php
 * Add your application-wide methods to the class, your models will inherit them.
 *
 * @package       cake
 * @subpackage    cake.cake.libs.model
 */
class AppModel extends Model {
	/**
	 * Attempts to set a new connection for the model.
	 * Doesn't seem to work so far...
	 * @param string $name
	 * @param array $config
	 */
	function setDbConnection($name, $config){
		// Point model to new config
		//ConnectionManager::create($name, $config);
		$this->useDbConfig = $name;
		parent::__construct(false, null, null);
	}
	
	/**
	 * Return a $db object for the connection
	 * @param string $name, the name of the connection
	 * @param array $config, the configuration details
	 */
	function getDbConnection($name, $config){
		ConnectionManager::create($name, $config);
		$db = &ConnectionManager::getDataSource($name);
		$db->setConfig(array('name' => $name, 'database' => $config['database'], 'persistent' => false));
		
		return $db;
	}
	
	/**
	 * Constructs the model
	 * If the app is set in SAAS mode, with an application id set, the model will create a new db connection on the fly
	 * @param unknown_type $id
	 * @param unknown_type $table
	 * @param unknown_type $ds
	 */
	function __construct($id = false, $table = null, $ds = null) {
		// If mode is SAAS, we connect to the database to get the username and password of the client database
		// we connect to it automatically
		if(Configure::read('install_mode') == 'saas' && Configure::read('appid') != 'app'){
			$client_id = Configure::read('appid');

			$db_config = ConnectionManager::getDataSource('default')->config;

			$link = mysql_connect($db_config['host'], $db_config['login'], $db_config['password']);
			mysql_select_db($db_config['database'], $link);
			$res = mysql_query('SELECT * FROM installations WHERE appid = \''.$client_id.'\'');
			$row = mysql_fetch_assoc($res);
			
			if(!empty($row) && isset($row['id'])){
				$config = array();
				$dbName = $row['dbname'];
				// Set correct database name
				$config['driver'] = 'mysql';
				$config['persistent'] = false;
				$config['host'] = $row['dbhost'];
				$config['login'] = $row['dbusername'];
				$config['password'] = $row['dbpassword'];
				$config['database'] = $dbName;
				$config['prefix'] = '';

				// Add new config to registry
				ConnectionManager::create($dbName, $config);
				// Point model to new config
				$this->useDbConfig = $dbName;
			}else{
				echo "Wrong application id";
				// TODO : Redirect to a page on Passbolt
				exit(0);
			}
		}
		parent::__construct($id, $table, $ds);
	}
}
