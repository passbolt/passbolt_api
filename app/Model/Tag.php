<?php

/**
 * Tag Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
class Tag extends AppModel {

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = ['Trackable'];

/**
 * Details of has and belongs to many relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasAndBelongsToMany = [
		'Resource' => [
			'className' => 'Resource',
			'joinTable' => 'items_tags',
		]
	];

/**
 * Get the validation rules upon context
 *
 * @param string context
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = 'default') {
		$default = [
			'id' => [
				'uuid' => [
					'rule' => 'uuid',
					'message' => __('UUID must be in correct format')
				]
			],
			'name' => [
				'alphaNumeric' => [
					'required' => 'create',
					'allowEmpty' => false,
					'rule' => "/^[\p{L}\d '\"-]*$/u",
					'message' => __('Name should only contain alphabets, numbers, spaces and the special characters \' " -')
				],
				'size' => [
					'rule' => ['lengthBetween', 3, 64],
					'message' => __('Name should be between %s and %s characters long'),
				]
			]
		];
		switch ($case) {
			default:
			case 'default':
				$rules = $default;
				break;
		}

		return $rules;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 */
	public static function getFindConditions($case = 'Tag.view', $role = Role::USER, $data = null) {
		$conditions = [];

		switch ($case) {
			case 'ItemTag.viewByForeignModel':
			case 'Tag.view':
				$conditions = [
					'conditions' => [
						'Tag.id' => $data['Tag']['id']
					]
				];
				break;

		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 *
 * @param string $case context ex: login, activation
 * @return $condition array
 */
	public static function getFindFields($case = 'view', $role = Role::USER) {
		$returnValue = ['fields' => []];
		switch ($case) {
			case 'ItemTag.viewByForeignModel':
			case 'Tag.view':
				$returnValue = [
					'fields' => [
						'Tag.id',
						'Tag.name',
						'Tag.created',
						'Tag.modified',
						'Tag.created_by',
						'Tag.modified_by',
					]
				];
				break;
			case 'Tag.add':
				$returnValue = [
					'fields' => [
						'name',
						'created_by',
						'modified_by',
					]
				];
				break;
		}

		return $returnValue;
	}

}
