<?php
/**
 * ItemsTags Controller
 *
 * @copyright   Copyright 2012, Passbolt.com
 * @license     http://www.passbolt.com/license
 * @package     app.Controller.GroupsController
 * @since       version 2.13.6
 */
class ItemTagsController extends AppController {

	public $helpers = array('PassboltAuth');

	/**
	 * Index
	 *
	 * @access public
	 */
	public function viewForeignItemTags($foreignModel = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModel);

		// check the HTTP request method
		if (!$this->request->is('get')) {
			$this->Message->error(__('Invalid request method, should be GET'));
			return;
		}

		// check if the target foreign model is commentable
		if(!$this->ItemTag->isValidForeignModel($foreignModelName)) {
			$this->Message->error(__('The model %s is not taggable', $foreignModelName));
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

		// find the comments
		$findData = array(
			'ItemTag' => array(
				'foreign_id' => $foreignId,
				'foreign_model' => $foreignModelName
			)
		);
		$findOptions = $this->ItemTag->getFindOptions('ItemTag.viewByForeignModel', User::get('Role.name'), $findData);

		$itemTags = $this->ItemTag->find('all', $findOptions);

		// Add the tag object to each entry
		$Tag = Common::getModel('Tag');
		foreach($itemTags as $k => $it) {
			$findData = array('Tag' => array('id' => $it['ItemTag']['tag_id']));
			$tagFindOptions = $Tag->getFindOptions('ItemTag.viewByForeignModel', User::get('Role.name'), $findData);
			$itemTags[$k]['Tag'] = $Tag->find('first', $findData);
		}
		$this->set('data', $itemTags);
		$this->Message->success();
	}

	/**
	 * Add a tag to a target taggable model instance
	 * @param string foreignModelName The target foreign model
	 * @param uuid foreignId The uuid of the target instance to create tags for
	 */
	public function addForeignItemTag($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);
		$postData = $this->request->data;

		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}

		// check if the target foreign model is commentable
		if(!$this->ItemTag->isValidForeignModel($foreignModelName)) {
			$this->Message->error(__('The model %s is not taggable', $foreignModelName));
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
		// the authorization to access the record is provided by the permissionable behavior, so if a user is not authorized to
		// access the instance reccord, the exists method should return false
		$this->loadModel($foreignModelName);
		if (!$this->$foreignModelName->exists($foreignId)) {
			$this->Message->error(__('The foreign instance %s for the model %s doesn\'t exist or the user is not allowed to access it', $foreignId, $foreignModelName));
			return;
		}

		// check if data was provided
		if (!isset($postData['ItemTag'])) {
			$this->Message->error(__('No data were provided'));
			return;
		}
		// add data to the posted data
		$postData['ItemTag']['foreign_model'] = $foreignModelName;
		$postData['ItemTag']['foreign_id'] = $foreignId;

		$this->ItemTag->create();
		$this->ItemTag->set($postData);
		// check if the data is valid
		if(!$this->ItemTag->validates()){
			$this->Message->error($this->ItemTag->validationErrors);
			return;
		}

		$fields = $this->ItemTag->getFindFields('ItemTag.add', User::get('Role.name'));
		$this->ItemTag->save($postData, true, $fields['fields']);

		// return the just inserted comment
		$findData = array('ItemTag' => array('id' => $this->Comment->id));
		$findOptions = $this->ItemTag->getFindOptions('ItemTag.view', User::get('Role.name'), $findData);
		$this->set('data', $this->ItemTag->find('first', $findOptions));
		$this->Message->success(__('The tag was sucessfully added'));
	}


	/**
	 * Delete an ItemTag.
	 * @param uuid id The uuid of the item_tag to delete
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
		if (!$this->ItemTag->exists($id)) {
			$this->Message->error(__('The item tag does not exist'));
			return;
		}

		// Delete the target itemTag
		$this->ItemTag->delete($id, true);
		$this->Message->success(__('The ItemTag was sucessfully deleted'));
	}

}
