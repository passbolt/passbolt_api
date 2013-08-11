<?php
/**
 * SuperJoin Behavior
 *
 * Usage:
 * 		Active the var $actsAs in your model to use the SuperJoin
 * 			- var $actsAs = array('SuperJoin');
 *
 * 		In your find you have to declare the superjoin
 * 			- in controller: $this->ModelName->find("all", array("superjoin" => array("AssociationModelName1", "AssociationModelName2"), "conditions" => array()));
 * 			- in model: $this->find("all", array("superjoin" => array("AssociationModelName1", "AssociationModelName2"), "conditions" => array()));
 *
 *
 * 		Make your find with HABTM conditions and be happy =]
 *
 * Obs:
 * 		Work with containable:
 * 			- If you active some habtm association Model: only the results of this association conditions will show up
 * 			- This active models still not work with containable =/
 * 			- The others associations still working default
 * 			- You can use this with conditions for hasMany, belongsTo and hasOne conditions (cake default)
 *
 *  News:
 *			- Version 2.0 to work with cakephp 2.x
 *  		- Version 1.1 dont need the active anymore.
 *  		- Changes to work with paginate
 *  		- Works like containble
 *
 * @version 2.0
 * @link http://github.com/Scoup/SuperJoin
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 * @author Léo Haddad (scoup001@gmail.com)
 *
 */
class SuperJoinBehavior extends ModelBehavior{

	/**
	 * Private status of behavior
	 * @var unknown_type
	 */
	private $active = false;

	/**
	 * Models HABTM to join tables
	 * @var array
	 */
	private $targets = array();

	/**
	 * Default Options
	 * @var unknown_type
	 */
	private $_defaultOptions = array(
		"type" => "left"
	);

	public $options = array();


	/*
	 * Configure the options
	 */
	public function setup(&$model, $settings = array()){
		$this->options = Set::merge($this->_defaultOptions, $settings);
	}

	/*
	 * Called before find to change the joins
	 */
	public function beforeFind(&$model, $query){
		if(isset($query["superjoin"])){
			$this->setModels($query["superjoin"]);
			foreach($this->targets as $target){
				$habtm = $model->hasAndBelongsToMany[$target];
				App::import("Model", $target);
				$association = new $target;
				$query["joins"][] = array(
						"table" => $habtm["joinTable"],
						"alias" => $habtm["with"],
						"type" => $this->options["type"],
						"conditions" => array(
							"$model->name.$model->primaryKey = {$habtm["with"]}.{$habtm["foreignKey"]}"
						)
					);
				$query["joins"][] = array(
						"table" => $association->useTable,
						"alias" => $association->name,
						"type" => $this->options["type"],
						"conditions" => array(
							"$association->name.$association->primaryKey = {$habtm["with"]}.{$habtm["associationForeignKey"]}"
						)
				);
			}
		}
		return $query;
	}

	/**
	 * Check if the superjoin is a string or Array and set it a array
	 */
	public function setModels($targets){
		if(!is_array($targets)) $targets = array($targets);
		$this->targets = $targets;
	}
}