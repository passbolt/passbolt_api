<?php
/**
 * User Model Test
 *
 * @copyright     Copyright 2012, Passbolt.com
 * @package       app.Test.Case.Model.UserTest
 * @since         version 2.12.7
 * @license       http://www.passbolt.com/license
 */
App::uses('User', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) { 
  require CAKE . 'Model/Datasource/CakeSession.php'; 
}

class UserTestCase extends CakeTestCase {
  public $fixtures = array('app.user'); //, 'app.role');
  public $autoFixtures = true;

  /**
   * Setup
   * @return void
   */
  public function setup() {
    parent::setUp();
    $this->User = ClassRegistry::init('User');
  }

  /**
   * Test UserName Validation
   * @return void
   */
  public function testUsernameValidation() {
    $testcases = array(
      '' => false, '?!#' => false, 'test' => false,
      'test@test.com' => true, 'admin' => false
    );
    foreach($testcases as $testcase => $result) {      
      $user = array('User' => array('username' => $testcase));
      $this->User->set($user);
      if($result) $msg = 'validation of the username with '.$testcase.' should validate';
      else $msg = 'validation of the username with '.$testcase.' should not validate';
      $this->assertEqual($this->User->validates(array('fieldList' => array('username'))), $result, $msg);
    }
  }

  /**
   * Test Password Validation
   * @return void
   */
  public function testPasswordValidation() {
    $testcases = array(
      '' => false, '?!#' => false, 'testss' => true,
      't2stss' => true
    );
    foreach($testcases as $testcase => $result) {      
      $user = array('User' => array('password' => $testcase, 'password_confirm' => $testcase));
      $this->User->set($user);
      if($result) $msg = 'validation of user password with '.$testcase.' should validate';
      else $msg = 'validation of user password with '.$testcase.' should not validate';
      $this->assertEqual($this->User->validates(array('fieldList' => array('password'))), $result, $msg);
    }
  }

  /**
   * Test Password Encryption
   * @return void
   */
  public function testBeforeSave() {
    $user = array('User' => array('password' => 'test1'));
    $this->User->set($user);
    $this->assertEqual($this->User->beforeSave(), true, 'Before save should return true');
    $this->assertNotEqual($this->User->data['User']['password'], $user['User']['password'], 'Before save should return true');
  }

  /**
   * Test UserGet
   * @return void
   */
  public function testGet() {
    // Make sure there is no active sessions
    App::import('Model', 'CakeSession'); 
    $Session = new CakeSession();
    $Session->delete(AuthComponent::$sessionKey);

    // Get an anonymous user
    $user = User::get();
    $this->assertEqual(is_array($user), true, 'User::get should return an array');
    $this->assertEqual(!isset($user['User']['password']), true, 'User::get should never return a password');
    $this->assertEqual($user['User']['username'], User::Anonymous, 'User::get should return anonymous');
    $this->assertEqual(isset($user['Role']), true, 'User::get should return role');
    $this->assertEqual($user['Role']['name'], Role::Guest, 'User::get should return guest role');
    $this->assertEqual($user, $Session->read(AuthComponent::$sessionKey), 'User::get should set user in session');

  }

  public function testSetActive() {
    // Try to get a user that doesn't exist
    $user = User::setActive(String::UUID());
    $this->assertEqual($user, false, 'User::setActive should return false');
  }

  public function testIsGuest() {    
    // Make sure there is no active sessions
    App::import('Model', 'CakeSession'); 
    $Session = new CakeSession();
    $Session->delete(AuthComponent::$sessionKey);

    // Get an anonymous user
    $this->assertEqual(User::isGuest(), true, 'User::isGuest should return true');

    // Get admin user
    $param = array(
      'conditions' => array('username' => 'Admin')
    );
    $user = $this->User->find('first',$param);
    $this->User->setActive($user);
    $this->assertEqual(User::isGuest(), false, 'User::isGuest should return true');
    
  }

  public function testIsAdmin() {    
    // Make sure there is no active sessions
    App::import('Model', 'CakeSession'); 
    $Session = new CakeSession();
    $Session->delete(AuthComponent::$sessionKey);

    // Get an anonymous user
    $this->assertEqual(User::isAdmin(), false, 'User::isAdmin should return false');

    // Get admin user
    $param = array(
      'conditions' => array('username' => 'Admin')
    );
    $user = $this->User->find('first',$param);
    $this->User->setActive($user);
    $this->assertEqual(User::isAdmin(), true, 'User::isGuest should return true');
  }

  public function testGetFindConditions() {
    try {
      $this->User->getFindConditions('testoqwidoqdhwqohdowqhid');
      $this->assertEqual(false, true, 'testGetFindCondition should throw an exception');
    } catch(Exception $e) {
    }
  }

  public function testGetFindFields() {
    try {
      $this->User->getFindFields('testdqwodjqodqodwjqidqjdow');
      $this->assertEqual(false, true, 'testGetFindFields should throw an exception');
    } catch(Exception $e) {
    }
  }
}
