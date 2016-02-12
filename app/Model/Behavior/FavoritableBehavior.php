<?php
/**
 * Favoritable Behavior
 * Allow adding favorites
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class FavoritableBehavior extends ModelBehavior {

/**
 * Contain settings indexed by model name.
 *
 * @var array
 * @access private
 */
	var $__settings = [];

/**
 * Initiate behavior for the model using settings.
 *
 * @param object $Model Model using the behaviour
 * @param array $settings Settings to override for model.
 * @access public
 */
	function setup(Model $Model, $settings = []) {
		$Model->bindModel([
			'hasOne' => [
				'Favorite' => [
					'dependent' => true,
					'foreignKey' => 'foreign_id',
					'conditions' => ['Favorite.user_id' => User::get('id')]
				]
			]
		], false);
	}
}
