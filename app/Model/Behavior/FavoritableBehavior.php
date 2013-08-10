<?php
/**
 * Favoritable Behavior
 * Allow adding favorites
 *
 * @copyright		Copyright 2013, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Model.Behavior.FavoritableBehavior
 * @since				version 2.13.09
 */
class FavoritableBehavior extends ModelBehavior {

	/**
	 * Contain settings indexed by model name.
	 * @var array
	 * @access private
	 */
	var $__settings = array();

	/**
	 * Initiate behavior for the model using settings.
	 *
	 * @param object $Model Model using the behaviour
	 * @param array $settings Settings to override for model.
	 * @access public
	 */
	function setup(&$Model, $settings = array()){
		$Model->bindModel(array('hasOne' => array(
			'Favorite' => array(
				'dependent' => true,
				'foreignKey' => 'foreign_id',
				'conditions' => array('Favorite.user_id' => User::get('id'))
			)
		)), false);
	}
}