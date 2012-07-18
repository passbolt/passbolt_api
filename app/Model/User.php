<?php
/**
 * User Model
 *
 * Copyright 2012, Passbolt
 * Passbolt(tm), the simple password management solution 
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Model.user
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
  public $name = 'User';

  /**
   * Constructor
   * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
   */
  public function __construct($id = false, $table = null, $ds = null) {
    parent::__construct($id, $table, $ds);
    $this->setValidationRules();
  }

  /**
   * Set the validation rules upon context
   * @param string context
   */
  function setValidationRules($context='default') {
    $this->validate = User::getValidationRules($context);
  }

  /**
   * Get the validation rules upon context
   * @param string context
   */
  static function getValidationRules($context='default') {
    $default = array(
      'username' => array(
        'required' => array(
          'required' => true,
          'allowEmpty' => false,
          'rule' => array('notEmpty'),
          'message' => __('A username is required')
        ),
        'email' => array(
          'rule' => array('email'),
          'message' => __('The username should be a valid email address')
        )
      ),
      'password' => array(
        'required' => array(
          'required' => true,
          'allowEmpty' => false,
          'rule' => array('notEmpty'),
          'message' => __('A password is required')
        ),
        'minLength' => array(
          'rule' => array('minLength',5),
          'message' => __('Your password should be at least composed of 5 characters')
        ),
        'confirmed' => array(
          'rule' => array('isConfirmed','User.password_confirm'),
          'message' => __('Your passwords should match')
        )
      ),
      'role' => array(
        'inlist' => array(
          'required' => true,
          'allowEmpty' => false,
          'rule' => array('inList', array('admin')),
          'message' => __('Please enter a valid role')
        )
      )
    );
    switch ($context) {
      case 'forgot_password' : 
        $rules['username'] = $default['username'];
      break;
      default:
      case 'resetPassword':
        $rules['password'] = $default['password'];
      break;
      case 'default' :
        $rules = $default; 
      break;
    }
    return $rules;
  }

  /**
   * Before Save callback
   * @link http://api20.cakephp.org/class/app-model#method-AppModel__construct
   * @return bool, if true proceed with save
   */
  public function beforeSave() {
    // encrypt the password
    // @todo use bcrypt instead of md5 hashing
    if (isset($this->data[$this->alias]['password'])) {
      $this->data[$this->alias]['password'] = AuthComponent::password($this->data[$this->alias]['password']);
    }
    return true;
  }

  /**
   * Set the user as current
   */
  public static function get() {
    $user = AuthComponent::user();
    // if the user is not in Session use a guest
    if($user == null) {
      $user = User::setActive(User::guest);
    }
    return $user;
  }

  /**
   * Set the user as current
   * @param array $user
   * @param bool $updateSession
   * @param bool $generateAuthCookie
   */
  static function setActive($user = null, $find=true) {
    $_this = Common::getModel('User');
    //@TODO only fetch if $user is incomplete compared to find conditions
    if($find) {
      if ($user != User::guest && isset($user['User']['id']) && Common::isUuid($user['User']['id'])) {
        $user = $_this->find('first',
          User::getFindOptions('userActivation',$user)
        );
      }
      if (is_null($user) || empty($user) || $user == User::guest) {
        $user = $_this->find('first',
          User::getFindOptions('guestActivation')
        );
      }
    }
    // just to make sure no password is around
    if (isset($user['User']['password'])) {
      unset($user['User']['password']);
    }
    // Store current user data in session
    App::import('Model', 'CakeSession'); 
    $Session = new CakeSession(); 
	  $Session->renew();
		$Session->write(AuthComponent::$sessionKey, $user);

    return $user;
  }

  public static function isAdmin() {
    $user = User::get();
    if (isset($user['role'])) {
      return $user['role'] == User::admin;
    } 
    return false;
  }

  public static function isGuest() {
    $user = User::get();
    if (isset($user['role'])) {
      return $user['role'] == User::guest;
    }
    return false;
  }

  /**
   * Return the find options to be used
   * @param string context
   * @return array
   */
  static function getFindOptions($case,&$data = null) {
    return array_merge(
      User::getFindConditions($case,&$data),
      User::getFindFields($case)
    );
  }

  /**
   * Return the conditions to be used for a given context
   * for example if you want to activate a User session
   * @param $context string{guest or id}
   * @param $data used in find conditions (such as User.id)
   * @return $condition array
   */
  static function getFindConditions($case='guestActivation',&$data=null) {
    switch($case) {
      case 'login':
        $conditions = array(
         'conditions' => array(
           'User.password' => $data['User']['password'],
           'User.username' => $data['User']['username'],
         )
       );
      break;
      case 'forgotPassword':
        $conditions = array(
         'conditions' => array(
           'User.username' => $data['User']['username'],
         )
        );
      break;
      case 'resetPassword':
      case 'userActivation':
        $conditions = array(
          'conditions' => array(
            'User.id' => $data['User']['id'],
            //'User.active' => 1,
          )
        );
      break;
      case 'guestActivation': default:
        $conditions = array(
          'conditions' => array(
            'User.username' => 'guest',
          )
        );
      break;
      default:
        throw new exception('ERROR: User::GetFindConditions case undefined');
        //$fields = array();
      break;
    }
    return $conditions;
  }

  /**
   * Return the list of field to fetch for given context
   * @param string $case context ex: login, activation
   * @return $condition array
   */
  static function getFindFields($case="guestActivation"){
    switch($case){
      case 'resetPassword':
      case 'forgotPassword':
      case 'guestActivation':
        $fields = array(
          'fields' => array(
            'User.id', 'User.username', 'User.role'
            //, 'User.active'
          )
        );
      break;
      case 'login':
      case 'userActivation':
        $fields = array(
          'contain' => array(
            'Role(id,name)',
            //'Timezone(id,name)',
            //'Language(id,name,ISO_639-2-alpha2,ISO_639-2-alpha1)',
            //'Settings(*)',
            //'Person(id,firstname,lastname)'
            //'Office(name,acronym,region,type)',
          ),
          'fields' => array(
            'User.id', 'User.username'
            //'User.active','User.permissions',
          )
        );
      break;
      default:
        throw new exception('ERROR: User::GetFindFields case undefined');
      break;
    }
    return $fields;
  }

}
