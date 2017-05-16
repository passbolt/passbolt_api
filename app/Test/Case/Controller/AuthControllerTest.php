<?php
/**
 * Authentication Controller Tests
 *
 * @copyright (c) 2015 Bolt Softwares Pvt Ltd
 * @licence GNU Affero General Public License http://www.gnu.org/licenses/agpl-3.0.en.html
 */
App::uses('AppController', 'Controller');
App::uses('User', 'Model');
App::uses('Role', 'Model');
App::uses('Gpgkey', 'Model');

// Uses sessions
// App::uses('CakeSession', 'Model/Datasource'); // doesn't work here
if (!class_exists('CakeSession')) {
	require CAKE . 'Model/Datasource/CakeSession.php';
}
if (!class_exists('\Passbolt\Gpg')) {
	App::import( 'Model/Utility', 'Gpg');
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
		'core.cakeSession',
		'app.user_agent',
		'app.controller_log',
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
		$this->setExpectedException('ForbiddenException', 'You need to login to access this location');
		$r = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
	}

/**
 * Test error 500 if the GnuPG fingerprint config for the server is missing.
 * It can happen if a sysop overrides the GnuPG config for the server post installation.
 */
	public function testLoginServerKeyFingerprintMissing() {
		Configure::delete('GPG.serverKey.fingerprint');
		$this->setExpectedException('InternalErrorException', 'The GnuPG config for the server is not available or incomplete');
		json_decode($this->testAction('/auth/login', array('return' => 'contents', 'method' => 'GET'), true));
	}

/**
 * Test error 500 if the GnuPG fingerprint config for the server is invalid.
 * It can happen if a sysop changed the server key fingerprint without loading this key in the gpg keyring post installation.
 */
	public function testLoginBadServerKeyFingerprint() {
		Configure::write('GPG.serverKey.fingerprint', '0000000000000000000000000000000000000000');
		$this->setExpectedException('InternalErrorException', 'The GPG Server key defined in the config is not found in the gpg keyring');
		json_decode($this->testAction('/auth/login', array('return' => 'contents', 'method' => 'GET'), true));
	}

/**
 * Test error 500 if config is invalid
 */
	public function testVerifyBadConfig() {
		Configure::write('GPG.serverKey.public', 'wrong');
		$this->setExpectedException('InternalErrorException', 'The public key for this passbolt instance was not found.');
		json_decode($this->testAction('/auth/verify.json', array('return' => 'contents', 'method' => 'GET'), true));
	}

/**
 * Test verify json layout
 */
	public function testVerifyLayout() {
		$r = json_decode($this->testAction('/auth/verify.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertTrue(isset($r->body), 'The layout should be JSON');

		$r = json_decode($this->testAction('/auth/verify', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertTrue(!isset($r->body), 'The layout should not be JSON');
	}

/**
 * Check that GPGAuth headers are set everywhere
 */
	public function testGetHeaders() {
		$this->testAction('/');
		$this->assertTrue(isset($this->headers['X-GPGAuth-Version']));
		$this->assertTrue(isset($this->headers['X-GPGAuth-Verify-URL']));
		$this->assertTrue(isset($this->headers['X-GPGAuth-Pubkey-URL']));
		$this->assertTrue(isset($this->headers['X-GPGAuth-Login-URL']));
		$this->assertTrue(isset($this->headers['X-GPGAuth-Logout-URL']));
	}

/**
 * Test that the passbolt instance public keys is available in the address provided in the headers
 */
	public function testGetServerPublicKey() {
		// get the server public key
		$this->testAction('/');
		$result = json_decode($this->testAction(
				$this->headers['X-GPGAuth-Verify-URL'] . DS . 'json',
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
		$fix = array(
			'' => false, // wrong empty
			'XXX' => false, // wrong format
			'333788B5464B797FDF10A98F2FE96B47C7FF421B' => false, // wrong does not exist
			'333788B5464B797FDF10A98F2FE96B47C7FF421AB' => false, // wrong format
			'333788B5464B797FDF10A98F2FE96\47C7FF41AB' => false, // wrong format
			'333788B5464B797FDF10A98F2FE96"47C7FF41AB' => false, // wrong format
			"333788B5464B797FDF10A98F2FE96'47C7FF41AZ" => false, // wrong format
			strtoupper($this->_keys['user']['fingerprint']) => true, // right format uppercase
			strtolower($this->_keys['user']['fingerprint']) => true, // right format lowercase
		);

		foreach ($fix as $keyid => $expectSuccess) {
			$this->testAction('/auth/login', array(
					'data' => array( 'gpg_auth' => array(
						'keyid' => $keyid
					)))
			);
			$this->assertTrue(isset($this->headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set for keyid:' . $keyid);
			$this->assertEquals($this->headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
			$this->assertTrue(isset($this->headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
			$this->assertNotEquals($this->headers['X-GPGAuth-Progress'], 'stage2', 'The progress indicator should not be stage 2');
			$this->assertNotEquals($this->headers['X-GPGAuth-Progress'], 'complete', 'The progress indicator should not be stage 2');

			if ($expectSuccess) {
				$msg = (isset($this->headers['X-GPGAuth-Debug'])) ? $this->headers['X-GPGAuth-Debug'] . ': ' . $keyid :
					'The fingerprint: ' . $keyid . ' should work';
				$this->assertFalse(isset($this->headers['X-GPGAuth-Error']), $msg);
				$this->assertFalse(isset($this->headers['X-GPGAuth-Verify-Response']));
			} else {
				$this->assertTrue(isset($this->headers['X-GPGAuth-Error']), 'There should be an error header set for keyid:' . $keyid);
				$this->assertEquals($this->headers['X-GPGAuth-Error'], 'true', 'There should be an error header set to true for keyid:' . $keyid);
			}
		}
	}

/**
 * Stage 0. Verify server key
 */
	public function testStage0MessageFormat() {
		$uuid = Common::uuid();

		$fix = array(
			//'' => false, // empty
			'XXX' => false, // wrong format
			'gpgauthv1.2.0,32|' . $uuid . '|gpgauthv1.3.0' => false, // wrong delimiter
			'gpgauthv1.2.0|32|' . $uuid . '|gpgauthv1.3.0' => false, // wrong version
			'gpgauthv1.3.0|32|' . $uuid . '|gpgauthv1.2.0' => false, // wrong version 2
			'gpgauthv1.3.0|36|' . $uuid . '|gpgauthv1.3.0' => false, // wrong length
			'gpgauthv1.3.0||' . $uuid . '|gpgauthv1.3.0' => false, // wrong length 2
			'gpgauthv1.3.0|64|' . $uuid . $uuid . '|gpgauthv1.3.0' => false, // wrong length 3
			'gpgauthv1.3.0|0|' . $uuid . $uuid . '|gpgauthv1.3.0' => false, // wrong length 4
			'gpgauthv1.3.0|32|' . $uuid . '|gpgauthv1.3.0|x' => false, // wrong format
			'gpgauthv1.3.0|36|' . $uuid . '|gpgauthv1.3.0' => true // right
		);

		$this->_gpg->addencryptkey($this->_keys['server']['fingerprint']);

		foreach ($fix as $token => $expectSuccess) {
			$msg = $this->_gpg->encrypt($token);
			$this->testAction('/auth/verify.json', array(
					'data' => array( 'gpg_auth' => array(
						'keyid' => $this->_keys['user']['fingerprint'],
						'server_verify_token' => $msg
					)))
			);

			$this->assertTrue(isset($this->headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
			$this->assertEquals($this->headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
			$this->assertTrue(isset($this->headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
			$this->assertEquals($this->headers['X-GPGAuth-Progress'], 'stage0', 'The progress indicator should be set to stage0 for token.');

			if (!$expectSuccess) {
				$this->assertTrue(isset($this->headers['X-GPGAuth-Error']), 'There should be an error header for token:' . $token);
				$this->assertEquals($this->headers['X-GPGAuth-Error'], 'true', 'There should be an error header set to true for token:' . $token);
				$this->assertTrue(isset($this->headers['X-GPGAuth-Debug']), 'A debug message should be set in the headers');
				$this->assertFalse(
					strpos($this->headers['X-GPGAuth-Debug'], 'Invalid verify token format') === false,
					'The debug message should contain "Invalid verify token format"'
				);
			} else {
				$this->assertTrue(isset($this->headers['X-GPGAuth-Verify-Response']), 'The verify response header should be set for ' . $token);
				$this->assertEquals($this->headers['X-GPGAuth-Verify-Response'], $token,
					'The verify response header should match the original token. It is ' . $this->headers['X-GPGAuth-Verify-Response'] . ' instead of ' . $token
				);
			}
		}
	}

/**
 * Stage 1. Authenticate user
 */
	public function testStage1UserToken() {
		$this->testAction('/auth/login', array(
				'data' => array(
					'gpg_auth' => array('keyid' => $this->_keys['user']['fingerprint'])
				)
			));

		// check headers
		$this->assertTrue(isset($this->headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
		$this->assertEquals($this->headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
		$this->assertTrue(isset($this->headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
		$this->assertEquals($this->headers['X-GPGAuth-Progress'], 'stage1', 'The progress indicator should be set to stage1');
		$this->assertTrue(isset($this->headers['X-GPGAuth-User-Auth-Token']), 'User authentication token should be set');

		// try to decrypt the message
		$this->assertTrue(
			$this->_gpg->adddecryptkey($this->_keys['server']['fingerprint'], $this->_keys['user']['passphrase']),
			'CONFIG - It is not possible to use the key provided in the fixtures to decrypt.'
		);
		$msg = (stripslashes(urldecode($this->headers['X-GPGAuth-User-Auth-Token'])));
		$plaintext = '';
		$info = $this->_gpg->decryptverify($msg, $plaintext);
		$this->assertFalse(($info === false), 'Could not decrypt the server generated User Auth Token: ' . $msg);
		$this->assertFalse(($plaintext === ''), 'Could not decrypt the server generated User Auth Token: ' . $msg);
		$this->assertEquals(strtoupper($info[0]['fingerprint']), strtoupper($this->_keys['server']['fingerprint']), 'Server signature is not matching known fingerprint');

		// Decrypt and check if the token is in the right format
		$info = explode('|', $plaintext);
		$this->assertTrue((count($info) == 4), 'Decrypted User Auth Token: sections missing or wrong delimiters: ' . $plaintext);
		list($version, $length, $uuid, $version2) = $info;
		$this->assertTrue($version == $version2, 'Decrypted User Auth Token: version numbers don\'t match: ' . $plaintext);
		$this->assertTrue($version == 'gpgauthv1.3.0', 'Decrypted User Auth Token: wrong version number: ' . $plaintext);
		$this->assertTrue($version == Common::isUuid($uuid), 'Decrypted User Auth Token: not a UUID: ' . $plaintext);
		$this->assertTrue($length == 36, 'Decrypted User Auth Token: wrong token data length');

		// Check if there is a valid AuthToken in store
		$AuthToken = Common::getModel('AuthenticationToken');
		$isValid = $AuthToken->isValid($uuid, Common::uuid('user.id.ada'));
		$this->assertTrue(!empty($isValid), 'There should a valid auth token');

		// Send it back!
		$this->testAction('/auth/login', array(
				'data' => array(
					'gpg_auth' => array(
						'keyid' => $this->_keys['user']['fingerprint'],
						'user_token_result' => $plaintext
					)
				)
			));

		if (isset($this->headers['X-GPGAuth-Debug'])) {
			$this->assertTrue(false, 'There should be no debug header set to true for token: ' .
				$uuid . '. Debug: ' . $this->headers['headers']['X-GPGAuth-Debug']);
		}

		$this->assertFalse(isset($this->headers['X-GPGAuth-Error']), 'There should not be an error header for token: ' . $uuid);
		$this->assertTrue(isset($this->headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
		$this->assertEquals($this->headers['X-GPGAuth-Authenticated'], 'true', 'The user should be authenticated at that point');
		$this->assertTrue(isset($this->headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
		$this->assertEquals($this->headers['X-GPGAuth-Progress'], 'complete', 'The progress indicator should be set to complete');

		// Authentication token should be disabled at that stage
		$isValid = $AuthToken->isValid($uuid, Common::uuid('user.id.ada'));
		$this->assertTrue(empty($isValid), 'There should a valid auth token');

		// Check if we can access users
		$r = json_decode($this->testAction('/users.json', array('return' => 'contents', 'method' => 'GET'), true));
		$this->assertTrue(count($r->body) > 1, 'There should be some users');
	}

/**
 * Test user cannot login if account is disabled
 */
	public function testNoLoginIfInactiveAndDeleted() {
		$this->_gpgSetup('ruth', false);
		$this->assertEquals('9B78A8F124D440FEF5A52159546B4787A3A2AE97', $this->_keys['user']['fingerprint']);
		$this->testAction('/auth/login', array(
				'data' => array(
					'gpg_auth' => array('keyid' => $this->_keys['user']['fingerprint'])
				)
			));
		$this->assertTrue(isset($this->headers['X-GPGAuth-Error']), 'There should not be an error header for fingerprint: ' . $this->_keys['user']['fingerprint']);
	}

/**
 * Test user cannot login if account is inactive
 */
	public function testNoLoginIfInactive() {
		$this->_gpgSetup('orna', false);
		$this->assertEquals('E2E98DCC84FB41F69603C346EA62E0B3397EEAB6', $this->_keys['user']['fingerprint']);
		$this->testAction('/auth/login', array(
				'data' => array(
					'gpg_auth' => array('keyid' => $this->_keys['user']['fingerprint'])
				)
			));
		$this->assertTrue(isset($this->headers['X-GPGAuth-Error']), 'There should not be an error header for fingerprint: ' . $this->_keys['user']['fingerprint']);
	}

/**
 * Test user cannot login if account is active but is marked as deleted
 */
	public function testNoLoginIfActiveAndDeleted() {
		$this->_gpgSetup('sofia', false);
		$this->assertEquals('252B91CB28A96C6D67E8FC139020576F08D8B763', $this->_keys['user']['fingerprint']);
		$this->testAction('/auth/login', array(
				'data' => array(
					'gpg_auth' => array('keyid' => $this->_keys['user']['fingerprint'])
				)
			));
		$this->assertTrue(isset($this->headers['X-GPGAuth-Error']), 'There should not be an error header for fingerprint: ' . $this->_keys['user']['fingerprint']);
	}

	// ====== UTILITIES =========================================================

/**
 * Setup GPG and import the keys to be used in the tests
 * @param string $name ada by default
 */
	protected function _gpgSetup($name = 'ada', $private = true) {
		$this->_gpg = new gnupg();
		$this->_gpg->seterrormode(gnupg::ERROR_EXCEPTION);

		// keys to be used in the tests
		$this->_keys = array(
			'server' => Configure::read('GPG.serverKey'),
			'user' => array(
				'public' => Configure::read('GPG.testKeys.path') . $name . '_public.key',
				'passphrase' => ''
			)
		);
		if ($private) {
			// only keys with no passphrase are supported at the moment
			$this->_keys['user']['private'] = Configure::read('GPG.testKeys.path') . $name . '_private_nopassphrase.key';
		}

		// Get fingerprint and add it to array.
		$Gpg = new \Passbolt\Gpg();
		$publicKeyinfo = $Gpg->getKeyInfo(file_get_contents($this->_keys['user']['public']));
		$this->_keys['user']['fingerprint'] = $publicKeyinfo['fingerprint'];

		// Make sure the keys are in the keyring
		// if needed we add them for later use in the tests
		$this->_gpg = new gnupg();
		foreach ($this->_keys as $name => $key) {
			$types = array('public', 'private');
			foreach ($types as $type) {
				if (isset($key[$type])) {
					$keydata = file_get_contents($key[$type]);
					if (!$this->_gpg->import($keydata)) {
						echo 'could not import ' . $type . ' key' . $key['fingerprint'];
						die;
					}
				}
			}
		}
	}
}
