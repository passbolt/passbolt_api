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
App::uses('Gpgkey', 'Model');

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
    public function testAllStagesFingerprint() {
        $server_verify_token = 'gpgauthv1.3.0|36|'.String::uuid().'gpgauthv1.3.0';

        $fix = array(
            '' => false,                                          // wrong empty
            'XXX' => false,                                       // wrong format
            '333788B5464B797FDF10A98F2FE96B47C7FF421B' => false,  // wrong does not exist
            '333788B5464B797FDF10A98F2FE96B47C7FF421AB'=> false,  // wrong format
            '333788B5464B797FDF10A98F2FE96\47C7FF41AB' => false,  // wrong format
            '333788B5464B797FDF10A98F2FE96"47C7FF41AB' => false,  // wrong format
            "333788B5464B797FDF10A98F2FE96'47C7FF41AZ" => false,  // wrong format
            strtoupper($this->_keys['user']['fingerprint']) => true,   // right format uppercase
            strtolower($this->_keys['user']['fingerprint']) => true,   // right format lowercase
        );

        foreach($fix as $keyid => $success_expected) {
            $result = $this->myTestAction(
                Router::url('/auth/login', true),
                array(
                    'data[gpg_auth][keyid]' => $keyid
                )
            );

            $this->assertTrue(isset($result['headers']['X-GPGAuth-Authenticated']), 'Authentication headers should be set for keyid:' . $keyid);
            $this->assertEquals($result['headers']['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
            $this->assertTrue(isset($result['headers']['X-GPGAuth-Progress']),'The progress indicator should be set in the headers');
            $this->assertNotEquals($result['headers']['X-GPGAuth-Progress'],'stage2','The progress indicator should not be stage 2');
            $this->assertNotEquals($result['headers']['X-GPGAuth-Progress'],'complete','The progress indicator should not be stage 2');

            if ($success_expected) {
                $msg = (isset($result['headers']['X-GPGAuth-Debug'])) ? $result['headers']['X-GPGAuth-Debug'] . ': '.$keyid :
                    'The fingerprint: '. $keyid . ' should work';
                $this->assertFalse(isset($result['headers']['X-GPGAuth-Error']), $msg);
                $this->assertFalse(isset($result['headers']['X-GPGAuth-Verify-Response']));
            } else {
                $this->assertTrue(isset($result['headers']['X-GPGAuth-Error']), 'There should be an error header set for keyid:' . $keyid);
                $this->assertEquals($result['headers']['X-GPGAuth-Error'], 'true', 'There should be an error header set to true for keyid:' . $keyid);
            }
        }
    }

    /**
     * Stage 0. with good user fingerprint with check different server verify token
     */
    public function testStage0MessageFormat() {
        $uuid = String::uuid();

        $fix = array(
            //'' => false,                                               // empty
            'XXX' => false,                                            // wrong format
            'gpgauthv1.2.0,32|'.$uuid.'|gpgauthv1.3.0' => false,       // wrong delimiter
            'gpgauthv1.2.0|32|'.$uuid.'|gpgauthv1.3.0' => false,       // wrong version
            'gpgauthv1.3.0|32|'.$uuid.'|gpgauthv1.2.0' => false,       // wrong version 2
            'gpgauthv1.3.0|36|'.$uuid.'|gpgauthv1.3.0' => false,       // wrong length
            'gpgauthv1.3.0||'.$uuid.'|gpgauthv1.3.0' => false,         // wrong length 2
            'gpgauthv1.3.0|64|'.$uuid.$uuid.'|gpgauthv1.3.0' => false, // wrong length 3
            'gpgauthv1.3.0|0|'.$uuid.$uuid.'|gpgauthv1.3.0' => false,  // wrong length 4
            'gpgauthv1.3.0|32|'.$uuid.'|gpgauthv1.3.0|x' => false,     // wrong format
            'gpgauthv1.3.0|36|'.$uuid.'|gpgauthv1.3.0' => true         // right
        );

        $this->_gpg->addencryptkey($this->_keys['server']['fingerprint']);

        foreach ($fix as $token => $expect_success) {
            $msg = $this->_gpg->encrypt($token);
            $result = $this->myTestAction(
                Router::url('/auth/verify.json', true),
                array(
                    'data[gpg_auth][keyid]' => $this->_keys['user']['fingerprint'],
                    'data[gpg_auth][server_verify_token]' => $msg
                )
            );

            $this->assertTrue(isset($result['headers']['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
            $this->assertEquals($result['headers']['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
            $this->assertTrue(isset($result['headers']['X-GPGAuth-Progress']),'The progress indicator should be set in the headers');
            $this->assertEquals($result['headers']['X-GPGAuth-Progress'],'stage0','The progress indicator should be set to stage0 for token.');

            if (!$expect_success) {
                $this->assertTrue(isset($result['headers']['X-GPGAuth-Error']),'There should be an error header for token:' . $token);
                $this->assertEquals($result['headers']['X-GPGAuth-Error'], 'true', 'There should be an error header set to true for token:' . $token);
                $this->assertTrue(isset($result['headers']['X-GPGAuth-Debug']), 'A debug message should be set in the headers');
                $this->assertFalse(
                    strpos($result['headers']['X-GPGAuth-Debug'],'Invalid verify token format') === false,
                    'The debug message should contain "Invalid verify token format"');
            } else {
                $this->assertTrue(isset($result['headers']['X-GPGAuth-Verify-Response']),'The verify response header should be set for ' . $token);
                $this->assertEquals($result['headers']['X-GPGAuth-Verify-Response'], $token,
                    'The verify response header should match the original token. It is ' . $result['headers']['X-GPGAuth-Verify-Response'] . ' instead of ' . $token
                );
            }
        }
    }

    /**
     * Check if a token is send by the server in the right format
     */
    public function testStage1UserToken() {
        // we consider that stage 1 was successful, e.g. that the user checked the server key
        $result = $this->myTestAction(
            Router::url('/auth/login', true),
            array( 'data[gpg_auth][keyid]' => $this->_keys['user']['fingerprint'])
        );

        // check headers
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
        $this->assertEquals($result['headers']['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Progress']),'The progress indicator should be set in the headers');
        $this->assertEquals($result['headers']['X-GPGAuth-Progress'],'stage1','The progress indicator should be set to stage1');
        $this->assertTrue(isset($result['headers']['X-GPGAuth-User-Auth-Token']), 'User authentication token should be set');

        // try to decrypt the message
        $this->assertTrue(
            $this->_gpg->adddecryptkey($this->_keys['server']['fingerprint'], $this->_keys['user']['passphrase']),
            'CONFIG - It is not possible to use the key provided in the fixtures to decrypt.'
        );
        $msg = (stripslashes(urldecode($result['headers']['X-GPGAuth-User-Auth-Token'])));
        $plaintext = '';
        $info =  $this->_gpg->decryptverify($msg,$plaintext);
        $this->assertFalse(($info === false), 'Could not decrypt the server generated User Auth Token: ' . $msg);
        $this->assertFalse(($plaintext === ''), 'Could not decrypt the server generated User Auth Token: ' . $msg);
        $this->assertEquals(strtoupper($info[0]['fingerprint']), strtoupper($this->_keys['server']['fingerprint']), 'Server signature is not matching known fingerprint');

        // Decrypt and check if the token is in the right format
        $result = explode('|', $plaintext);
        $this->assertTrue((count($result) == 4), 'Decrypted User Auth Token: sections missing or wrong delimiters: ' . $plaintext);
        list($version, $length, $uuid, $version2) = $result;
        $this->assertTrue($version == $version2, 'Decrypted User Auth Token: version numbers don\'t match: ' . $plaintext);
        $this->assertTrue($version == 'gpgauthv1.3.0', 'Decrypted User Auth Token: wrong version number: ' . $plaintext);
        $this->assertTrue($version == Common::isUuid($uuid), 'Decrypted User Auth Token: not a UUID: ' . $plaintext);
        $this->assertTrue($length == 36, 'Decrypted User Auth Token: wrong token data length');


        $AuthenticationToken = Common::getModel('AuthenticationToken');

        $token = $AuthenticationToken->createToken(Common::uuid('user.id.ada'), AuthenticationToken::UUID);

        $this->assertTrue($token != false, 'Token should not be false');
        $r = $AuthenticationToken->checkTokenIsValid($token['AuthenticationToken']['token'], Common::uuid('user.id.ada'));
        $this->assertFalse(empty($r), 'r should not be empty');

        // Send it back!
        $result = $this->myTestAction(
            Router::url('/auth/login', true),
            array(
                'data[gpg_auth][keyid]' => $this->_keys['user']['fingerprint'],
                'data[gpg_auth][user_token_result]' => $plaintext
            )
        );

        if(isset($result['headers']['X-GPGAuth-Debug'])) {
            $this->assertTrue(false,'There should be no debug header set to true for token: ' .
              $uuid . '. Debug: '.$result['headers']['X-GPGAuth-Debug']);
        }
        $this->assertFalse(isset($result['headers']['X-GPGAuth-Error']),'There should not be an error header for token: ' . $uuid);
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
        $this->assertEquals($result['headers']['X-GPGAuth-Authenticated'], 'true', 'The user should be authenticated at that point');
        $this->assertTrue(isset($result['headers']['X-GPGAuth-Progress']),'The progress indicator should be set in the headers');
        $this->assertEquals($result['headers']['X-GPGAuth-Progress'],'complete','The progress indicator should be set to complete');

    }

    // ====== UTILITIES =========================================================

    /**
     * Setup GPG and import the keys to be used in the tests
     */
    protected function _gpgSetup() {
        $this->_gpg = new gnupg();
        $this->_gpg->seterrormode(gnupg::ERROR_EXCEPTION);

        // @TODO from fixtures?
        // keys to be used in the tests
        $this->_keys = array(
            'server' => Configure::read('GPG.serverKey'),
            'user' => array(
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'public' => Configure::read('GPG.testKeys.path') . 'ada_public.key',
                'private' => Configure::read('GPG.testKeys.path') . 'ada_private_nopassphrase.key',
                'passphrase' => ''
            )
        );

        // Make sure the keys are in the keyring
        // if needed we add them for later use in the tests
        $this->_gpg = new gnupg();
        foreach ($this->_keys as $name => $key) {
            //$type = ($name == 'server') ? 'public' : 'private';
            $type = 'public';
            $keydata = file_get_contents($key[$type]);
            if(!$this->_gpg->import($keydata)) {
                echo 'could not import ' . $type . ' key' . $key['fingerprint'];
                die;
            }
            $type = 'private';
            $keydata = file_get_contents($key[$type]);
            if(!$this->_gpg->import($keydata)) {
                echo 'could not import ' . $type . ' key' . $key['fingerprint'];
                die;
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
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));

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
