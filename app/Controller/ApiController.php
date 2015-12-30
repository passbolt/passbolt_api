<?php
/**
 * Api Controller
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.ResourcesController
 * @since         version 2.15.11
 */

use Swagger\Annotations as SWG;
use Swagger\Swagger;

class ApiController extends AppController
{

	/**
	 * Generates Swagger-PHP JSON documentation
	 * Uses Configuration from "swagger.paths"
	 * - Produces a specific resource if $resource is specified.
	 * - Produces a ResourceList if no $resource is specified
	 *
	 * @param    string      Resource label
	 * @return  string
	 */
	function doc($resource = null)
	{
		$this->viewPath = 'Json';
		$this->view = 'default';
		$this->layout = 'empty';

		// Build file list from paths
		$pathList = Configure::read('swagger.paths');
		$swagger = \Swagger\scan($pathList);
		echo $swagger;
		die();
	}
}