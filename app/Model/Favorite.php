<?php
/**
 * Favorite Model
 *
 * @copyright		Copyright 2013, Passbolt.com
 * @license			http://www.passbolt.com/license
 * @package			app.Model.Favorite
 * @since				version 2.13.09
 */
class Favorite extends AppModel {
	var $belongsTo = array('User');

	/**
	 * Indicates if a resource is marked as favorite
	 *
	 * @param string $foreign_id UUID of the resource
	 * @param string $model name of the resource type
	 * @return bool
	 * @access public
	 */
	static function isFavorited($foreign_id,$model=null) {
		$_this = Common::getModel('Favorite');
		$conditions =	array(
			'conditions' => array(
				'model' => $model,
				'user_id' => User::get('id'),
				'foreign_id' => $foreign_id
			),
			'fields' => array(
				'id'
			)
		);
		$favorites = $_this->find('first',$conditions);
		return !empty($favorites);
	}

	/**
	 * STATIC Get the number of faved items per model
	 * @return string $archived {true, false, both}
	 * @return array $favorites
	 */
	static function getAllCount($archives=true) {
		$_this = Common::getModel('Favorite');

		// Get potential models from list
		$models = @array_keys(Configure::read('App.favorites.models'),true);

		// default condition
		$conditions =	array(
			'conditions' => array(
				'model' => $models,
				'user_id' => User::get('id')
			),
			'fields' => array(
				'model',
				'COUNT(model) as count'
			),
			'group' => array(
				'Favorite.model'
			)
		);

		// Bind the models if the search depends on record's status
		if ($archives!='both' && is_bool($archives)) {
			Favorite::__bindArchivableModels(&$_this, &$models,&$conditions,$archives);
		}

		// Search for faved records
		$favorites = $_this->find('all',$conditions);
		//pr($favorites);

		//tidy up
		foreach ($favorites as $i => $fav) {
			$favorites[$i]['Favorite']['count'] = $fav[0]['count'];
			unset($favorites[$i][0]);
		}

		return $favorites;
	}

	/**
	 * STATIC - Prepare static find
	 * Bind models and define default find conditions
	 * @param $_this instance of Favorites model
	 * @param $conditions find conditions
	 * @access private
	 * @TODO move in archivable behavior?
	 */
	static function __bindArchivableModels(&$_this, &$models, &$conditions,$archived) {
		$softDeleteModels = @array_keys(Configure::read('App.softdelete.models'),true);
		$softDeleteFields = Configure::read('App.softdelete.fields');
		$models = array_intersect($models, $softDeleteModels);
		$options = $hasManyOptions = array();

		//$conditions = array('Favorite.user_id' => User::get('id'));
		foreach ($models as $i => $model) {
			$options[$model] = array('foreignKey' => 'foreign_id');
			$Model = Common::getModel($model);
			$Model->bindModel(array('hasMany' => array(
				'Favorite' => array(
					'dependent' => true,
					'foreignKey' => 'foreign_id'
				)
			)), false);

			// dont retrieve archived records unless required
			foreach ($softDeleteFields as $field) {
				if (array_key_exists($field, $Model->_schema)) {
					$alias = $Model->alias;
					$value = $archived ? '1' : '0';
					$conditions['conditions'][] = '(' . $alias . '.' . $field . '='. $value . ' || ' . $alias . '.id IS NULL)';
				}
			}
		}
		$conditions['contain'] = $models;
		$_this->bindModel(array('belongsTo' => $options), false);
	}
}
