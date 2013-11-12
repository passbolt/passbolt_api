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
			$this->Message->error(__('Invalid request method, should be GET'));
			return;
		}
		
		// check if the target foreign model is commentable
		if(!$this->Comment->isValidForeignModel($foreignModelName)) {
			$this->Message->error(__('The model %s is not commentable', $foreignModelName));
			return;
		}
		
		// no instance id given
		if(is_null($foreignId)) {
			$this->Message->error(__('The %s id is missing', $foreignModelName));
			return;
		}
		
		// the instance id is invalid
		if (!Common::isUuid($foreignId)) {
			$this->Message->error(__('The %s id is invalid', $foreignModelName));
			return;
		}
		
		// the foreign instance does not exist
		$this->loadModel($foreignModelName);
		if (!$this->$foreignModelName->exists($foreignId)) {
			$this->Message->error(__('The %s does not exist', $foreignModelName), array('code' => 404));
			return;
		}
		
		// find the comments
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
			$this->Message->error(__('The %s id is missing', $foreignModelName));
			return;
		}
		
		// the instance id is invalid
		if (!Common::isUuid($foreignId)) {
			$this->Message->error(__('The %s id is invalid', $foreignModelName));
			return;
		}
		
		// the foreign instance does not exist
		// the authorization to access the record is provided by the permissionable behavior, so if a user is not authorized to
		// access the instance reccord, the exists method should return false
		$this->loadModel($foreignModelName);
		if (!$this->$foreignModelName->exists($foreignId)) {
			$this->Message->error(__('The %s does not exist', $foreignModelName), array('code' => 404));
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
			$this->Message->error(__('Could not validate data'));
			return;
		}
		
		$fields = $this->Comment->getFindFields('add', User::get('Role.name'));
		$this->Comment->save($postData, true, $fields['fields']);
		
		// return the just inserted comment
		$findData = array('Comment' => array('id' => $this->Comment->id));
		$findOptions = $this->Comment->getFindOptions('view', User::get('Role.name'), $findData);
		$this->set('data', $this->Comment->find('first', $findOptions));
		$this->Message->success(__('The comment was sucessfully added'));
	}

/**
 * Edit a comment.
 * The user who wants to edit a comment has to be the owner of the comment.
 * @param uuid id The uuid of the comment to edit 
 */
	public function edit($id = null) {
		// check the HTTP request method
		if (!$this->request->is('put')) {
			$this->Message->error(__('Invalid request method, should be PUT'));
			return;
		}

		// check if the is provided
		if (!isset($id)) {
			$this->Message->error(__('The comment id is missing'));
			return;
		}

		// the instance id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The comment id is invalid'));
			return;
		}
		
		// check if the comment exists
		if (!$this->Comment->exists($id)) {
			$this->Message->error(__('The comment does not exist'), array('code' => 404));
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
			$this->Message->error(__('Unable to validate the pushed data'));
			return;
		}
		
		// check the user is the owner of the comment or it has the role to edit it
		if(!$this->Comment->isOwner($id)) {
			$this->Message->error(__('Your are not allowed to edit this comment'), array('code' => 403));
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
 * Delete a comment.
 * The user who wants to delete a comment has to be the owner of the comment.
 * By deleteing a comment the children comments are deleted too.
 * @param uuid id The uuid of the comment to delete 
 */
	public function delete($id = null) {
		// check the HTTP request method
		if (!$this->request->is('delete')) {
			$this->Message->error(__('Invalid request method, should be DELETE'));
			return;
		}
		
		// check if the is provided
		if (!isset($id)) {
			$this->Message->error(__('The comment id is missing'));
			return;
		}

		// the instance id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The comment id is invalid'));
			return;
		}
		
		// check if the comment exists
		if (!$this->Comment->exists($id)) {
			$this->Message->error(__('The comment does not exist'), array('code' => 404));
			return;
		}
		
		// check the user is the owner of the comment or it has the role to delete it
		if(!$this->Comment->isOwner($id)) {
			$this->Message->error(__('Your are not allowed to delete this comment'), array('code' => 403));
			return;
		}
		
		// Delete the target comment and by cascading its children
		$this->Comment->delete($id, true);
		$this->Message->success(__('The comment was sucessfully deleted'));
	}
}
