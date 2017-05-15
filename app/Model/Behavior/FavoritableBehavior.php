<?php
/**
 * Favoritable Behavior
 * Allow adding favorites
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class FavoritableBehavior extends ModelBehavior {

/**
 * Contain settings indexed by model name.
 *
 * @var array
 * @access private
 */
	private $__settings = [];

/**
 * Setup this behavior with the specified configuration settings.
 *
 * @param Model $model Model using this behavior
 * @param array $config Configuration settings for $model
 * @return void
 */
	public function setup(Model $model, $config = []) {
		$model->bindModel([
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
