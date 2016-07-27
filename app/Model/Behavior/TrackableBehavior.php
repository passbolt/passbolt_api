<?php
/**
 * Trackable Behavior - autopopulate created_by, modified_by
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('User', 'Model');

class TrackableBehavior extends ModelBehavior {

/**
 * Before validate callback
 *
 * @param Model $model Model using this behavior
 * @param array $options Options passed from Model::save().
 * @return mixed False or null will abort the operation. Any other result will continue.
 * @see Model::save()
 */
	public function beforeValidate(Model $model, $options = []) {
		if (!isset($model->data[$model->alias]['created_by'])) {
			if (empty($model->data[$model->alias]['id'])) {
				$model->data[$model->alias]['created_by'] = User::get('id');
			}
		}
		if (!isset($model->data[$model->alias]['modified_by'])) {
			$model->data[$model->alias]['modified_by'] = User::get('id');
		}
		return true;
	}

/**
 * Check the user is owner of the given record
 *
 * @param Model $model a reference to the model
 * @param string $id uuid of the user
 * @return bool
 * @access public
 */
	public function isOwner(Model $model, $id) {
		$result = $model->findById($id);
		return $result[$model->alias]['created_by'] == User::get('id');
	}
}
