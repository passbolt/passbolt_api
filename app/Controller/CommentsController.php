<?php
/**
 * Comments Controller
 *
 * @copyright		Copyright 2012, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Controller.CommentsController
 * @since			version 2.13.3
 */
class CommentsController extends AppController {

/**
 * View comments of a target commentable model instance
 * @param string foreignModelName The target foreign model
 * @param uuid foreignId The uuid of the target instance to get comments for 
 */
	public function viewForeignComments($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);
		
		// check the HTTP request method
		if (!$this->request->is('get')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}
		
		// check if the target foreign model is commentable
		if(!$this->Comment->isValidForeignModel($foreignModelName)) {
			$this->Message->error(__('The model %s is not commentable', $foreignModelName));
			return;
		}
		
		// no instance id given
		if(is_null($foreignId)) {
			$this->Message->error(__('The id parameter is missing'));
			return;
		}
		
		// the instance id is invalid
		if (!Common::isUuid($foreignId)) {
			$this->Message->error(__('The id %s is invalid', $foreignId));
			return;
		}
		
		// the foreign instance does not exist
		$this->loadModel($foreignModelName);
		if (!$this->$foreignModelName->exists($foreignId)) {
			$this->Message->error(__('The foreign instance %s for the model %s doesn\'t exist or the user is not allowed to access it', $foreignId, $foreignModelName));
			return;
		}
		
		$findData = array(
			'Comment' => array(
				'foreign_id' => $foreignId
			)
		);
		$findOptions = $this->Comment->getFindOptions('viewByForeignModel', User::get('Role.name'), $findData);
		$this->set('data', $this->Comment->find('threaded', $findOptions));
		$this->Message->success();
	}


/**
 * Add a comment to a target commentable model instance
 * @param string foreignModelName The target foreign model
 * @param uuid foreignId The uuid of the target instance to create comments for 
 */
	public function addForeignComment($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);
		$postData = $this->request->data;

		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		
		// check if the target foreign model is commentable
		if(!$this->Comment->isValidForeignModel($foreignModelName)) {
			$this->Message->error(__('The model %s is not commentable', $foreignModelName));
			return;
		}
		
		// no instance id given
		if(is_null($foreignId)) {
			$this->Message->error(__('The id parameter is missing'));
			return;
		}
		
		// the instance id is invalid
		if (!Common::isUuid($foreignId)) {
			$this->Message->error(__('The id %s is invalid', $foreignId));
			return;
		}
		
		// the foreign instance does not exist
		$this->loadModel($foreignModelName);
		if (!$this->$foreignModelName->exists($foreignId)) {
			$this->Message->error(__('The foreign instance %s for the model %s doesn\'t exist or the user is not allowed to access it', $foreignId, $foreignModelName));
			return;
		}
		
		// check if data was provided
		if (!isset($postData['Comment'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}
		// add data to the posted data
		$postData['Comment']['foreign_model'] = $foreignModelName;
		$postData['Comment']['foreign_id'] = $foreignId;
		
		$this->Comment->create();
		$this->Comment->set($postData);
		// check if the data is valid
		if(!$this->Comment->validates()){
			$this->Message->error($this->Comment->validationErrors);
			return;
		}
		
		$fields = $this->Comment->getFindFields('add', User::get('Role.name'));
		$this->Comment->save($postData, true, $fields['fields']);
		
		$findData = array('Comment' => array('id' => $this->Comment->id));
		$findOptions = $this->Comment->getFindOptions('view', User::get('Role.name'), $findData);
		$this->set('data', $this->Comment->find('first', $findOptions));
		$this->Message->success(__('The comment was sucessfully added'));
	}

/**
 * Edit a comment
 * @param uuid id The uuid of the comment to edit 
 */
	public function edit($id = null) {
		// check the HTTP request method
		if (!$this->request->is('put')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}
		
		// the instance id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The id %s is invalid', $id));
			return;
		}
		
		// check if the comment exists
		if (!$this->Comment->exists($id)) {
			$this->Message->error(__('The comment does not exist'));
			return;
		}
		
		// Treat the posted data
		$pushData = $this->request->data;
		$pushData['Comment']['id'] = $id;
		$this->Comment->create();
		$this->Comment->setValidationRules('edit');
		$this->Comment->set($pushData);
		
		// check if the data is valid
		if(!$this->Comment->validates()){
			$this->Message->error($this->Comment->validationErrors);
			return;
		}
		
		// try to save
		$fields = $this->Comment->getFindFields('edit', User::get('Role.name'));
		$comment = $this->Comment->save($pushData, true, $fields['fields']);
		if ($comment === false) {
			$this->Message->error(__('The comment could not be updated'));
			return;
		}

		$findData = array('Comment' => array('id' => $this->Comment->id));
		$findOptions = $this->Comment->getFindConditions('view', User::get('Role.name'), $findData);
		$this->set('data', $this->Comment->find('first', $findOptions));
		$this->Message->success(__('The comment was sucessfully updated'));
	}

/**
 * Delete a comment
 * @param uuid id The uuid of the comment to delete 
 */
	public function delete($id = null) {
		// check the HTTP request method
		if (!$this->request->is('delete')) {
			$this->Message->error(__('Invalid request method, should be DELETE'));
			return;
		}
		
		// the instance id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The id %s is invalid', $id));
			return;
		}
		
		// check if the comment exists
		if (!$this->Comment->exists($id)) {
			$this->Message->error(__('The comment does not exist'));
			return;
		}
		
		// Delete the target comment and by cascading its children
		$this->Comment->delete($id, true);
		$this->Message->success(__('The comment was sucessfully deleted'));
	}
}
