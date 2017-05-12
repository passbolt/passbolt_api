<?php
/**
 * Favorite Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Favorite extends AppModel {

	public $belongsTo = ['User'];

/**
 * Check if the given foreign model is allowed
 *
 * @param string $foreignModel The foreign model key to test
 * @return bool
 */
	public function isValidForeignModel($foreignModel) {
		return in_array($foreignModel, Configure::read('Favorite.foreignModels'));
	}
}