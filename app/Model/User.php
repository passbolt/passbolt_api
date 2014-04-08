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
App::uses('BcryptFormAuthenticate', 'Controller/Component/Auth');
App::uses('Common', 'Controller/Component');
App::uses('Role', 'Model');

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
	 * @access public
	 */
	public $actsAs = array('Containable', 'Trackable');

	/**
	 * Details of belongs to relationships
	 *
	 * @var array
	 * @link http://book.cakephp.org/2.0/en/models/associations-linking-models-together.html#
	 */
	public $belongsTo = array('Role');

  /**
   * Details of the hasOne relationships
   * @var array
   */
  public $hasOne = array(
    'Profile'
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
					'required'   => true,
					'allowEmpty' => false,
					'rule'       => array('notEmpty'),
					'message'    => __('A username is required')
				),
				'email'    => array(
					'rule'    => array('email'),
					'message' => __('The username should be a valid email address')
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
				'minLength' => array(
					'rule'    => array('minLength', 5),
					'message' => __('Your password should be at least composed of 5 characters')
				)
			)
		);
		switch ($case) {
			default:
			case 'default' :
				$rules = $default;
		}

		return $rules;
	}

	/**
	 * Before Save callback
	 *
	 * @link   http://api20.cakephp.org/class/app-model#method-AppModel__construct
	 * @return bool, if true proceed with save
	 * @access public
	 */
	public function beforeSave($options=null) {
		// encrypt the password
		// @todo use bcrypt instead #PASSBOLT-157
		if (isset($this->data['User']['password'])) {
			//$this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
			$this->data['User']['password'] = BcryptFormAuthenticate::hash($this->data['User']['password']);
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
	 * Check if user is admin role
	 *
	 * @return bool true if role is admin
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
						break;

					case 'User::index':
						$conditions = array(
							'conditions' => array(
								'User.active' => true,
								'User.deleted' => false
							)
						);
						if (isset($data['keywords'])) {
							$keywords = explode(' ', $data['keywords']);
							foreach ($keywords as $keyword) {
								$conditions['conditions']["AND"][] = array('User.username LIKE' => '%' . $keyword . '%');
							}
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
					'fields'  => array(
						'User.id',
						'User.username',
						'User.role_id',
					),
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
								'Profile.last_name'
							)
						)
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
								'Role.name'
							)
						)
					)
				);
				break;
			case 'User::save':
				$fields = array(
					'fields' => array(
						'username',
						'role_id',
						'password',
						'active'
					)
				);
				break;
			case 'User::edit':
				$fields = array(
					'fields' => array(
						'username',
						'role_id',
						'password',
						'active'
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
