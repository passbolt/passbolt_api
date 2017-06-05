<?php
/**
 * User Model
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */

App::uses('AuthComponent', 'Controller/Component');
App::uses('Common', 'Controller/Component');
App::uses('Role', 'Model');
App::uses('GroupUser', 'Model');
App::uses('Security', 'Utility');
App::uses('CakeEvent', 'Event');

/**
 * @SWG\Definition(
 * @SWG\Xml(name="User"),
 * @SWG\Property(
 *     property="id",
 *     type="string",
 *     description="UUID of the user, the primary identifier",
 *     example="e3ed5a94-8ef0-35a5-a01f-8e62539b758b"
 *   ),
 * @SWG\Property(
 *     property="username",
 *     type="string",
 *     description="Email address of the user",
 *     example="ada@passbolt.com"
 *   ),
 * @SWG\Property(
 *     property="role_id",
 *     type="string",
 *     description="The UUID of the user role",
 *     example="d1acbfc1-78d8-3e25-ad8b-7ab1eb0332dc"
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
 *     description="Id of the user who created the user"
 *   ),
 * @SWG\Property(
 *     property="modified_by",
 *     type="string",
 *     description="Id of the user who last modified the user"
 *   )
 * )
 */
class User extends AppModel {

/**
 * Model Name
 *
 * @access public
 */
	public $name = 'User';

/**
 * Model behaviors
 *
 * @link http://api20.cakephp.org/class/model#
 */
	public $actsAs = [
		'Containable',
		'Trackable'
	];

/**
 * Details of belongs to relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = [
		'Role'
	];

/**
 * Details of the hasOne relationships
 *
 * @var array
 */
	public $hasOne = [
		'Profile',
		'Gpgkey',
		'AuthenticationToken'
	];

/**
 * Details of has many relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = [
		'GroupUser',
		'Secret',
		'UserResourcePermission',
		// Custom join with ControllerLog to retrieve the last logged in date.
		// The results of this will be processed in the afterFind
		// and integrated directly in the user object with column name last_logged_in.
		'LastLoggedIn' => [
			'className' => 'ControllerLog',
			'order' => ['LastLoggedIn.created' => 'DESC'],
			'conditions' => ['LastLoggedIn.message' => 'login_success', 'LastLoggedIn.user_id' => 'User.id'],
			'limit' => 1,
		]
	];

/**
 * Details of has and belongs to many relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasAndBelongsToMany = [
		'Group' => [
			'className' => 'Group'
		]
	];

/**
 * Anonymous user
 */
	const ANONYMOUS = 'anonymous@passbolt.com';

/**
 * Get the validation rules for a given context
 *
 * @param string $case (optional) The target validation case if any.
 * @return array validation rules
 * @access public
 */
	public static function getValidationRules($case = null) {
		$default = [
			'username' => [
				'required' => [
					'required' => 'create',
					'allowEmpty' => false,
					'rule' => ['notBlank'],
					'message' => __('A username is required')
				],
				'email' => [
					'rule' => ['email'],
					'message' => __('The username should be a valid email address')
				],
				'login' => [
					'rule' => ['checkUsernameNotExist'],
					'on' => 'create',
					'shared' => false,
					'message' => __('The username has already been taken')
				]
			],
			'role_id' => [
				'required' => [
					'required' => 'create',
					'allowEmpty' => false,
					'rule' => ['notBlank'],
					'message' => __('A role should be provided')
				],
				'validRole' => [
					'shared' => false,
					'rule' => ['checkValidRole'],
					'message' => __('The role provided is not valid')
				],
				'cantRemoveOwnAdminRole' => [
					'shared' => false,
					'on' => 'update',
					'rule' => ['checkCantRemoveOwnAdminRole'],
					'message' => __('It is not possible to remove your own admin role')
				],
			],
		];
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}

		return $rules;
	}

/**
 * Custom validation rule
 * Check if the role provided is a valid one
 *
 * @param array $check with 'role_id' key set
 * @return bool
 * @access public
 */
	public function checkValidRole($check) {
		if (!isset($check['role_id']) || empty($check['role_id'])) {
			return false;
		}
		$role = $this->Role->findById($check['role_id']);

		// Role is in DB and is not guest
		if (empty($role)) {
			return false;
		}
		if (in_array($role['Role']['name'], [Role::USER, Role::ADMIN, Role::ROOT])) {
			return true;
		}

		// there can be only one guest user
		if ($role['Role']['name'] === Role::GUEST) {
			$count = $this->find('count', [ 'conditions' => ['role_id' => $check['role_id']]]);
			if ($count === 0) {
				return true;
			}
		}

		return false;
	}

/**
 * Custom validation rule
 * Check if the username does'nt already exist.
 * We consider that a username exists if there is already the same username, with a deleted value to 0.
 * In other terms, a user that has been deleted will not be considered as already existing.
 *
 * @param array $check with 'role_id' key set
 * @return bool
 * @access public
 */
	public function checkUsernameNotExist($check) {
		if (!isset($check['username']) || empty($check['username'])) {
			return false;
		}

		$user = $this->find('first', [
				'conditions' => [
					'username' => $check['username'],
					'deleted' => 0,
				]
			]);

		if (!empty($user)) {
			return false;
		}

		return true;
	}

/**
 * Custom validation rule
 * Check if an admin is trying to remove his own admin role.
 *
 * @param array $check with 'role_id' key set, user role
 * @return bool
 * @access public
 */
	public function checkCantRemoveOwnAdminRole($check) {
		if (!isset($check['role_id']) || empty($check['role_id'])) {
			return false;
		}
		$role = $this->Role->findById($check['role_id']);
		$userId = null;

		// check if a user record is available as previously set data
		// or if user id is set as per active record pattern
		if (isset($this->data['User']['id']) && !empty($this->data['User']['id'])) {
			$userId = $this->data['User']['id'];
		} elseif (isset($this->id) && !empty($this->id)) {
			$userId = $this->id;
		}

		// if user id is not set then it means we are not updating a record
		if ($userId == null) {
			return true;
		}

		// if current user is different from updated user, we are good.
		$currentUser = User::get('id');
		if ($currentUser != $userId) {
			return true;
		}

		// If current admin user want to modify his role to non admin, it's a no!
		if (User::get('Role.name') == Role::ADMIN && $role['Role']['name'] != User::get('Role.name')) {
			return false;
		}

		return true;
	}

/**
 * AfterFind callback.
 *
 * @param mixed $results The results of the find operation
 * @param bool $primary Whether this model is being queried directly (vs. being queried as an association)
 * @return mixed Result of the find operation
 * @link http://book.cakephp.org/2.0/en/models/callback-methods.html#afterfind
 */
	public function afterFind($results, $primary = false) {
		foreach ($results as $k => $res) {
			if (isset($res['LastLoggedIn'])) {
				if (isset($res['LastLoggedIn'][0]) && isset($res['LastLoggedIn'][0]['created'])) {
					$results[$k]['User']['last_logged_in'] = $res['LastLoggedIn'][0]['created'];
				} else {
					$results[$k]['User']['last_logged_in'] = null;
				}
				unset($results[$k]['LastLoggedIn']);
			}
		}
		return $results;
	}

/**
 * Before Save callback
 *
 * @param array $options passed from Model::save().
 * @return bool, if true proceed with save
 * @access public
 *
 * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
 */
	public function beforeSave($options = null) {
		// Are we in an insert or update scenario.
		$insert = !$this->id && empty($this->data[$this->alias][$this->primaryKey]);

		// If debug mode is activated, set the user id in a predictive manner.
		if (!isset($this->data['User']['id']) && isset($this->data['User']['username']) && Configure::read('debug') > 0) {
			$this->data['User']['id'] = Common::uuid($this->data['User']['username']);
		}

		// Set a default role in case it is not provided.
		if ($insert && !isset($this->data['User']['role_id']) || empty($this->data['User']['role_id'])) {
			$this->data['User']['role_id'] = $this->Role->field('id', ['name' => Role::USER]);
		}

		return true;
	}

/**
 * Get the current user
 *
 * @param string $path optional path to the field, example 'Role.id' or 'Profile.firstname'
 * @return array the current user or an anonymous user, false if error
 * @access public
 */
	public static function get($path = null) {
		Common::getModel('Role');

		// Get the user from the session
		$u = AuthComponent::user();

		// otherwise use a anonymous / guest one
		if ($u == null) {
			$u = User::setActive(User::ANONYMOUS);
		}
		// truth is a land without path
		if (!isset($path)) {
			return $u;
		}
		// trying to find the path in u
		$path = str_replace('.', '/', $path);
		if (strpos($path, '/') === false) {
			$path = sprintf('User/%s', $path);
		}
		// If User is a part of path, and doesn't exist in Authentication object, we remove it from path.
		// This is to match new CakePHP auth format, and to avoid side effects with existing passbolt code.
		if (preg_match('/^User\//', $path) && !isset($u['User'])) {
			// We remove User from path.
			$path = str_replace('User/', '', $path);
		}
		$path = '/' . $path;
		$value = Set::extract($path, $u);
		if (!$value) {
			return false;
		}

		return $value[0];
	}

/**
 * Set the user as current
 * It always perform a search on id to avoid abuse (such as using a crafted/fake user)
 *
 * @param mixed $user UUID, User::ANONYMOUS, or user array with id specified
 * @param bool $updateSession should the session be restarted? Default true
 * @return array the desired user or an ANONYMOUS user, false if error in find
 * @access public
 */
	public static function setActive($user = null, $updateSession = true) {
		// Instantiate the mode as we are in a static/singleton context
		$_this = Common::getModel('User');
		$u = [];

		// If user is unspecified or ANONYMOUS is requested
		if ($user == null || $user == User::ANONYMOUS) {
			$u = $_this->find('first', User::getFindOptions('User::activation', Role::GUEST));
			// If user is anonymous, reset the session so it is not logged in anymore.
			self::setInactive();
		} else {
			// if the user is specified and have a valid ID find it
			if (is_string($user) && Common::isUuid($user)) {
				$user = ['User' => ['id' => $user]];
			}
			$u = $_this->find('first', User::getFindOptions('User::activation', Role::USER, $user));

			// Store current user data in session or config if CLI
			if ($updateSession) {
				App::import('Model', 'CakeSession');
				CakeSession::write(AuthComponent::$sessionKey, $u);
			}
		}

		if (empty($u)) {
			return false;
		}

		return $u;
	}

/**
 * Make the current user inactive
 *
 * @return void
 * @access public
 */
	public static function setInactive() {
		// Delete current user data in session
		App::import('Model', 'CakeSession');
		if (CakeSession::check(AuthComponent::$sessionKey)) {
			CakeSession::delete(AuthComponent::$sessionKey);
			CakeSession::delete('Auth.redirect');
		}
	}

/**
 * Check if user is an admin (use role)
 *
 * @return bool true if role is admin
 * @access public
 */
	public static function isAdmin() {
		Common::getModel('Role');
		$user = User::get();

		return isset($user['Role']['name']) && $user['Role']['name'] == Role::ADMIN;
	}

/**
 * Check if user has anonymous role
 *
 * @return bool true if role is anonymous
 * @access public
 */
	public static function isAnonymous() {
		$user = User::get();
		$return = isset($user['User']['username']) && $user['User']['username'] == User::ANONYMOUS;

		return $return;
	}

/**
 * Check if user is a guest - Shortcut Method
 *
 * @return bool true if role is guest
 * @access public
 */
	public static function isGuest() {
		Common::getModel('Role');
		$user = User::get();

		return isset($user['Role']['name']) && $user['Role']['name'] == Role::GUEST;
	}

/**
 * Check if user is a root - Shortcut Method
 *
 * @return bool true if role is root
 * @access public
 */
	public static function isRoot() {
		Common::getModel('Role');
		$user = User::get();

		return isset($user['Role']['name']) && $user['Role']['name'] == Role::ROOT;
	}

/**
 * Return the find conditions to be used for a given context.
 *
 * @param null|string $case The target case.
 * @param null|string $role The user role.
 * @param null|array $data (optional) Optional data to build the find conditions.
 * @return array
 *
 * @throws Exception
 */
	public static function getFindConditions($case = null, $role = Role::GUEST, &$data = null) {
		$conditions = [];

		switch ($role) {
			case Role::GUEST:
				switch ($case) {
					case 'User::view':
						$conditions = [
							'conditions' => [
								'User.active' => true,
								'User.deleted' => false
							]
						];
						if (isset($data['User.id'])) {
							$conditions['conditions']['User.id'] = $data['User.id'];
						}
						break;

					case 'Setup::userInfo':
						$conditions = [
							'conditions' => [
								'User.active' => false,
								'User.deleted' => false,
								'User.id' => $data['User.id'],
							]
						];
						break;

					case 'Recovery::userInfo':
						$conditions = [
							'conditions' => [
								'User.active' => true,
								'User.deleted' => false,
								'User.id' => $data['User.id'],
							]
						];
						break;

					case 'User::activation':
						$conditions = [
							'conditions' => [
								'User.username' => User::ANONYMOUS,
								'User.active' => true,
								'Role.name' => Role::GUEST,
							]
						];
						break;
					default:
						throw new Exception('User::getFindCondition does not exist for role:' . $role . ' and case:' . $case);
				}
				break;

			case Role::USER:
			case Role::ADMIN:
			case Role::ROOT:
				switch ($case) {
					case 'User::activation':
						$conditions = [
							'conditions' => [
								'User.id' => $data['User']['id'],
								'Role.name' => [Role::USER, Role::ADMIN, Role::ROOT],
							]
						];
						break;

					case 'User::GpgAuth':
						$conditions = [
							'conditions' => [
								'Gpgkey.fingerprint' => $data['Gpgkey']['fingerprint'],
								'User.active' => true,
								'User.deleted' => false,
							]
						];
						break;

					case 'Resource::users':
						$conditions = [
							'conditions' => [
								'User.active' => true,
								'User.deleted' => false
							]
						];
						$conditions['conditions']['User.id'] = $data['User.ids'];
						break;

					case 'User::view':
						$conditions = [
							'conditions' => [
								'User.deleted' => false
							]
						];
						// if user is simple user, we do not allow him to see non active users.
						if ($role == Role::USER) {
							$conditions['conditions']['User.active'] = 1;
						}
						if (isset($data['User.id'])) {
							$conditions['conditions']['User.id'] = $data['User.id'];
						}
						if (isset($data['User.active'])) {
							$conditions['conditions']['User.active'] = $data['User.active'];
						}
						break;

					case 'User::index':
						$conditions = [
							'conditions' => [
								'User.deleted' => false,
								'Role.name' => [Role::USER, Role::ADMIN],
							]
						];
						// if user is admin, is-active filter is enabled
						if ($role == Role::ADMIN) {
							if (isset($data['filter']['is-active'])) {
								$conditions['conditions']['User.active'] = $data['filter']['is-active'] ? 1 : 0;
							}
						}
						// if user is simple user, we do not allow him to see non active users.
						if ($role == Role::USER) {
							$conditions['conditions']['User.active'] = 1;
						}
						// If filter on keywords.
						if (isset($data['filter']['keywords'][0])) {
							$keywords = explode(' ', trim($data['filter']['keywords'][0]));
							foreach ($keywords as $keyword) {
								$conditions['conditions']['AND'][]['OR'] = [
									'User.username LIKE' => "%$keyword%",
									'Profile.first_name LIKE' => "%$keyword%",
									'Profile.last_name LIKE' => "%$keyword%",
								];
							}
						}
						if (isset($data['filter']['has-groups'])) {
							$GroupUser = Common::getModel('GroupUser');
							$usersIds = $GroupUser->findUsersIdsMemberOfGroups($data['filter']['has-groups']);
							$conditions['conditions']['User.id'] = $usersIds;
							if (!isset($data['contain']) || !in_array('group', $data['contain'])) $data['contain'][] = 'group';
						}
						if (!empty($data['exclude-users'])) {
							$conditions['conditions']['User.id NOT IN'] = $data['exclude-users'];
						}
						break;

					case 'Share::searchUsers':
						// Use conditions already defined for the index case
						$conditions = User::getFindConditions('User::index', $role, $data);

						// By default only active users are returned.
						$conditions['conditions']['User.active'] = 1;
						break;

					default:
						throw new Exception('User::getFindCondition does not exist for role:' . $role . ' and case:' . $case);
				}
				break;

			default:
				throw new Exception('User::getFindCondition does not exist for role:' . $role);
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
	public static function getFindFields($case = null, $role = null, $data = null) {
		$fields = ['fields' => []];

		switch ($case) {
			case 'User::view':
			case 'User::index':
			case 'Setup::userInfo':
			case 'Recovery::userInfo':
				$fields = [
					'fields' => [
						'User.id',
						'User.username',
						'User.role_id',
						'User.created',
						'User.modified',
						'User.created_by',
						'User.modified_by'
					],
					'contain' => [
						'Role' => [
							'fields' => [
								'Role.id',
								'Role.name'
							]
						],
						'Profile' => [
							'fields' => [
								'Profile.id',
								'Profile.first_name',
								'Profile.last_name',
								'Profile.created',
								'Profile.modified'
							],
							'Avatar' => [
								'fields' => [
									'Avatar.id',
									'Avatar.user_id',
									'Avatar.foreign_key',
									'Avatar.model',
									'Avatar.filename',
									'Avatar.filesize',
									'Avatar.mime_type',
									'Avatar.extension',
									'Avatar.hash',
									'Avatar.path',
									'Avatar.adapter',
									'Avatar.created',
									'Avatar.modified'
								]
							]
						],
						'Gpgkey' => [
							'fields' => [
								'Gpgkey.uid',
								'Gpgkey.bits',
								'Gpgkey.fingerprint',
								'Gpgkey.key_id',
								'Gpgkey.key_created',
								'Gpgkey.expires',
								'Gpgkey.type',
								'Gpgkey.key',
							],
						],
						'GroupUser' => [
							'fields' => [
								'GroupUser.group_id',
								'GroupUser.user_id',
							],
						],
						'LastLoggedIn' => [
							'fields' => [
								'LastLoggedIn.created'
							]
						]
					]
				];
				// Add active status for admin and root roles.
				if ($role !== null && ($role == Role::ADMIN || $role == Role::ROOT)) {
					$fields['fields'][] = 'User.active';
				}
				break;
			case 'User::GpgAuth':
				$fields = [
					'fields' => [
						'User.id',
						'User.username',
					],
					'contain' => [
						'Role' => [
							'fields' => [
								'Role.id',
								'Role.name',
							]
						],
						'Gpgkey' => [
							'fields' => [
								'Gpgkey.uid',
								'Gpgkey.bits',
								'Gpgkey.fingerprint',
								'Gpgkey.key_id',
								'Gpgkey.key_created',
								'Gpgkey.expires',
								'Gpgkey.type',
								'Gpgkey.key',
							],
						],
					]
				];
				break;
			case 'User::activation':
				$fields = [
					'fields' => [
						'User.id',
						'User.username',
					],
					'contain' => [
						'Role' => [
							'fields' => [
								'Role.id',
								'Role.name',
							]
						]
					]
				];
				break;
			case 'User::validateAccount':
				$fields = [
					'fields' => [
						'Profile' => [
							'first_name',
							'last_name',
						],
						'Gpgkey' => [
							'key',
							'bits',
							'uid',
							'type',
							'key_created',
							'fingerprint',
							'key_id'
						],
					],
				];
				break;
			case 'User::save':
				$fields = [
					'fields' => [
						'username',
						'role_id',
						'active',
						'created_by',
						'modified_by'
					]
				];
				// if we are in debug mode, we also allow a predictive id to be inserted.
				if (Configure::read('debug') > 0) {
					$fields['fields'][] = 'id';
				}
				break;
			case 'User::edit':
				$fields = [
					'fields' => [
						'User' => [
							'role_id',
							'active',
							'modified_by'
						],
						'Profile' => [
							'first_name',
							'last_name',
						]
					]
				];
				break;
			case 'User::softDelete':
				$fields = [
					'fields' => [
						'deleted',
						'modified_by'
					]
				];
				break;
			case 'Group::index':
			case 'Group::view':
				$fields = [
					'fields' => [
						'User.id',
						'User.username',
						'User.role_id',
						'User.created',
						'User.modified',
						'User.created_by',
						'User.modified_by',
					],
				];
				break;
			case 'Share::searchUsers':
				$fields = [
					'fields' => [
						'User.id',
						'User.username',
						'User.role_id',
						'User.created',
						'User.modified',
						'User.created_by',
						'User.modified_by',
					],
					'contain' => [
						'Role' => [
							'fields' => [
								'Role.id',
								'Role.name',
							]
						],
						'Profile' => [
							'fields' => [
								'Profile.id',
								'Profile.first_name',
								'Profile.last_name',
								'Profile.created',
								'Profile.modified'
							],
							'Avatar' => [
								'fields' => [
									'Avatar.id',
									'Avatar.user_id',
									'Avatar.foreign_key',
									'Avatar.model',
									'Avatar.filename',
									'Avatar.filesize',
									'Avatar.mime_type',
									'Avatar.extension',
									'Avatar.hash',
									'Avatar.path',
									'Avatar.adapter',
									'Avatar.created',
									'Avatar.modified'
								]
							]
						],
						'Gpgkey' => [
							'fields' => [
								'Gpgkey.key_id',
							],
						],
					]
				];
		}

		return $fields;
	}

/**
 * Return the list of order instructions allowed for each case, with their default value
 *
 * @param null $case
 * @param null $role
 * @return array
 */
	public static function getFindAllowedOrder($case = null, $role = null) {
		return [
			'User.username',
			'User.created',
			'User.modified',
			'Profile.first_name',
			'Profile.last_name',
			'Profile.created',
			'Profile.modified'
		];
	}

/**
 * Add a user and its profile, and create an authentication token.
 *
 * @param array $data user and profile data
 * @throws Exception if there was a problem when saving
 * @throws ValidationException if the provided user data do not validate
 * @return array The user, the profile and the token data
 */
	public function registerUser($data) {
		// No user data provided
		if (!isset($data['User']) || empty($data['User'])) {
			throw new Exception(__('User data are missing'));
		}

		// No profile data provided
		if (!isset($data['Profile']) || empty($data['Profile'])) {
			throw new Exception(__('Profile data are missing'));
		}

		// If role id is not provided, we assign a default one
		if (!isset($data['User']['role_id']) || empty($data['User']['role_id'])) {
			$data['User']['role_id'] = $this->Role->field('Role.id', ['name' => Role::USER]);
		}

		// By default a user is not active
		$data['User']['active'] = 0;

		// Begin transaction
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		// Get the fields used for this operation validation
		$findCase = "User::save";
		$fields = $this->getFindFields($findCase, User::get('Role.name'));

		// Set the data for validation and save
		$this->create();
		$this->set($data);

		// Validate the user data with the right fields
		if (!$this->validates(['fieldList' => [$fields['fields']]])) {
			$dataSource->rollback();
			throw new ValidationException(__('Could not validate user data'), ['User' => $this->validationErrors]);
		}

		// Save the user
		$saveUser = $this->save($data, false, $fields['fields']);
		if (!$saveUser) {
			$dataSource->rollback();
			throw new Exception(__('The user could not be saved'));
		}

		// Now the profile
		// Get the meaningful fields for this operation
		$fields = $this->Profile->getFindFields($findCase, User::get('Role.name'));

		// Set the data for validation and save
		$data['Profile']['user_id'] = $saveUser['User']['id'];
		$this->Profile->set($data);

		// Validate the profile data
		if (!$this->Profile->validates(['fieldList' => [$fields['fields']]])) {
			$dataSource->rollback();
			throw new ValidationException(__('Could not validate profile'), ['Profile' => $this->Profile->validationErrors]);
		}

		// Save the profile
		$saveProfile = $this->Profile->save($data['Profile'], false, $fields['fields']);
		if (!$saveProfile) {
			$dataSource->rollback();
			throw new Exception(__('The profile could not be saved'));
		}

		// Create the setup authentication token
		$saveToken = $this->AuthenticationToken->generate($saveUser['User']['id']);
		if (!$saveToken) {
			$dataSource->rollback();
			throw new Exception(__('The account token could not be created'));
		}

		// Everything fine, we commit.
		$dataSource->commit();

		// Build result.
		$res = array_merge($saveUser, $saveProfile, $saveToken);

		// Dispatch event.
		$event = new CakeEvent('Model.User.afterRegister', $this, ['data' => $res]);
		$this->getEventManager()->dispatch($event);

		return $res;
	}

/**
 * Soft delete a user.
 *
 * @param string $userId Id of the user to soft delete
 * @throws Exception if there is an issue during the save operation
 * @return void
 */
	public function softDelete($userId) {
		$Permission = ClassRegistry::init('Permission');

		// Begin transaction
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		// Mark the user as deleted
		$data['User'] = [
			'id' => $userId,
			'deleted' => 1
		];
		$fields = $this->getFindFields('User::softDelete', User::get('Role.name'));
		if (!$this->save($data, true, $fields['fields'])) {
			$dataSource->rollback();
			throw new Exception(__('Unable to soft delete the user'));
		}

		// Revoke the user's permissions
		$deleteOptions = ['Permission.aro_foreign_key' => $userId];
		if (!$Permission->deleteAll($deleteOptions, false)) {
			$dataSource->rollback();
			throw new Exception(__('Unable to delete de user\'s permissions'));
		}

		// Everything fine, we commit.
		$dataSource->commit();
	}
}
