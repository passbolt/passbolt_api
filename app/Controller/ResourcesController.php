<?php
/**
 * Resources Controller
 *
 * @copyright     Copyright 2012 Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Controller.ResourcesController
 * @since         version 2.12.7
 */

App::uses('Category', 'Model');
 
class ResourcesController extends AppController {
	/**
 * Get a resource
 * Renders a json object of the resource
 *
 * @param uuid $id the id of the resource
 * @return void
 */
	public function view($id=null) {
		// check if the category id is provided
		if (!isset($id)) {
			$this->Message->error(__('The resource id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The resource id invalid'));
			return;
		}
		// check if it exists
		$o = $this->Resource->getFindFields('view');
		$resource = $this->Resource->findById($id, $o['fields']);
		if (!$resource) {
			$this->Message->error(__('The resource does not exist'));
			return;
		}
		$this->Message->success();
	}
	
		/**
 * Get all resources in a category id
 * Renders a json object of the resources
 *
 * @param uuid $category_id the id of the category
 * @return void
 */
		public function viewByCategory($category_id=null, $recursive=false) {
		// check if the category id is provided
		if (!isset($category_id)) {
			$this->Message->error(__('The category id is missing'));
			return;
		}
		// check if the id is valid
		if (!Common::isUuid($category_id)) {
			$this->Message->error(__('The category id invalid'));
			return;
		}
		
		// check if the category exists
		$categoryModel = new Category();
		$category = $categoryModel->findById($category_id);
		if(!$category){
			$this->Message->error(__('The category doesn\t exist'));
			return;
		}
	
		if($recursive == false) {
			$resources = $this->Resource->find('all', array(
				'conditions' => array( 'Category.id' => $category_id)
			));
			pr($resources); die();
		}
		else {
			
		}

		$this->Message->success();
	}
		
	public function populate(){
		$this -> layout = 'html5';
		$this->Resource->create();
		$this->Resource->saveAll(
			array(0 => array(  
			'Category' => array( 'id' => '50170f13-6b88-4e70-bb6f-3658b4e000c3' ),
			'Resource' => array('name' => 'test', 'username' => 'john', 'expiry_date' => null, 'uri' => 'http://www.enova-tech.net', 'description' => 'this is a description test')
			))
		);
	}
}

