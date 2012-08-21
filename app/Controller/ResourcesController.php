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
App::uses('CategoryResource', 'Model');
 
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
		$this->Resource->bindModel(array('hasOne' => array('CategoryResource')));
		$this->Resource->contain(array('CategoryResource'));
		$o = $this->Resource->getFindFields('view');
		$resource = $this->Resource->findById($id, $o['fields']);
		if (!$resource) {
			$this->Message->error(__('The resource does not exist'));
			return;
		}
		$this->set('data', $resource);
		$this->Message->success();
	}
	
/**
 * Get all resources in a category id
 * Renders a json object of the resources
 *
 * @param uuid $category_id the id of the category
 * @param bool recursive, whether we want also the resources of all subcategories
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
		$category = $this->Resource->CategoryResource->Category->findById($category_id);
		if(!$category){
			$this->Message->error(__('The category doesn\t exist'));
			return;
		}
	
		if($recursive == false) {
			$data = array('CategoryResource.category_id' => $category_id);
		}
		else{
			$cats = $this->Resource->CategoryResource->Category->find('all', array('conditions' => array('Category.lft >=' => $category['Category']['lft'], 'Category.rght <=' => $category['Category']['rght'])));
			foreach($cats as $cat){
				$data['CategoryResource.category_id'][] = $cat['Category']['id'];
			}
		}
		$this->Resource->bindModel(array('hasOne' => array('CategoryResource')));
		$options = $this->Resource->getFindOptions('viewByCategory', $data);
		$resources = $this->Resource->find('all', $options);
		//pr($resources); die();
		
		if(!$resources){
			$this->Message->error(__('Something wrong happened'));
			return;
		}

		$this->set('data', $resources);
		$this->Message->success();
	}
		
	public function populate(){
		$this -> layout = 'html5';
		$this->Resource->create();
			$catDrupalId = '4ff6111b-efb8-4a26-aab4-2184cbdd56cb';
		$catAnjunaId = '4ff6111c-8534-4d17-869c-2184cbdd56cb';
		$catHippiesId = '4ff6111d-9e6c-4d71-80ee-2184cbdd56cb';
		$this->Resource->saveAll(
			array(
				0 => array(  
				'Category' => array( 'id' => $catGoaId ),
				'Resource' => array('name' => 'festival du cinema', 'username' => 'festival', 'expiry_date' => null, 'uri' => 'http://www.iffigoa.org/', 'description' => 'description of the Goa Film Festival')
				),
				1 => array(  
				'Category' => array( 'id' => $catGoaId ),
				'Resource' => array('name' => 'Church Square', 'username' => 'priest1', 'expiry_date' => null, 'uri' => '', 'description' => 'this is a description test')
				),
				2 => array(  
				'Category' => array( 'id' => $catAnjunaId ),
				'Resource' => array('name' => 'hill door', 'username' => 'hippie', 'expiry_date' => null, 'uri' => 'http://www.hippiehill.com', 'description' => 'never underestimate the power of Anjuna Hills')
				),
				3 => array(  
				'Category' => array( 'id' => $catHippiesId ),
				'Resource' => array('name' => 'washroom', 'username' => 'sousouchaie', 'expiry_date' => null, 'uri' => '', 'description' => 'How to get inside the washroom at Hippie ?')
				),
				4 => array(  
				'Resource' => array('name' => 'random', 'username' => 'user1', 'expiry_date' => '2014-07-01', 'uri' => 'http://www.enova-tech.net', 'description' => 'sample entry')
				)
			)
		);
	}
	
	/**
	 * Delete a resource
	 * @param uuid id the id of the resource to delete
	 */
	public function delete($id = null){
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
		
		$resource = $this->Resource->findById($id);
		if(!$resource){
			$this->Message->error(__('The resource doesn\'t exist'));
			return;
		}
		
		$resource['Resource']['deleted'] = '1';
		if(!$this->Resource->save($resource)){
			$this->Message->error(__('Error while deleting'));
			return;
		}
		$this->Message->success();
	}
	
	/**
	 * Add a resource
	 */
	public function add(){
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Resource'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}
					

		// set the data for validation and save
		$resourcepost = $this->request->data;
		$this->Resource->set($resourcepost);

		// check if the data is valid
		if (!$this->Resource->validates()) {
			$this->Message->error(__('Could not validate resource data'));
			return;
		}

		$resource = $this->Resource->save($resourcepost);
		if ($resource === false) {
			$this->Message->error(__('The resource could not be saved'));
			return;
		}
		
		// Save the relations
		foreach($resourcepost['Category'] as $cat){
				$crdata = array(
					'CategoryResource' => array(
						'category_id' => $cat['id'],
						'resource_id' => $resource['Resource']['id'],
					)
				);
			// check if the data is valid
			$this->Resource->CategoryResource->set($crdata);
			if (!$this->Resource->CategoryResource->validates()) {
				$this->Message->error(__('Could not validate CategoryResource'));
				return;
			}
			// if validation passes, then save the data
			$res = $this->Resource->CategoryResource->save();
			if(!$res){
				$this->Message->error(__('Could not save the association'));
				return;
			}
		}
		$this->Message->success(__('The resource was sucessfully saved'));
	}

	/**
	 * Update a resource
	 */
	public function update(){
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		// check if data was provided
		if (!isset($this->request->data['Resource'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}

		$resourcepost = $this->request->data;
		// check if the id is valid
		if (!Common::isUuid($resourcepost['Resource']['id'])) {
			$this->Message->error(__('The resource id invalid'));
			return;
		}
		
		// get the resource id
		$resource = $this->Resource->findById($resourcepost['Resource']['id']);
		if(!$resource){
			$this->Message->error(__('The resource doesn\'t exist'));
			return;
		}

		$fields = $this->Resource->getFindFields('view');
		$mask = '/([a-zA-Z]*)\.([a-zA-Z_]*)/i';
		foreach($fields['fields'] as $f){
			preg_match($mask, $f, $matches);
			$model = $matches[1];
			$key = $matches[2];
			$resource[$model][$key] = $resourcepost[$model][$key];
		}
		$save = $this->Resource->save($resource);
		if(!$save){
			$this->Message->error(__('The resource could not be updated'));
			return;
		}
		$this->Message->success(__('The resource was sucessfully updated'));
	}
}

