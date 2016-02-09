<?php
/**
 * User Model
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @license       http://www.passbolt.com/license
 * @package       app.Model.user
 * @since         version 2.12.7
 */

App::uses('AuthComponent', 'Controller/Component');
App::uses('Common', 'Controller/Component');
App::uses('Role', 'Model');
App::uses('Security', 'Utility');

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
		'SuperJoin',
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
		'AuthenticationToken',
	];

/**
 * Details of has many relationships
 *
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = ['GroupUser', 'Secret'];

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
 * They are legions
 */
	const ANONYMOUS = 'anonymous@passbolt.com';

/**
 * Get the validation rules upon context
 *
 * @param string context
 *
 * @return array validation rules
 * @throws exception if case is undefined
 * @access public
 */
	public static function getValidationRules($case = 'default') {
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
					'rule' => 'isUnique',
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
 * Check if the role provided is a valid one.
 *
 * @param $check data provided for validation
 *
 * @return bool
 */
	public function checkValidRole($check) {
		if (!isset($check['role_id']) || empty($check['role_id'])) {
			return false;
		}
		$role = $this->Role->findById($check['role_id']);
		if (empty($role)) {
			return false;
		}
		if (!in_array($role['Role']['name'], [Role::ADMIN, Role::USER])) {
			return false;
		}

		return true;
	}

/**
 * Check if an admin is trying to remove his own admin role.
 *
 * @param $check data provided for validation
 *
 * @return bool
 */
	public function checkCantRemoveOwnAdminRole($check) {
		if (!isset($check['role_id']) || empty($check['role_id'])) {
			return false;
		}
		$role = $this->Role->findById($check['role_id']);

		$userId = null;
		// check if a user record is available.
		// @todo explain
		if (isset($this->data['User']['id']) && !empty($this->data['User']['id'])) {
			$userId = $this->data['User']['id'];
		} elseif (isset($this->id) && !empty($this->id)) {
			$userId = $this->id;
		}
		// if user id is null, it means we are not updating.
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
 * Before Save callback
 *
 * @link   http://api20.cakephp.org/class/app-model#method-AppModel__construct
 * @return bool, if true proceed with save
 * @access public
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
 * @return array the current user or an anonymous user, false if error
 *
 * @param string field
 *
 * @access public
 */
	public static function get($path = null) {
		// Get the user from the session
		Common::getModel('Role');
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
 * @param mixed UUID, User::ANONYMOUS, or user array with id specified
 *
 * @return array the desired user or an ANONYMOUS user, false if error in find
 * @access public
 */
	public static function setActive($user = null) {
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

			// Store current user data in session
			App::import('Model', 'CakeSession');
			$Session = new CakeSession();
			$Session->renew();
			$Session->write(AuthComponent::$sessionKey, $u);
		}

		if (empty($u)) {
			return false;
		}

		return $u;
	}

/**
 * Make the current user inactive
 *
 * @access public
 */
	public static function setInactive() {
		// Store current user data in session
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();

		$Session->delete(AuthComponent::$sessionKey);
		$Session->delete('Auth.redirect');
		$Session->renew();
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
	public static function getFindConditions($case = null, $role = Role::GUEST, $data = null) {
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
					case 'User::activation':
						$conditions = [
							'conditions' => [
								'User.username' => User::ANONYMOUS,
								'User.active' => true
							]
						];
						break;
					default:
						throw new Exception('User::getFindCondition does not exist for role:' . $role . ' and case:' . $case);
						break;
				}
				break;

			case Role::USER:
			case Role::ADMIN:
			case Role::ROOT:
				switch ($case) {
					case 'User::activation':
						$conditions = [
							'conditions' => [
								'User.id' => $data['User']['id']
							]
						];
						break;
					case 'User::GpgAuth':
						$conditions = [
							'conditions' => [
								'Gpgkey.fingerprint' => $data['Gpgkey']['fingerprint']
							]
						];
						break;

					case 'User::view':
						$conditions = [
							'conditions' => [
								'User.deleted' => false
							]
						];
						// if user is simple user, we do not allow him to see non active users.
						if ($role == Role::USER) {
							$conditions['conditions']['User.active'] = true;
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
						// if user is simple user, we do not allow him to see non active users.
						if ($role == Role::USER) {
							$conditions['conditions']['User.active'] = true;
						}
						// If filter on group.
						if (isset($data['foreignModels']['Group.id'])) {
							$conditions['conditions']['Group.id'] = $data['foreignModels']['Group.id'];
						}
						// If filter on keywords.
						if (isset($data['keywords'])) {
							$keywords = explode(' ', $data['keywords']);
							foreach ($keywords as $keyword) {
								$conditions['conditions']["AND"][] = ['User.username LIKE' => '%' . $keyword . '%'];
							}
						}
						// If exclude users.
						if (isset($data['excludedUsers'])) {
							$conditions['conditions']["AND"]["NOT"][] = ['User.id' => $data['excludedUsers']];
						}
						// Order the data.
						if (isset($data['order'])) {
							switch ($data['order']) {
								case 'modified':
									$conditions['order'] = ['User.modified DESC'];
									break;
							}
						} else {
							// By default order alphabetically
							$conditions['order'] = ['Profile.last_name ASC'];
						}
						break;

					case 'Share::searchUsers':
						// Use already conditions already defined for the index case
						$conditions = User::getFindConditions('User::index', $role, $data);
						// Only return users who don't have a direct permission defined for the given aco instance
						$conditions['joins'][] = [
							'table' => 'users',
							'alias' => 'UserToGrant',
							'type' => 'inner',
							'conditions' => [
								'
								User.id = UserToGrant.id
								AND UserToGrant.id NOT IN (
									SELECT Permission.aro_foreign_key
									FROM permissions Permission
									WHERE Permission.aco = "' . $data['aco'] . '"
										AND Permission.aco_foreign_key = "' . $data['aco_foreign_key'] . '"
										AND Permission.aro_foreign_key = UserToGrant.id
								)',
							],
						];
						break;

					default:
						throw new Exception('User::getFindCondition does not exist for role:' . $role . ' and case:' . $case);
						break;
				}
				break;

			default:
				throw new Exception('User::getFindCondition does not exist for role:' . $role);
				break;
		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 *
 * @param string $case context ex: login, activation
 *
 * @return empty|$condition array
 * @access public
 */
	public static function getFindFields($case = null, $role = Role::USER) {
		switch ($case) {
			case 'User::view':
			case 'User::index':
			default:
				$fields = [
					'fields' => [
						'DISTINCT User.id',
						'User.username',
						'User.role_id',
						'User.created',
						'User.modified',
					],
					'superjoin' => ['Group'],
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
						'Group' => [
							'fields' => [
								'Group.id',
								'Group.name',
								'Group.created',
								'Group.modified'
							],
						],
						'GroupUser' => [
							'fields' => [
								'GroupUser.group_id',
								'GroupUser.user_id',
							],
						],
					]
				];
				// Add active status for admin and root roles.
				if ($role == Role::ADMIN || $role == Role::ROOT) {
					$fields['fields'][] = 'User.active';
				}
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
						],
						'Profile' => [
							'first_name',
							'last_name',
						]
					]
				];
				break;
			case 'User::delete':
				$fields = [
					'fields' => [
						'deleted'
					]
				];
				break;
		}

		return $fields;
	}

/**
 * Add a user and its profile, and create an authentication token.
 *
 * @param $data The user and profile data
 * @return array The user, the profile and the token data
 *
 * @throws Exception
 * @throws ValidationException
 */
	public function registerUser($data, $case = "User::save") {
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
		$data['User']['active'] = false;

		// Begin transaction
		$dataSource = $this->getDataSource();
		$dataSource->begin();

		// Get the meaningful fields for this operation
		$fields = $this->getFindFields($case, User::get('Role.name'));
		// Set the data for validation and save
		$this->set($data);

		// Validate the user data
		if (!$this->validates(['fieldList' => [$fields['fields']]])) {
			$dataSource->rollback();
			throw new ValidationException(__('Could not validate user data'), $this->validationErrors);
		}

		// Save the user
		$saveUser = $this->save($data, false, $fields['fields']);
		if (!$saveUser) {
			$dataSource->rollback();
			throw new Exception(__('The user could not be saved'));
		}

		// Get the meaningful fields for this operation
		$fields = $this->Profile->getFindFields($case, User::get('Role.name'));
		// Set the data for validation and save
		$data['Profile']['user_id'] = $this->id;
		$this->Profile->set($data);

		// Validate the profile data
		if (!$this->Profile->validates(['fieldList' => [$fields['fields']]])) {
			$dataSource->rollback();
			throw new ValidationException(__('Could not validate profile'), $this->Profile->validationErrors);
		}

		// Save the profile
		$saveProfile = $this->Profile->save($data['Profile'], false, $fields['fields']);
		if (!$saveProfile) {
			$dataSource->rollback();
			throw new Exception(__('The profile could not be saved'));
		}

		// Create the setup authentication token
		$saveToken = $this->AuthenticationToken->generate($this->id);
		if (!$saveToken) {
			$dataSource->rollback();
			throw new Exception(__('The account token could not be created'));
		}

		// Everything fine, we commit.
		$dataSource->commit();

		return array_merge($saveUser, $saveProfile, $saveToken);
	}
}
