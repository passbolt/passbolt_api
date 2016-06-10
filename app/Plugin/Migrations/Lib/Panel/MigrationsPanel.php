<?php
/**
 * Copyright 2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

//App::import('File', 'Migration', true, array(APP . 'plugins' . DS . 'migrations' . DS . 'libs' . DS ), 'migration.php');
App::import('Lib', 'Migrations.MigrationVersion');

/**
 * Migrations Panel for DebugKit
 *
 * To include this in your DebugKit panel list, add it to the options for DebugKit:
 *
 * @@@
 * public $components = array('DebugKit.Toolbar' => array(
 *    'panels' => array('Migrations.migrations')
 * ));
 * @@@
 */
class MigrationsPanel extends DebugPanel {

/**
 * Title
 *
 * @var string
 */
	public $title = 'Migrations';

/**
 * Element name
 *
 * @var string
 */
	public $elementName = 'migrations_panel';

/**
 * Plugin name
 *
 * @var string
 */
	public $plugin = 'migrations';

/**
 * Output buffer
 *
 * @var string
 */
	public $output = '';

/**
 * BeforeRender Callback
 *
 * @param Controller $controller Current controller
 * @return array
 */
	public function beforeRender(Controller $controller) {
		$v = new MigrationVersion();
		$map = $migrations = array();

		$migrations = Hash::merge(array('app'), CakePlugin::loaded());
		foreach ($migrations as $plugin) {
			try {
				$map[$plugin] = array(
					'map' => $v->getMapping($plugin),
					'current' => $v->getVersion($plugin)
				);
			} catch (MigrationVersionException $e) {
				// Skip if we get an error.
			}
		}
		return $map;
	}

}
