<?php
/**
 * Routes configuration
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
/**
 * Will automatically switch view classes when a request is done with the .json extension,
 * or the Accept header is application/json.
 * @see http://book.cakephp.org/2.0/en/views/json-and-xml-views.html
 * @see http://book.cakephp.org/2.0/en/development/rest.html
 */
	Router::parseExtensions('json');

/**
 * Map default routes
 */
	Router::mapResources('dictionaries');
	Router::mapResources('users');
	Router::mapResources('categories');
	Router::mapResources('favorites');
	Router::mapResources('resources');
	Router::mapResources('secrets');
	Router::mapResources('permissions');
	Router::mapResources('comments');
//	Router::mapResources('groups');
//	Router::mapResources('groupsUsers');

/**
 * Redirections and aliases (aka persistant redirections)
 */
	Router::redirect('/login', array('controller' => 'auth', 'action' => 'login'));
	Router::redirect('/logout', array('controller' => 'auth', 'action' => 'logout'));
	Router::redirect('/users/login', array('controller' => 'auth', 'action' => 'login'));
	Router::redirect('/users/logout', array('controller' => 'auth', 'action' => 'logout'));
	Router::redirect('/auth/register', array('controller' => 'users', 'action' => 'register'), array('persist' => true));
	Router::redirect('/auth/step1', array('controller' => 'users', 'action' => 'login'), array('persist' => true));

/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'pages', 'action' => 'display', 'home'));

/**
 * Debug pages
 */
	Router::connect('/debug', array('controller' => 'pages', 'action' => 'debug', 'config'));
	Router::connect('/debug/config', array('controller' => 'pages', 'action' => 'debug', 'config'));
	Router::connect('/pages/debug', array('controller' => 'pages', 'action' => 'debug', 'config'));
	Router::connect('/pages/debug/*', array('controller' => 'pages', 'action' => 'debug', 'config'));

/**
 * ...and connect the rest of 'Pages' controller's URLs.
 */
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * Custom route for dictionary controller
 */
	Router::connect('/dictionary/*', array('controller' => 'dictionaries', 'action' => 'view'));
	Router::connect('/dictionaries/*', array('controller' => 'dictionaries', 'action' => 'view'));

/**
 * Custom route for validation rules controller
 */
	Router::connect('/validation/:model', array('controller' => 'ValidationRules', 'action' => 'view'), array('pass' => array('model')));
	Router::connect('/validation/:model/:case', array('controller' => 'ValidationRules', 'action' => 'view'), array('pass' => array('model', 'case')));

/**
 * Custom route for categories controller
 */
//	Router::connect('/categories/index', array('controller' => 'categories', 'action' => 'index'));
//	Router::connect('/categories/index/*', array('controller' => 'categories', 'action' => 'index'));
//	Router::connect('/categories/type/*', array('controller' => 'categories', 'action' => 'type'));
//	Router::connect('/categories/move/*', array('controller' => 'categories', 'action' => 'move'));
//	Router::connect('/categories/rename/*', array('controller' => 'categories', 'action' => 'rename'));
//	Router::connect('/categories/children/*', array('controller' => 'categories', 'action' => 'children'));
//	Router::connect('/categories/*', array('controller' => 'categories', 'action' => 'delete', '[method]' => 'DELETE'));
//	Router::connect('/categories/*', array('controller' => 'categories', 'action' => 'edit', '[method]' => 'PUT'));
//	Router::connect('/categories/*', array('controller' => 'categories', 'action' => 'view', '[method]' => 'GET'));

/**
 * Custom route for resources controller
 */
	Router::connect('/resources/index', array('controller' => 'resources', 'action' => 'index'));
	Router::connect('/resources/index/*', array('controller' => 'resources', 'action' => 'index'));
	Router::connect('/resources/*', array('controller' => 'resources', 'action' => 'delete', '[method]' => 'DELETE'));
	Router::connect('/resources/*', array('controller' => 'resources', 'action' => 'edit', '[method]' => 'PUT'));
	Router::connect('/resources/*', array('controller' => 'resources', 'action' => 'view', '[method]' => 'GET'));

/**
 * Custom route for groups controller
 */
//	Router::connect('/groups/index', array('controller' => 'groups', 'action' => 'index'));
//	Router::connect('/groups/index/*', array('controller' => 'groups', 'action' => 'index'));
//	Router::connect('/groups/*', array('controller' => 'groups', 'action' => 'delete', '[method]' => 'DELETE'));
//	Router::connect('/groups/*', array('controller' => 'groups', 'action' => 'edit', '[method]' => 'PUT'));
//	Router::connect('/groups/*', array('controller' => 'groups', 'action' => 'view', '[method]' => 'GET'));

/**
 * Category Resources
 */
//	Router::connect('/categoriesResources', array('controller' => 'categories_resources', 'action' => 'view', '[method]' => 'GET'));
//	Router::connect('/categoriesResources/*', array('controller' => 'categories_resources', 'action' => 'view', '[method]' => 'GET'));
//	Router::connect('/categoriesResources/*', array('controller' => 'categories_resources', 'action' => 'edit', '[method]' => 'PUT'));
//	Router::connect('/categoriesResources/*', array('controller' => 'categories_resources', 'action' => 'add', '[method]' => 'POST'));
//	Router::connect('/categoriesResources/*', array('controller' => 'categories_resources', 'action' => 'delete', '[method]' => 'DELETE'));

/**
 * Group User
 */
//	Router::connect('/groupsUsers', array('controller' => 'groups_users', 'action' => 'view', '[method]' => 'GET'));
//	Router::connect('/groupsUsers/*', array('controller' => 'groups_users', 'action' => 'view', '[method]' => 'GET'));
//	Router::connect('/groupsUsers/*', array('controller' => 'groups_users', 'action' => 'edit', '[method]' => 'PUT'));
//	Router::connect('/groupsUsers/*', array('controller' => 'groups_users', 'action' => 'add', '[method]' => 'POST'));
//	Router::connect('/groupsUsers/*', array('controller' => 'groups_users', 'action' => 'delete', '[method]' => 'DELETE'));

/**
 * Custom route for gpgkeys controller
 */
	Router::connect('/gpgkeys', array('controller' => 'gpgkeys', 'action' => 'index', '[method]' => 'GET'));
	Router::connect('/gpgkeys', array('controller' => 'gpgkeys', 'action' => 'add', '[method]' => 'POST'));
	Router::connect('/gpgkeys/index', array('controller' => 'gpgkeys', 'action' => 'index'));
	Router::connect('/gpgkeys/index/*', array('controller' => 'gpgkeys', 'action' => 'index'));
	Router::connect('/gpgkeys/*', array('controller' => 'gpgkeys', 'action' => 'delete', '[method]' => 'DELETE'));
	Router::connect('/gpgkeys/*', array('controller' => 'gpgkeys', 'action' => 'view', '[method]' => 'GET'));

/**
 * Custom route for users controller
 */
	Router::connect('/users/index', array('controller' => 'users', 'action' => 'index'));
	Router::connect('/users/index/*', array('controller' => 'users', 'action' => 'index'));
	Router::connect('/register', array('controller' => 'users', 'action' => 'register'));
	Router::connect('/register/thankyou', array('controller' => 'users', 'action' => 'register_thankyou'));
	Router::connect('/recover', array('controller' => 'users', 'action' => 'recover'));
	Router::connect('/recover/thankyou', array('controller' => 'users', 'action' => 'recover_thankyou'));
	Router::connect('/users/*', array('controller' => 'users', 'action' => 'delete', '[method]' => 'DELETE'));
	Router::connect('/users/password/*', array('controller' => 'users', 'action' => 'editPassword', '[method]' => 'PUT'));
	Router::connect('/users/avatar/*', array('controller' => 'users', 'action' => 'editAvatar', '[method]' => 'POST'));
	Router::connect('/users/validateAccount/*', array('controller' => 'users', 'action' => 'validateAccount'));
	Router::connect('/users/*', array('controller' => 'users', 'action' => 'edit', '[method]' => 'PUT'));
	Router::connect('/users/*', array('controller' => 'users', 'action' => 'view', '[method]' => 'GET'));

/**
 * Custom route for setup
 */
	Router::connect('/setup/recover', array('controller' => 'setup', 'action' => 'recover', '[method]' => 'GET'));
	Router::connect('/setup/completeRecovery', array('controller' => 'setup', 'action' => 'completeRecovery', '[method]' => 'PUT'));

/**
 * Custom route for permissions controller
 */
	Router::connect('/permissions/resource/:id', array('controller' => 'permissions', 'action' => 'addAcoPermissions', 'model' => 'Resource', '[method]' => 'POST'), array('pass' => array('model', 'id')));
	Router::connect('/permissions/simulate/:resource/:id', array('controller' => 'permissions', 'action' => 'simulateAcoPermissionsAfterChange', 'model' => 'Resource', '[method]' => 'POST'), array('pass' => array('resource', 'id')));
	Router::connect('/permissions/resource/:id', array('controller' => 'permissions', 'action' => 'viewAcoPermissions', 'model' => 'Resource', '[method]' => 'GET'), array('pass' => array('model', 'id')));
//	Router::connect('/permissions/category/:id', array('controller' => 'permissions', 'action' => 'addAcoPermissions', 'model' => 'Category', '[method]' => 'POST'), array('pass' => array('model', 'id')));
//	Router::connect('/permissions/category/:id', array('controller' => 'permissions', 'action' => 'viewAcoPermissions', 'model' => 'Category', '[method]' => 'GET'), array('pass' => array('model', 'id')));
	Router::connect('/permissions/*', array('controller' => 'permissions', 'action' => 'edit', '[method]' => 'PUT'));
	Router::connect('/permissions/*', array('controller' => 'permissions', 'action' => 'delete', '[method]' => 'DELETE'));

/**
 * Custom route for share
 */
	Router::connect('/share/:resource/:id', array('controller' => 'share', 'action' => 'update', '[method]' => 'PUT'), array('pass' => array('resource', 'id')));
	Router::connect('/share/search-users/:model/:id', array('controller' => 'share', 'action' => 'searchUsers', '[method]' => 'GET'), array('pass' => array('model', 'id')));

/**
 * Custom route for comments controller
 */
	Router::connect('/comments/:model/:id', array('controller' => 'comments', 'action' => 'viewForeignComments', '[method]' => 'GET'), array('pass' => array('model', 'id')));
	Router::connect('/comments/:model/:id', array('controller' => 'comments', 'action' => 'addForeignComment', '[method]' => 'POST'), array('pass' => array('model', 'id')));
	Router::connect('/comments/*', array('controller' => 'comments', 'action' => 'edit', '[method]' => 'PUT'));
	Router::connect('/comments/*', array('controller' => 'comments', 'action' => 'delete', '[method]' => 'DELETE'));

/**
 * Custom route for tags controller
 */
	Router::connect('/itemTags/:model/:id', array('controller' => 'itemTags', 'action' => 'viewForeignItemTags', '[method]' => 'GET'), array('pass' => array('model', 'id')));
	Router::connect('/itemTags/:model/:id', array('controller' => 'itemTags', 'action' => 'addForeignItemTag', '[method]' => 'POST'), array('pass' => array('model', 'id')));
	Router::connect('/itemTags/*', array('controller' => 'itemTags', 'action' => 'delete', '[method]' => 'DELETE'));

/**
 * Custom route for favorites controller
 */
	Router::connect('/favorites/:model/:id', array('controller' => 'favorites', 'action' => 'add', '[method]' => 'POST'), array('pass' => array('model', 'id')));
	Router::connect('/favorites/*', array('controller' => 'favorites', 'action' => 'delete', '[method]' => 'DELETE'));

/**
 * Custom route for setup health check page
 */
	Router::connect('/healthcheck', array('controller' => 'HealthCheck', 'action' => 'index'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
