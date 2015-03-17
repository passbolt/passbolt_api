<?php
/**
 * ApiClass Fixture
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
 * @subpackage    api_generator.tests.fixtures
 * @since         ApiGenerator 0.1
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 **/
class ApiClassFixture extends CakeTestFixture {
	var $name = 'ApiClass';
	var $fields = array(
		'id' => array('type' => 'string', 'default' => NULL, 'length' => 36, 'null' => false, 'key' => 'primary'),
		'api_package_id' => array('type' => 'string', 'default' => NULL, 'length' => 36, 'null' => true),
		'name' => array('type' => 'string', 'length' => 200, 'null' => false),
		'slug' => array('type' => 'string', 'length' => 200, 'null' => false),
		'file_name' => array('type' => 'text'),
		'method_index' => array('type' => 'text'),
		'property_index' => array('type' => 'text'),
		'flags' => array('type' => 'integer', 'default' => 0, 'length' => 5),
		'coverage_cache' => array('type' => 'float', 'length' => '4,4'),
		'created' => array('type' => 'datetime'),
		'modified' => array('type' => 'datetime'),
	);

	function __construct() {
		foreach ($this->records as &$record) {
			$record['file_name'] = CAKE . $record['file_name'];
		}
		parent::__construct();
	}

	var $records = array(
	array(
		'id' => '498cee77-97c8-441a-99c3-80ed87460ad7', 
		'api_package_id' => null,
		'name' => 'basics.php', 
		'slug' => 'basics.php',
		'file_name' => 'basics.php', 
		'method_index' => 'config uses debug getMicrotime sortByKey h a aa e low up r pr params am env cache clearCache stripslashes_deep __ __n __d __dn __dc __dcn __c LogError fileExistsInPath convertSlash ife', 
		'property_index' => NULL,
		'flags' => '1',
		'coverage_cache' => null,
		'created' => '2009-02-06 21:14:15',
		'modified' => '2009-02-06 21:14:15'
	),
	array(
		'id' => '498cee77-ddbc-4f12-b457-80ed87460ad7',
		'api_package_id' => null,
		'name' => 'Dispatcher', 
		'slug' => 'dispatcher', 
		'file_name' => 'Routing/Dispatcher.php', 
		'method_index' => '__construct dispatch _invoke __extractparams parseparams baseurl _restructureparams __getcontroller __loadcontroller uri geturl cached __construct dispatch _invoke __extractparams parseparams baseurl _restructureparams __getcontroller __loadcontroller uri geturl cached', 
		'property_index' => 'base webroot here admin plugin params base webroot here admin plugin params', 
		'flags' => '2',
		'coverage_cache' => null,
		'created' => '2009-02-06 21:14:15', 
		'modified' => '2009-02-06 21:14:15'
	),
	array(
		'id' => '498cee77-68c4-4eb7-ba8b-80ed87460ad7',
		'api_package_id' => null,
		'name' => 'Router', 
		'slug' => 'router', 
		'file_name' => 'Routing/Router.php', 
		'method_index' => 'getinstance getnamedexpressions connect connectnamed mapresources writeroute prefixes parse __matchroute compile __parseextension __connectdefaultroutes setrequestinfo getparams getparam getpaths reload promote url maprouteelements __maproute getnamedelements matchnamed querystring normalize requestroute currentroute stripplugin stripescape parseextensions getargs getinstance getnamedexpressions connect connectnamed mapresources writeroute prefixes parse __matchroute compile __parseextension __connectdefaultroutes setrequestinfo getparams getparam getpaths reload promote url maprouteelements __maproute getnamedelements matchnamed querystring normalize requestroute currentroute stripplugin stripescape parseextensions getargs', 
		'property_index' => 'routes __admin __prefixes __parseextensions __validextensions __named named __currentroute __headermap __resourcemap __resourcemapped __params __paths __defaultsmapped routes __admin __prefixes __parseextensions __validextensions __named named __currentroute __headermap __resourcemap __resourcemapped __params __paths __defaultsmapped', 
		'flags' => '2',
		'coverage_cache' => null,
		'created' => '2009-02-06 21:14:15', 
		'modified' => '2009-02-06 21:14:15'
	),
	array(
		'id' => '498cee77-a45c-4de0-a25c-80ed87460ad7',
		'api_package_id' => null,
		'name' => 'ShellDispatcher', 
		'slug' => 'shell-dispatcher', 
		'file_name' => 'Console/ShellDispatcher.php', 
		'method_index' => 'shelldispatcher __construct __initconstants _initenvironment __buildpaths __bootstrap dispatch getinput stdout stderr parseparams __parseparams shiftargs help _stop shelldispatcher __construct __initconstants _initenvironment __buildpaths __bootstrap dispatch getinput stdout stderr parseparams __parseparams shiftargs help _stop', 
		'property_index' => 'stdin stdout stderr params args shell shellclass shellcommand shellpaths shellpath shellname stdin stdout stderr params args shell shellclass shellcommand shellpaths shellpath shellname', 
		'flags' => '2',
		'coverage_cache' => null,
		'created' => '2009-02-06 21:14:15', 
		'modified' => '2009-02-06 21:14:15'
	),
	array(
		'id' => '498cee77-9da8-48e3-b6ab-80ed87460ad7',
		'api_package_id' => null,
		'name' => 'ConsoleErrorHandler', 
		'slug' => 'console-error-handler', 
		'file_name' => 'Console/ConsoleErrorHandler.php', 
		'method_index' => '__construct error error404 missingcontroller missingaction privateaction missingtable missingdatabase missingview missinglayout missingconnection missinghelperfile missinghelperclass missingcomponentfile missingcomponentclass missingmodel stdout stderr __construct error error404 missingcontroller missingaction privateaction missingtable missingdatabase missingview missinglayout missingconnection missinghelperfile missinghelperclass missingcomponentfile missingcomponentclass missingmodel stdout stderr', 
		'property_index' => 'stdout stderr stdout stderr', 
		'flags' => '2',
		'coverage_cache' => null,
		'created' => '2009-02-06 21:14:15', 
		'modified' => '2009-02-06 21:14:15'
	),
	array(
		'id' => '498cee77-0360-4c44-8dcd-80ed87460ad7',
		'api_package_id' => null,
		'name' => 'AclComponent', 
		'slug' => 'acl-component', 
		'file_name' => 'Controller/Component/AclComponent.php', 
		'method_index' => '__construct startup _initacl check allow deny inherit grant revoke __construct startup _initacl check allow deny inherit grant revoke', 
		'property_index' => '_instance _instance', 
		'flags' => '2',
		'coverage_cache' => null,
		'created' => '2009-02-06 21:14:15', 
		'modified' => '2009-02-06 21:14:15'
	),
	array(
		'id' => '498cee77-e330-422b-bdca-80ed87460ad7',
		'api_package_id' => null,
		'name' => 'IniAcl', 
		'slug' => 'ini-acl', 
		'file_name' => 'Controller/Component/AclComponent.php', 
		'method_index' => '__construct check readconfigfile arraytrim __construct check readconfigfile arraytrim', 
		'property_index' => 'config config', 
		'flags' => '2',
		'coverage_cache' => null,
		'created' => '2009-02-06 21:14:15', 
		'modified' => '2009-02-06 21:14:15'
	)
);
}