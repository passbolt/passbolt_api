<?php
/**
 * Tag Model
 *
 * @copyright (c) 2015-present Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
/**
 * @SWG\Definition(
 * @SWG\Xml(name="Tag"),
 * @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="Tag UUID",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="name",
 *     type="string",
 *     description="Name of the tag",
 *     example="magpie"
 *   ),
 * @SWG\Property(
 *     property="created",
 *     type="string",
 *     description="Creation date",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="modified",
 *     type="string",
 *     description="Last modification date",
 *     example="﻿2016-04-26 17:01:01"
 *   ),
 * @SWG\Property(
 *     property="created_by",
 *     type="string",
 *     description="Id of the user who created the tag"
 *   ),
 * @SWG\Property(
 *     property="modified_by",
 *     type="string",
 *     description="Id of the last user who updated the tag"
 *   )
 * )
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
 * @param null|string $case optional validation case
 * @return array cakephp validation rules
 */
	public static function getValidationRules($case = null) {
		$rules = [
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
					'message' => __('Tag should only contain alphabets, numbers, spaces and the special characters \' " -')
				],
				'size' => [
					'rule' => ['lengthBetween', 3, 64],
					'message' => __('Tag should be between %s and %s characters long'),
				]
			]
		];
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
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = 'view', $role = null) {
		$fields = ['fields' => []];
		switch ($case) {
			case 'ItemTag.viewByForeignModel':
			case 'Tag.view':
				$fields = [
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
				$fields = [
					'fields' => [
						'name',
						'created_by',
						'modified_by',
					]
				];
				break;
		}
		return $fields;
	}
}
