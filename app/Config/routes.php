<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright		Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link			http://cakephp.org CakePHP(tm) Project
 * @package			app.Config
 * @since			CakePHP(tm) v 0.2.9
 * @license			MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Will automatically switch view classes when a request is done with the .json extension,
 * or the Accept header is application/json.
 * @see http://book.cakephp.org/2.0/en/views/json-and-xml-views.html
 * @see http://book.cakephp.org/2.0/en/development/rest.html
 */
 	Router::parseExtensions('json');
	Router::mapResources('dictionaries');
	Router::mapResources('users');
	Router::mapResources('categories');
	Router::mapResources('resources');
	Router::mapResources('secrets');
	Router::mapResources('permissions');
	Router::mapResources('comments');

	// God knows why.. the edit mapping is not working for resource. So we redeclare it manually
	Router::connect(
		"/resources/*",
		array("controller" => 'resources', "action" => "edit", "[method]" => "PUT")
	);
	Router::connect(
		"/resources/*",
		array("controller" => 'resources', "action" => "delete", "[method]" => "DELETE")
	);
	// The line below doesn't seem to work
	// Router::mapResources('categoriesResources');
	// So we declare the routes mapping for this function manually
	Router::connect(
		"/categoriesResources",
		array("controller" => 'categories_resources', "action" => "view", "[method]" => "GET")
	);
	Router::connect(
		"/categoriesResources/*",
		array("controller" => 'categories_resources', "action" => "view", "[method]" => "GET")
	);
	Router::connect(
		"/categoriesResources/*",
		array("controller" => 'categories_resources', "action" => "edit", "[method]" => "PUT")
	);
	Router::connect(
		"/categoriesResources/*",
		array("controller" => 'categories_resources', "action" => "add", "[method]" => "POST")
	);
	Router::connect(
		"/categoriesResources/*",
		array("controller" => 'categories_resources', "action" => "delete", "[method]" => "DELETE")
	);

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));

/**
 * ...and connect the rest of 'Pages' controller's urls.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Custom route for dictionary controller
 */
	Router::connect('/dictionary/*', array('controller' => 'dictionaries', 'action' => 'view'));
	Router::connect('/dictionaries/*', array('controller' => 'dictionaries', 'action' => 'view'));

/**
 * Custom route for categories controller
 */
	Router::connect('/categories/index', array('controller' => 'categories', 'action' => 'index'));
	Router::connect('/categories/index/*', array('controller' => 'categories', 'action' => 'index'));
	Router::connect('/categories/type/*', array('controller' => 'categories', 'action' => 'type'));
	Router::connect('/categories/move/*', array('controller' => 'categories', 'action' => 'move'));
	Router::connect('/categories/rename/*', array('controller' => 'categories', 'action' => 'rename'));
	Router::connect('/categories/children/*', array('controller' => 'categories', 'action' => 'children'));
	Router::connect('/categories/*', array('controller' => 'categories', 'action' => 'view'));

/**
 * Custom route for permissions controller
 */
	// Router::connect(
		// '/permissions/:comment/:id',
		// array('controller' => 'permissions', 'action' => 'addAcoPermissions', "[method]" => "POST"), 
		// array('pass' => array('model', 'id')));
	// Router::connect(
		// '/permissions/:comment/:id', 
		// array('controller' => 'permissions', 'action' => 'viewAcoPermissions', "[method]" => "GET"), 
		// array('pass' => array('model', 'id')));
	Router::connect(
		'/permissions/resource/:id',
		array('controller' => 'permissions', 'action' => 'addAcoPermissions', 'model'=>'Resource', "[method]" => "POST"), 
		array('pass' => array('model', 'id')));
	Router::connect(
		'/permissions/resource/:id', 
		array('controller' => 'permissions', 'action' => 'viewAcoPermissions', 'model'=>'Resource', "[method]" => "GET"), 
		array('pass' => array('model', 'id')));
	Router::connect(
		'/permissions/category/:id', 
		array('controller' => 'permissions', 'action' => 'addAcoPermissions', 'model'=>'Category', "[method]" => "POST"), 
		array('pass' => array('model', 'id')));
	Router::connect(
		'/permissions/category/:id', 
		array('controller' => 'permissions', 'action' => 'viewAcoPermissions', 'model'=>'Category', "[method]" => "GET"), 
		array('pass' => array('model', 'id')));
		
/**
 * Custom route for comments controller
 */
	Router::connect(
		'/comments/:model/:id',
		array('controller' => 'comments', 'action' => 'viewForeignComments', "[method]" => "GET"),
		array('pass' => array('model', 'id')));
 	Router::connect(
		'/comments/:model/:id',
		array('controller' => 'comments', 'action' => 'addForeignComment', "[method]" => "POST"), 
		array('pass' => array('model', 'id')));
 	Router::connect('/comments/*', array('controller' => 'comments', 'action' => 'edit', "[method]" => "PUSH"));
 	Router::connect('/comments/*', array('controller' => 'comments', 'action' => 'delete', "[method]" => "DELETE"));

/**
 * Load all plugin routes.	See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
