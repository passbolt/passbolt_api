<?php
/**
 * Profile Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('ProfileAvatar', 'Model');

/**
 * @SWG\Definition(
 * @SWG\Xml(name="Profile"),
 * @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="Profile UUID",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="user_id",
 *     type="string",
 *     description="User UUID",
 *     example="d1acbfc1-78d8-3e11-ad8b-7ab1eb0332d3"
 *   ),
 * @SWG\Property(
 *     property="first_name",
 *     type="string",
 *     description="First name",
 *     example="Ada"
 *   ),
 * @SWG\Property(
 *     property="last_name",
 *     type="string",
 *     description="Last name",
 *     example="Lovelace"
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
 *   )
 * )
 *
 * Note: title, gender, date of birth, modified_by, created_by are available in the model as
 * future improvements but are not used by the API at the moment.
 *
 */
class Profile extends AppModel {

/**
 * Details of belongs to relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'User',
	];

/**
 * Details of has one relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasOne = [
		'Avatar' => [
			'className' => 'ProfileAvatar',
			'foreignKey' => 'foreign_key',
		],
	];

/**
 * Get the validation rules upon context
 *
 * @param null|string $case optional
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		$default = [
			'user_id' => [
				'uuid' => [
					'rule' => 'uuid',
					'required' => 'create',
					'allowEmpty' => false,
					'message' => __('UUID must be in correct format')
				],
				'exist' => [
					'rule' => ['userExists', null],
					'message' => __('The user id provided does not exist')
				],
			],
			'gender' => [
				'required' => [
					'allowEmpty' => false,
					'rule' => ['notBlank'],
					'message' => __('Gender cannot be empty')
				],
				'inList' => [
					'rule' => ['inList', ['m', 'f']],
					'message' => __('Gender can be only "m" or "f"')
				]
			],
			'date_of_birth' => [
				'date' => [
					'rule' => ['date', 'ymd'],
					'message' => 'Enter a valid date of birth in YY-MM-DD format.',
					'allowEmpty' => false
				]
			],
			'title' => [
				'inList' => [
					'rule' => ['inList', ['Mr', 'Ms', 'Mrs', 'Dr']],
					'message' => __('A valid title has to be provided'),
					'allowEmpty' => false
				],
			],
			'first_name' => [
				'alphaNumericAndSpecial' => [
					'rule' => "/^[\p{L} \-']*$/u",
					'required' => true,
					'allowEmpty' => false,
					'message' => __('First name should only contain alphabets and the special characters : - \'')
				],
				'size' => [
					'rule' => ['lengthBetween', 2, 64],
					'message' => __('First name should be between %s and %s characters long'),
				]
			],
			'last_name' => [
				'alphaNumericAndSpecial' => [
					'rule' => "/^[\p{L} \-']*$/u",
					'required' => true,
					'allowEmpty' => false,
					'message' => __('Last name should only contain alphabets and the special characters : - \'')
				],
				'size' => [
					'rule' => ['lengthBetween', 2, 64],
					'message' => __('Last name should be between %s and %s characters long'),
				]
			],
		];
		return $default;
	}

/**
 * Return the list of fields to be returned by a find operation in given context
 *
 * @param string $case context ex: login, activation
 * @param string $role optional user role if needed to build the options
 * @return array $fields
 * @access public
 */
	public static function getFindFields($case = null, $role = null, $data = null) {
		switch ($case) {
			case 'view':
				$fields = [
					'fields' => [
						'Role.id',
						'Role.name'
					]
				];
				break;
			case 'User::save':
			case 'User::edit':
				$fields = [
					'fields' => [
						'user_id',
						'first_name',
						'last_name',
					]
				];
				break;
			default:
				$fields = [
					'fields' => []
				];
				break;
		}

		return $fields;
	}

/**
 * AfterFind callback.
 * Used mainly to initialize default avatars.
 * It is added here, because ProfileAvatar after Find is not executed if the result is empty.
 *
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed Result of the find operation
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterfind
 */
	public function afterFind($results, $primary = false) {
		if ($primary === false) {
			foreach ($results as $key => $result) {
				if (empty($result['Profile']['Avatar'])) {
					$result['Profile']['Avatar'] = [];
				}
				$results[$key]['Profile']['Avatar'] = $this->Avatar->addPathsInfo($result['Profile']['Avatar']);
			}
		} else {
			$results['Avatar'] = empty($results['Avatar']) ? [] : $results['Avatar'];
			$results['Avatar'] = $this->Avatar->addPathsInfo($results['Avatar']);
		}

		return $results;
	}
}
