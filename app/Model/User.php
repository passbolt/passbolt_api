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
	public $actsAs = array(
		'SuperJoin',
		'Containable',
		'Trackable'
	);

/**
 * Details of belongs to relationships
 *
 * @var array
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $belongsTo = array(
		'Role'
	);

/**
 * Details of the hasOne relationships
 * @var array
*/
	public $hasOne = array(
		'Profile',
		'Gpgkey',
		'AuthenticationToken',
	);

/**
 * Details of has many relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasMany = array('GroupUser', 'Secret');

/**
 * Details of has and belongs to many relationships
 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
 */
	public $hasAndBelongsToMany = array(
		'Group' => array(
			'className' => 'Group'
		)
	);

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
		$default = array(
			'username' => array(
				'required' => array(
					'required'   => 'create',
					'allowEmpty' => false,
					'rule'       => array('notEmpty'),
					'message'    => __('A username is required')
				),
				'email'    => array(
					'rule'    => array('email'),
					'message' => __('The username should be a valid email address')
				),
				'login' => array(
					'rule' => 'isUnique',
					'on' => 'create',
					'shared' => FALSE,
					'message' => __('The username has already been taken')
				)
			),
			'password' => array(
				'required'  => array(
					'required'   => true,
					'allowEmpty' => false,
					'on'         => 'create',
					'rule'       => array('notEmpty'),
					'message'    => __('A password is required'),
				),
				'size' => array(
					'rule' => array('between', 8, 20),
					'message' => __('Password should be between %s and %s characters long'),
				)
			),
			'current_password' => array(
				'validPassword' => array(
					'rule' => array('validPassword', true),
					'shared' => false,
					'required' => false,
					'message' => __('Password provided is not valid'),
				)
			),
		);
		switch ($case) {
			case 'editPassword':
				$rules = array(
					'password' => $default['password'],
				);
				break;

			default:
			case 'default' :
				$rules = $default;
		}

		return $rules;
	}


	/**
	 * Check if the password is valid
	 * @param $check
	 * @return bool
	 */
	public function validPassword($check) {
		if ($check['current_password'] == null) {
			return false;
		} else {
			$userId = $this->id;
			if (!$userId) {
				return false;
			}
			$currentUserPass = $this->field('password', array('id' => $userId));
			$hashedPass = Security::hash($check['current_password'], 'blowfish', $currentUserPass);
			// Check that current password is valid.
			$valid = $currentUserPass == $hashedPass;
			return $valid;
		}
	}

/**
 * Before Save callback
 *
 * @link   http://api20.cakephp.org/class/app-model#method-AppModel__construct
 * @return bool, if true proceed with save
 * @access public
 */
	public function beforeSave($options=null) {
		// Encrypt the password.
		if (isset($this->data['User']['password'])) {
			$this->data['User']['password'] = Security::hash($this->data['User']['password'], Configure::read('HashType'), false);
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
		if (preg_match('/^User\//', $path)  && !isset($u['User'])) {
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
		$u = array();

		// If user is unspecified or ANONYMOUS is requested
		if ($user == null || $user == User::ANONYMOUS) {
			$u = $_this->find('first', User::getFindOptions(User::ANONYMOUS));
		} else {
			// if the user is specified and have a valid ID find it
			if (is_string($user) && Common::isUuid($user)) {
				$user = array('User' => array('id' => $user));
			}
			$u = $_this->find('first', User::getFindOptions('User::activation', Role::USER, $user));
		}

		if (empty($u)) {
			return false;
		}

		// Store current user data in session
		App::import('Model', 'CakeSession');
		$Session = new CakeSession();
		$Session->renew();
		$Session->write(AuthComponent::$sessionKey, $u);

		return $u;
	}

/**
 * Make the current user inactive
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
 */
	public static function getFindConditions($case = User::ANONYMOUS, $role = Role::GUEST, $data = null) {
		$conditions = array();

		switch ($role) {
			case Role::GUEST:
				switch ($case) {
					case 'User::view':
						$conditions = array(
							'conditions' => array(
								'User.active' => true,
								'User.deleted' => false
							)
						);
						if (isset($data['User.id'])) {
							$conditions['conditions']['User.id'] = $data['User.id'];
						}
						break;
					case 'Setup::userInfo':
						$conditions = array(
							'conditions' => array(
								'User.active' => false,
								'User.deleted' => false,
								'User.id' => $data['User.id'],
							)
						);
						break;
				}
				break;

			case Role::USER:
			case Role::ADMIN:
				switch ($case) {
					case 'User::activation':
						$conditions = array(
							'conditions' => array(
								'User.id' => $data['User']['id']
							)
						);
						break;

					case User::ANONYMOUS:
					default:
						$conditions = array(
							'conditions' => array(
								'User.username' => User::ANONYMOUS,
								'User.active' => true
							)
						);
						break;

					case 'User::view':
						$conditions = array(
							'conditions' => array(
								'User.active' => true,
								'User.deleted' => false
							)
						);
						if (isset($data['User.id'])) {
							$conditions['conditions']['User.id'] = $data['User.id'];
						}
						if (isset($data['User.active'])) {
							$conditions['conditions']['User.active'] = $data['User.active'];
						}
						break;

					case 'User::index':
						$conditions = array(
							'conditions' => array(
								'User.active' => true,
								'User.deleted' => false,
								'Role.name' => array(Role::USER, Role::ADMIN),
							)
						);
						// If filter on group.
						if (isset($data['foreignModels']['Group.id'])) {
							$conditions['conditions']['Group.id'] = $data['foreignModels']['Group.id'];
						}
						// If filter on keywords.
						if (isset($data['keywords'])) {
							$keywords = explode(' ', $data['keywords']);
							foreach ($keywords as $keyword) {
								$conditions['conditions']["AND"][] = array('User.username LIKE' => '%' . $keyword . '%');
							}
						}
						// Order the data.
						if (isset($data['order'])) {
							switch ($data['order']) {
								case 'modified':
									$conditions['order'] = array('User.modified DESC');
									break;
							}
						} else {
							// By default order alphabetically
							$conditions['order'] = array('Profile.last_name ASC');
						}
						break;

					default:
						$conditions = array(
							'conditions' => array()
						);
				}
				break;

			default :
				switch ($case) {
					case User::ANONYMOUS:
					default:
						$conditions = array(
							'conditions' => array(
								'User.username' => User::ANONYMOUS,
								'User.active' => true
							)
						);
						break;

				}
				break;

		}

		return $conditions;
	}

/**
 * Return the list of field to fetch for given context
 *
 * @param string $case context ex: login, activation
 *
 * @return $condition array
 * @access public
 */
	public static function getFindFields($case = User::ANONYMOUS, $role = Role::USER) {
		switch ($case) {
			case User::ANONYMOUS:
			case 'User::view':
			case 'User::index':
			default:
				$fields = array(
					'fields' => array(
						'DISTINCT User.id',
						'User.username',
						'User.role_id',
						'User.created',
						'User.modified',
					),
					'superjoin' => array('Group'),
					'contain' => array(
						'Role' => array(
							'fields' => array(
								'Role.id',
								'Role.name'
							)
						),
						'Profile' => array(
							'fields' => array(
								'Profile.id',
								'Profile.first_name',
								'Profile.last_name',
								'Profile.created',
								'Profile.modified'
							),
							'Avatar' => array(
								'fields' => array(
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
								)
							)
						),
						'Gpgkey' => array(
							'fields' => array(
								'Gpgkey.uid',
								'Gpgkey.bits',
								'Gpgkey.fingerprint',
								'Gpgkey.key_id',
								'Gpgkey.key_created',
								'Gpgkey.expires',
								'Gpgkey.type',
								'Gpgkey.key',
							),
						),
						'Group' => array(
							'fields' => array(
								'Group.id',
								'Group.name',
								'Group.created',
								'Group.modified'
							),
						),
						'GroupUser' => array(
							'fields' => array(
								'GroupUser.group_id',
								'GroupUser.user_id',
							),
						),
					)
				);
				break;
			case 'User::activation':
				$fields = array(
					'fields'  => array(
						'User.id',
						'User.username',
					),
					'contain' => array(
						'Role' => array(
							'fields' => array(
								'Role.id',
								'Role.name',
							)
						)
					)
				);
				break;
			case 'User::validateAccount':
				$fields = array(
					'fields' => array(
						'User' => array(
							'password',
						),
						'Profile' => array(
							'first_name',
							'last_name',
						),
						'Gpgkey' => array(
							'key',
						),
					),
				);
				break;
			case 'User::save':
				$fields = array(
					'fields' => array(
						'username',
						'role_id',
						'password',
						'active',
					)
				);
				break;
			case 'User::edit':
				$fields = array(
					'fields' => array(
						'User' => array(
							'role_id',
							'password',
							'active',
						),
						'Profile' => array(
							'first_name',
							'last_name',
						)
					)
				);
				break;
			case 'User::editPassword':
				$fields = array(
					'fields' => array(
						'password',
					)
				);
				break;
			case 'User::delete':
				$fields = array(
					'fields' => array(
						'deleted'
					)
				);
				break;
		}
		return $fields;
	}
}
