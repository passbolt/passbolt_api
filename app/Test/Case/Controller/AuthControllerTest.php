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

    protected $_gpg;
    protected $_keys;

    /**
     * Instanciate test helpers
     */
    public function setup() {
        parent::setup();
        $this->_gpgSetup();
    }

    /**
     * Test accessing a resource that requires login
     */
    public function testNotAllowed() {
        // test getting all the users with the anonymous user
        $this->setExpectedException('HttpException', 'You need to login to access this location');
        json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
    }

    /**
     * Check that GPGAuth headers are set everywhere
     */
    public function testGetHeaders() {
        $result = $this->myTestAction(Router::url('/', true));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Version']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Verify-URL']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Pubkey-URL']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Login-URL']));
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Logout-URL']));
    }

    /**
     * Test that the passbolt instance public keys is available in the address provided in the headers
     */
    public function testGetServerPublicKey() {
        // get the server public key
        $result = $this->myTestAction(Router::url('/', true));
        $result = json_decode($this->testAction(
            $result['headers']['X-GPGAuth-Verify-URL'] . DS . 'json',
            array('return' => 'contents', 'method' => 'GET'), true)
        );
        // check the key data and fingerprint are set and match the config
        $this->assertTrue(isset($result->body->fingerprint));
        $this->assertTrue(isset($result->body->keydata));
        $this->assertEquals($result->body->fingerprint, $this->_keys['server']['fingerprint']);
    }

    /**
     * Test authentication with wrong user key fingerprint
     */
    public function testStage0Fingerprint() {
        $server_verify_token = 'gpgauthv1.3.0|36|'.String::uuid().'gpgauthv1.3.0';

        $wrong = array(
            '' => false,                                          // empty
            'XXX' => false,                                       // wrong format
            '333788B5464B797FDF10A98F2FE96B47C7FF421B' => false,  // does not exist
            '333788B5464B797FDF10A98F2FE96B47C7FF421AB' => false, // wrong format
            '333788B5464B797FDF10A98F2FE96\47C7FF41AB' => false,  // wrong format
            '333788B5464B797FDF10A98F2FE96"47C7FF41AB' => false,  // wrong format
            "333788B5464B797FDF10A98F2FE96'47C7FF41AZ" => false,  // wrong format
            '﻿333788B5464B797FDF10A98F2FE96B47C7FF421A' => true   // right
        );

        foreach($wrong as $keyid => $success_expected) {
            $result = $this->myTestAction(
                Router::url('/auth/verify', true),
                array(
                    'data[gpg_auth][keyid]' => $keyid,
                    'data[gpg_auth][server_verify_token]' => $server_verify_token
                )
            );

            $this->assertTrue(isset($result['headers']['X-GPGAuth-Authenticated']));
            $this->assertEquals($result['headers']['X-GPGAuth-Authenticated'], 'false', 'the user should not be authenticate at that point');
            $this->assertTrue(isset($result['headers']['X-GPGAuth-Progress']));
            $this->assertEquals($result['headers']['X-GPGAuth-Progress'],'stage0');

            if ($success_expected) {
                $this->assertFalse(isset($result['headers']['X-GPGAuth-Error']),'The fingerprint: '. $keyid . ' should work');
                $this->assertFalse(isset($result['headers']['X-GPGAuth-Verify-Response']));
            } else {
                $this->assertTrue(isset($result['headers']['X-GPGAuth-Error']),'The fingerprint: '. $keyid . ' should not work');
            }
        }
    }

    /**
     * Stage 0. with good user fingerprint
     */
    public function testStage0WrongMessageFormat() {
        $uuid = String::uuid();

        $wrong = array(
            '',                                               // empty
            'XXX',                                            // wrong format
            'gpgauthv1.2.0,32|'.$uuid.'|gpgauthv1.3.0',       // wrong delimiter
            'gpgauthv1.2.0|32|'.$uuid.'|gpgauthv1.3.0',       // wrong version
            'gpgauthv1.3.0|32|'.$uuid.'|gpgauthv1.2.0',       // wrong version 2
            'gpgauthv1.3.0|36|'.$uuid.'|gpgauthv1.3.0',       // wrong length
            'gpgauthv1.3.0||'.$uuid.'|gpgauthv1.3.0',         // wrong length 2
            'gpgauthv1.3.0|64|'.$uuid.$uuid.'|gpgauthv1.3.0', // wrong length 3
            'gpgauthv1.3.0|0|'.$uuid.$uuid.'|gpgauthv1.3.0',  // wrong length 4
            'gpgauthv1.3.0|32|'.$uuid.'|gpgauthv1.3.0|x'      // wrong format

        );
        $this->_gpg->addencryptkey($this->_keys['user']['fingerprint']);

        foreach($wrong as $token) {
            $msg = $this->_gpg->encrypt($token);
            $result = $this->myTestAction(
                Router::url('/auth/verify', true),
                array(
                    'data[gpg_auth][keyid]' => $this->_keys['user']['fingerprint'],
                    'data[gpg_auth][server_verify_token]' => $token
                )
            );

            $this->assertTrue(isset($result['headers']['X-GPGAuth-Authenticated']));
            $this->assertEquals($result['headers']['X-GPGAuth-Authenticated'], 'false');
            $this->assertTrue(isset($result['headers']['X-GPGAuth-Progress']));
            $this->assertEquals($result['headers']['X-GPGAuth-Progress'],'stage05');
        }
    }

    // ====== UTILITIES =========================================================

    /**
     * Setup GPG and import the keys to be used in the tests
     */
    function _gpgSetup() {
        $this->_gpg = new gnupg();

        // @TODO from fixtures?
        // keys to be used in the tests
        $this->_keys = array(
            'server' => Configure::read('Auth.gpg.serverKey'),
            'user' => array(
                'fingerprint' => '333788B5464B797FDF10A98F2FE96B47C7FF421A',
                'public' => APP . 'Config' . DS . 'gpg' . DS . 'ada_public.key',
                'private' => APP . 'Config' . DS . 'gpg' . DS . 'ada_private.key',
                'passphrase' => ''
            )
        );

        // Make sure the keys are in the keyring
        // if needed we add them for later use in the tests
        $this->_gpg = new gnupg();
        foreach ($this->_keys as $name => $key) {
            $info = $this->_gpg->keyinfo($key['fingerprint']);
            if (empty($info)) {
                $type = ($name == 'server') ? 'public' : 'private';
                $keydata = file_get_contents($key[$type]);
                $this->_gpg->import($keydata);
            }
        }
    }

    /**
     * Convenience function to replace testaction to get access to headers
     * @param $url
     * @return bool
     */
    function myTestAction($url, $data = null) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        // if post data
        if(!empty($data)) {
            $data_string = '';
            foreach($data as $key=>$value) { $data_string .= $key.'='.urlencode($value).'&'; }
            rtrim($data_string, '&');
            curl_setopt($ch,CURLOPT_POST, count($data));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $data_string);
        }

        $response = curl_exec($ch);

        if(empty($response)) {
            return false;
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
