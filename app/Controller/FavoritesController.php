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
	 * Before filter function redefinition
	 * @see Controller::beforeFilter
	 */
	function beforeFilter() {
		parent::beforeFilter();
		$this->models = array_keys(Configure::read('App.favorites.models'),true);
		foreach ($this->models as $model) {
			$this->$model = Common::getModel($model);
			$this->Favorite->bindModel(
				array('belongsTo' => array(
					$model => array('foreignKey' => 'foreign_id')
				))
			);
		}
	}

	/**
	 * Add a favorite for a given user
	 * @param UUID $id
	 * @param string $model name
	 * @return void
	 */
	function add($id, $model) {
		$userId = User::get('id');
		$favorite = $this->Favorite->find('first', array(
			'conditions' => array('user_id' => $userId, 'foreign_id' => $id)
		));
		// what else could go wrong?
		if (!empty($favorite)) {
			$this->Message->warning(
				'WARNING_FAVORITE_EXIST',
				__('This record was already starred!', true),
				true
			);
		} else {
			$this->Favorite->create(array(
				'user_id' => $userId,
				'foreign_id' => $id,
				'model' => strtolower($model)
			));
			$this->Favorite->save();
			$this->Favorite->load(User::get('id'));
			$this->Message->success(
				__('This record was successfully starred!', true),
				true
			);
		}
	}

	/**
	 * Unfav/unstar a given record for a given model
	 *
	 * @param UUID $id of the resource
	 * @param string $model name
	 * @return void
	 */
	function delete($id,$model) {
		$userId = User::get('id');
		$favorite = $this->Favorite->find('first', array(
			'conditions' => array('user_id' => $userId, 'foreign_id' => $id)
		));
		// what else could go wrong?
		if (empty($favorite)) {
			$this->Message->warning(
				'WARNING_FAVORITE_DONTEXIST',
				__('Oops, This record was not starred in the first place!',true),
				true
			);
		} else {
			$this->Favorite->delete($favorite['Favorite']['id']);
			$this->Favorite->load(User::get('id'));
			$this->Message->success(
				__('This record was removed from your starred item list.', true),
				true
			);
		}
	}
}
