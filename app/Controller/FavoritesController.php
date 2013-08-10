<?php
/**
 * Favorites controller
 * Control the starred items
 *
 * @copyright		Copyright 2013, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Controller.FavoritesController
 * @since				version 2.13.09
 */
class FavoritesController extends AppController {

/**
 * Add a foavorite for a target model instance
 * @param string foreignModelName The target foreign model
 * @param uuid foreignId The uuid of the target instance to get comments for 
 */
	public function add($foreignModelName = null, $foreignId = null) {
		$foreignModelName = Inflector::camelize($foreignModelName);
		$userId = User::get('id');
		
		// check the HTTP request method
		if (!$this->request->is('post')) {
			$this->Message->error(__('Invalid request method, should be POST'));
			return;
		}
		
		// check if the target foreign model is favoritable
		if(!$this->Favorite->isValidForeignModel($foreignModelName)) {
			$this->Message->error(__('The model %s is not favoritable', $foreignModelName));
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

		$favorite = $this->Favorite->find('first', array(
			'conditions' => array('user_id' => $userId, 'foreign_id' => $foreignId)
		));

		// Already stared
		if (!empty($favorite)) {
			$this->Message->error(__('This record was already starred!'));
			return;
		} else {
			$this->Favorite->create(array(
				'user_id' => $userId,
				'foreign_id' => $foreignId,
				'foreign_model' => strtolower($foreignModelName)
			));
			$favorite = $this->Favorite->save();
			$this->set('data', $favorite);
			$this->Message->success(__('This record was successfully starred!'));
			return;
		}
	}

	/**
	 * Unfav/unstar a given record for a given model
	 *
	 * @param UUID $id of the resource
	 * @param string $model name
	 * @return void
	 */
	function delete($id) {
		$favorite = $this->Favorite->findById($id);
		$userId = User::get('id');

		// check the HTTP request method
		if (!$this->request->is('delete')) {
			$this->Message->error(__('Invalid request method, should be DELETE'));
			return;
		}

		// the id is invalid
		if (!Common::isUuid($id)) {
			$this->Message->error(__('The id %s is invalid', $id));
			return;
		}

		// no favorite found
		if (empty($favorite)) {
			$this->Message->error(__('Oops, This record was not starred in the first place!'));
			return;
		}
		
		// if the current user is not the owner of the favorite
		if($favorite['Favorite']['user_id'] != $userId) {
			$this->Message->error(__('Oops, This star is not yours!'));
			return;
		}
		
		$this->Favorite->delete($favorite['Favorite']['id']);
		$this->Message->success(__('This record was removed from your starred item list.'));
	}
}
