<?php
/**
 * Favorite Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Favorite extends AppModel {
	var $belongsTo = array('User');

/**
 * Check if the given foreign model is allowed
 * @param string foreignModel The foreign model key to test
 * @return boolean
 */
	public function isValidForeignModel($foreignModel) {
		return in_array($foreignModel, Configure::read('Favorite.foreignModels'));
	}

/**
 * Indicates if a resource is marked as favorite
 *
 * @param string $foreign_id UUID of the resource
 * @param string $model name of the resource type
 * @return bool
 * @access public
 */
	static function isFavorited($foreign_id, $model = null) {
		$_this = Common::getModel('Favorite');
		$conditions =	array(
			'conditions' => array(
				'foreign_model' => $model,
				'user_id' => User::get('id'),
				'foreign_id' => $foreign_id
			),
			'fields' => array(
				'id'
			)
		);
		$favorites = $_this->find('first', $conditions);
		return !empty($favorites);
	}

/**
 * STATIC Get the number of faved items per model
 * @return string $archived {true, false, both}
 * @return array $favorites
 */
	static function getAllCount($archives = true) {
		$_this = Common::getModel('Favorite');

		// Get potential models from list
		$models = @array_keys(Configure::read('Favorite.foreignModels'), true);

		// default condition
		$conditions =	array(
			'conditions' => array(
				'foreign_model' => $models,
				'user_id' => User::get('id')
			),
			'fields' => array(
				'foreign_model',
				'COUNT(foreign_model) as count'
			),
			'group' => array(
				'Favorite.foreign_model'
			)
		);

		// Search for faved records
		$favorites = $_this->find('all', $conditions);

		//tidy up
		foreach ($favorites as $i => $fav) {
			$favorites[$i]['Favorite']['count'] = $fav[0]['count'];
			unset($favorites[$i][0]);
		}

		return $favorites;
	}

}