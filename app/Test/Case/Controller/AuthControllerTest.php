<?php
/**
 * Authentication Controller Tests
 *
 * @copyright    Copyright 2012, Passbolt.com
 * @package      app.Test.Case.Controller.AuthController
 * @since        version 2.12.7
 * @license      http://www.passbolt.com/license
 */
App::uses('AppController', 'Controller');
App::uses('CategoriesController', 'Controller');
App::uses('Category', 'Model');
App::uses('CategoryType', 'Model');
App::uses('User', 'Model');
App::uses('Role', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
    require CAKE . 'Model/Datasource/CakeSession.php';
}

class AuthControllerTest extends ControllerTestCase {

    public $fixtures = array(
        'app.groups_user',
        'app.group',
        'app.user',
        'app.gpgkey',
        'app.email_queue',
        'app.profile',
        'app.file_storage',
        'app.role',
        'app.authenticationToken',
        'app.authenticationLog',
        'app.authenticationBlacklist',
        'core.cakeSession',
    );

    public function testNotAllowed() {
        // test getting all the users with the anonymous user
        $this->setExpectedException('HttpException', 'You need to login to access this location');
        json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
    }

    public function testAuthHeaders() {
        $result = $this->myTestAction(Router::url('/auth/verify.json', true));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Version']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Verify-URL']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Pubkey-URL']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Login-URL']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Logout-URL']));
    }

    public function testGpgAuthGetServerPublicKey() {
        // Test that the user is returned properly in the session (authentication has done its job)
        $result = json_decode($this->testAction('/auth/verify.json', array('return' => 'contents', 'method' => 'GET'), true));
        $this->assertTrue(isset($result->body->fingerprint));
        $this->assertTrue(isset($result->body->keydata));
        $this->assertEquals($result->body->fingerprint, Configure::read('Auth.gpg.serverKey.fingerprint'));

    }

    public function testGpgAuthVerify() {

//        // check logging in with a good user
//        $data = array(
//            'gpg_auth' => array(
//                'keyid' => '333788B5464B797FDF10A98F2FE96B47C7FF421A', // user@passbolt.com
//                'server_verify_token' => 'testounette'
//            )
//        );
//
//        // Test that the user is returned properly in the session (authentication has done its job)
//        $result = $this->testAction(
//            '/users/login',
//            array('return' => 'vars', 'method' => 'POST', 'data' => $data),
//            true
//        );
//
//        // test with anonymous user
//        $result = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
//        $this->assertNotEmpty($result);

    }

    /**
     * Convenience function to replace testaction to get access to headers
     * @param $url
     * @return bool
     */
    function myTestAction($url) {
        $result = false;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, Router::url('/auth/verify.json',true));
        $response = curl_exec($ch);

        if(empty($response)) {
            return $result;
        }

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $header = substr($response, 0, $header_size);
        $result['headers'] = $this->_get_headers_from_curl_response($header);
        $result['body'] = substr($response, $header_size);
        return $result;
    }

    /**
     * Parse headers from curl response into associative array
     * @param $response
     * @return array
     */
    protected function _get_headers_from_curl_response($response) {
        $headers = array();
        $header_text = substr($response, 0, strpos($response, "\r\n\r\n"));

        foreach (explode("\r\n", $header_text) as $i => $line)
            if ($i === 0) {
                $headers['http_code'] = $line;
            } else {
                list ($key, $value) = explode(': ', $line);
                $headers[$key] = $value;
            }

        return $headers;
    }

// --- OLD FORM BASED LOGIN TESTS -----

//    public function testLoginWrongUser() {
//        // Make sure there is no session active after each test
//        $this->User->setInactive();
//        $data = array(
//            'User' => array(
//                'username' => 'biloute@passbolt.com',
//                'password' => 'ouaich mec'
//            )
//        );
//        $result = $this->testAction(
//            '/users/login',
//            array('return' => 'view', 'method' => 'POST', 'data' => $data),
//            true
//        );
//        $this->assertTextContains('Username', $result);
//        $this->assertTextContains('Password', $result);
//    }
//
//    public function testLogin() {
//        // check if we get form
//        $result = $this->testAction('/login', array('return' => 'view', 'method' => 'GET'), true);
//        $this->assertEquals(
//            preg_match('/(<form)/', $result),
//            true,
//            '/users/login with no data sent should return a form'
//        );
//
//        // check logging in with a good user
//        $data = array(
//            'User' => array(
//                'username' => 'user@passbolt.com',
//                'password' => 'password'
//            )
//        );
//
//        // Test that the user is returned properly in the session (authentication has done its job)
//        $result = $this->testAction(
//            '/users/login',
//            array('return' => 'vars', 'method' => 'POST', 'data' => $data),
//            true
//        );
//        $this->assertEquals(
//            $this->User->get('User.username'),
//            'user@passbolt.com',
//            "login test should have returned user@passbolt.com but has returned {$this->User->get('User.username')}"
//        );
//
//        // Test that the redirection is there as it should
//        $result = $this->testAction(
//            '/users/login',
//            array('return' => 'view', 'method' => 'POST', 'data' => $data),
//            true
//        );
//        $this->assertEquals(
//            $this->headers['Location'],
//            Router::url('/', true),
//            "Login should have redirected to / but has not"
//        );
//    }

}
