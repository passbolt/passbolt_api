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
class UserTestCase extends CakeTestCase {

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
    
  }

}
