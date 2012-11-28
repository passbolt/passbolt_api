<?php
/**
 * Trackable Behavior - autopopulate created_by, modified_by
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @license      http://www.passbolt.com/license
 * @package      app.Model.Behavior.TrackableBehavior
 * @since        version 2.12.7
 */
App::uses('User', 'Model');
class TrackableBehavior extends ModelBehavior {

/**
 * Before validate callback
 * @return bool success
 * @access public
 */
	public function beforeValidate(&$model) {
		if (empty($model->data[$model->alias]['id'])) {
			$model->data[$model->alias]['created_by'] = User::get('id');
		}
		$model->data[$model->alias]['modified_by'] = User::get('id');
		return true;
	}
}

