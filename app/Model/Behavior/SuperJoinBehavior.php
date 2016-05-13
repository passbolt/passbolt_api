<?php
/**
 * SuperJoin Behavior
 *
 * Usage:
 *  Active the var $actsAs in your model to use the SuperJoin
 *  - var $actsAs = array('SuperJoin');
 *
 *  In your find you have to declare the superjoin
 *  - in controller: $this->ModelName->find("all", array("superjoin" => array("AssociationModelName1", "AssociationModelName2"), "conditions" => array()));
 *  - in model: $this->find("all", array("superjoin" => array("AssociationModelName1", "AssociationModelName2"), "conditions" => array()));
 *
 *   Make your find with HABTM conditions and be happy =]
 *
 * Obs:
 *  Work with containable:
 *  - If you active some habtm association Model: only the results of this association conditions will show up
 *  - This active models still not work with containable =/
 *  - The others associations still working default
 *  - You can use this with conditions for hasMany, belongsTo and hasOne conditions (cake default)
 *
 * News:
 *   - Version 2.1 PHPCS
 *   - Version 2.0 to work with cakephp 2.x
 *   - Version 1.1 dont need the active anymore.
 *   - Changes to work with paginate
 *   - Works like containble
 *
 * @version 2.1
 * @link http://github.com/Scoup/SuperJoin
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author Léo Haddad (scoup001@gmail.com) & Passbolt team rocket (contact@passbolt.com)
 */
class SuperJoinBehavior extends ModelBehavior {

/**
 * Models HABTM to join tables
 *
 * @var array
 */
	private $__targets = [];

/**
 * Default Options
 *
 * @var array $_defaultOptions
 */
	protected $_defaultOptions = [
		'type' => 'left'
	];

	public $options = [];

/**
 * Setup this behavior with the specified configuration settings.
 *
 * @param Model $model Model using this behavior
 * @param array $config Configuration settings for $model
 * @return void
 */
	public function setup(Model $model, $config = []) {
		$this->options = Hash::merge($this->_defaultOptions, $config);
	}

/**
 * Called before a find to change the joins
 *
 * @param Model $model Model using this behavior
 * @param array $query Data used to execute this query, i.e. conditions, order, etc.
 * @return bool|array False or null will abort the operation. You can return an array to replace the
 *   $query that will be eventually run.
 */
	public function beforeFind(Model $model, $query) {
		if (isset($query['superjoin'])) {
			$this->setModels($query['superjoin']);
			foreach ($this->__targets as $target) {
				$habtm = $model->hasAndBelongsToMany[$target];
				App::import('Model', $target);
				$association = new $target;
				$query['joins'][] = [
					'table' => $habtm['joinTable'],
					'alias' => $habtm['with'],
					'type' => $this->options['type'],
					'conditions' => [
						"$model->name.$model->primaryKey = {$habtm["with"]}.{$habtm["foreignKey"]}"
					]
				];
				$query['joins'][] = [
					'table' => $association->useTable,
					'alias' => $association->name,
					'type' => $this->options['type'],
					'conditions' => [
						"$association->name.$association->primaryKey = {$habtm["with"]}.{$habtm["associationForeignKey"]}"
					]
				];
			}
		}

		return $query;
	}

/**
 * Check if the superjoin is a string or Array and set it a array
 *
 * @param array $targets model names
 * @return void
 */
	public function setModels($targets) {
		if (!is_array($targets)) {
			$targets = [$targets];
		}
		$this->__targets = $targets;
	}
}
