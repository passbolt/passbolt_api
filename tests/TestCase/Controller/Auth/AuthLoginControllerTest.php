<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Auth;

use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestCase;
use Cake\Validation\Validation;
use PassboltTestData\Shell\Task\Base\GpgkeysDataTask;

class AuthLoginControllerTest extends IntegrationTestCase
{
    public $fixtures = [
        'app.Base/users', 'app.Base/roles', 'app.Base/profiles', 'app.Base/authentication_tokens',
        'app.Base/gpgkeys', 'app.Base/groups_users', 'app.Base/avatars'
    ];
    public $keyid;
    public $gpg;

    // Keys ids used in this test. Set in _gpgSetup.
    protected $adaKeyId;
    protected $serverKeyId;

    public function testUserLoginGetSuccess()
    {
        $this->get('/auth/login');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('<div id="container" class="page login', $data);
    }

    /**
     * Test getting login started with deleted account
     */
    public function testUserLoginAsDeletedUserError()
    {
        $this->post('/auth/login', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => '252B91CB28A96C6D67E8FC139020576F08D8B763'
                ]
            ]
        ]);
        $msg = 'There is no user associated with this key. User not found.';
        $this->assertHeader('X-GPGAuth-Debug', $msg);
    }

    /**
     * Test error 500 if the GnuPG fingerprint config for the server is missing.
     * It can happen if a sysop overrides the GnuPG config for the server post installation.
     */
    public function testLoginServerKeyFingerprintMissing()
    {
        Configure::delete('passbolt.gpg.serverKey.fingerprint');
        $this->post('/auth/login');
        $this->assertResponseFailure();
        $data = $this->_getBodyAsString();
        $expect = 'An Internal Error Has Occurred';
        $this->assertContains($expect, $data);
        $expect = 'The GnuPG config for the server is not available or incomplete';
        $this->assertContains($expect, $data);
    }

    /**
     * Test error 500 if the GnuPG fingerprint config for the server is invalid.
     * It can happen if a sysop changed the server key fingerprint without loading this key in the gpg keyring post installation.
     */
    public function testLoginBadServerKeyFingerprint()
    {
        $fingerprint = '0000000000000000000000000000000000000000';
        Configure::write('passbolt.gpg.serverKey.fingerprint', $fingerprint);
        $this->post('/auth/login');
        $this->assertResponseFailure();
        $data = $this->_getBodyAsString();
        $expect = 'An Internal Error Has Occurred';
        $this->assertContains($expect, $data);
        $expect = 'The OpenPGP server key defined in the config could not be found in the GnuPG keyring.';
        $this->assertContains($expect, $data);
    }

    /**
     * Check that GPGAuth headers are set everywhere
     */
    public function testGetHeaders()
    {
        $this->get('/auth/login');
        $this->assertHeader('X-GPGAuth-Version', '1.3.0');
        $this->assertHeader('X-GPGAuth-Verify-URL', '/auth/verify');
        $this->assertHeader('X-GPGAuth-Pubkey-URL', '/auth/verify.json');
        $this->assertHeader('X-GPGAuth-Login-URL', '/auth/login');
        $this->assertHeader('X-GPGAuth-Logout-URL', '/auth/logout');
    }

    /**
     * Check that GPGAuth headers are set everywhere
     */
    public function testGetHeadersPost()
    {
        $this->post('/auth/login', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => 'testid'
                ]
            ]
        ]);
        $this->assertHeader('X-GPGAuth-Progress', 'stage0');
    }

    /**
     * Test authentication with wrong user key fingerprint
     */
    public function testAllStagesFingerprint()
    {
        $this->_gpgSetup(); // add ada's keys
        $fix = [
            '' => false, // wrong empty
            'XXX' => false, // wrong format
            '333788B5464B797FDF10A98F2FE96B47C7FF421B' => false, // wrong does not exist
            '333788B5464B797FDF10A98F2FE96B47C7FF421AB' => false, // wrong format
            '333788B5464B797FDF10A98F2FE96\47C7FF41AB' => false, // wrong format
            '333788B5464B797FDF10A98F2FE96"47C7FF41AB' => false, // wrong format
            "333788B5464B797FDF10A98F2FE96'47C7FF41AZ" => false, // wrong format
            strtoupper($this->adaKeyId) => true, // right format uppercase ada public
            strtolower($this->adaKeyId) => true, // right format lowercase
        ];

        foreach ($fix as $keyid => $expectSuccess) {
            $this->post('/auth/login', [
                'data' => [
                    'gpg_auth' => [
                        'keyid' => $keyid
                    ]
                ]
            ]);
            $headers = $this->getHeaders();
            $this->assertTrue(isset($headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set for keyid:"' . $keyid . '"');
            $this->assertEquals($headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
            $this->assertTrue(isset($headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
            $this->assertNotEquals($headers['X-GPGAuth-Progress'], 'stage2', 'The progress indicator should not be stage 2');
            $this->assertNotEquals($headers['X-GPGAuth-Progress'], 'complete', 'The progress indicator should not be stage 2');

            if ($expectSuccess) {
                $msg = (isset($headers['X-GPGAuth-Debug'])) ? $headers['X-GPGAuth-Debug'][0] . ': ' . $keyid :
                    'The fingerprint: ' . $keyid . ' should work';
                $this->assertFalse(isset($headers['X-GPGAuth-Error']), $msg);
                $this->assertFalse(isset($headers['X-GPGAuth-Verify-Response']));
            } else {
                $this->assertTrue(isset($headers['X-GPGAuth-Error']), 'There should be an error header set for keyid:' . $keyid);
                $this->assertEquals($headers['X-GPGAuth-Error'], 'true', 'There should be an error header set to true for keyid:' . $keyid);
            }
        }
    }

    /**
     * Stage 0. Verify server key
     */
    public function testStage0MessageFormat()
    {
        $this->_gpgSetup();
        $uuid = UuidFactory::uuid();

        $fix = [
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
        ];

        $this->_gpg->addencryptkey($this->serverKeyId);
        $this->_gpg->addsignkey($this->adaKeyId);
        foreach ($fix as $token => $expectSuccess) {
            $msg = $this->_gpg->encrypt($token);
            $this->post('/auth/verify', [
                'data' => [
                    'gpg_auth' => [
                        'keyid' => $this->adaKeyId, // Ada's key
                        'server_verify_token' => $msg
                    ]
                ]
            ]);

            $headers = $this->getHeaders();
            $this->assertTrue(isset($headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
            $this->assertEquals($headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
            $this->assertTrue(isset($headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
            $this->assertEquals($headers['X-GPGAuth-Progress'], 'stage0', 'The progress indicator should be set to stage0 for token.');

            if (!$expectSuccess) {
                $this->assertTrue(isset($headers['X-GPGAuth-Error']), 'There should be an error header for token:' . $token);
                $this->assertEquals($headers['X-GPGAuth-Error'], 'true', 'There should be an error header set to true for token:' . $token);
                $this->assertTrue(isset($headers['X-GPGAuth-Debug']), 'A debug message should be set in the headers');
                $this->assertFalse(
                    strpos($headers['X-GPGAuth-Debug'], 'Invalid verify token format') === false,
                    'The debug message should contain "Invalid verify token format"'
                );
            } else {
                $this->assertTrue(isset($headers['X-GPGAuth-Verify-Response']), 'The verify response header should be set for ' . $token);
                $this->assertEquals(
                    $headers['X-GPGAuth-Verify-Response'],
                    $token,
                    'The verify response header should match the original token. It is ' . $headers['X-GPGAuth-Verify-Response'] . ' instead of ' . $token
                );
            }
        }
    }

    /**
     * Stage 0. Verify server key is incorrect or changed
     */
    public function testStage0WrongServerKey()
    {
        $this->_gpgSetup();
        $uuid = UuidFactory::uuid();

        // Use betty public key instead of server
        $wrongPublicKey = PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'betty_public.key';
        $keyInfo = $this->_gpg->import(file_get_contents($wrongPublicKey));
        $this->serverKeyId = $keyInfo['fingerprint'];
        $token = 'gpgauthv1.3.0|36|' . $uuid . '|gpgauthv1.3.0';
        $this->_gpg->addencryptkey($this->serverKeyId);
        $this->_gpg->addsignkey($this->adaKeyId);
        $msg = $this->_gpg->encrypt($token);

        $this->post('/auth/verify.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->adaKeyId,
                    'server_verify_token' => $msg
                ]
            ]
        ]);
        $headers = $this->getHeaders();
        $this->assertTrue(isset($headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
        $this->assertEquals($headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
        $this->assertTrue(isset($headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
        $this->assertEquals($headers['X-GPGAuth-Progress'], 'stage0', 'The progress indicator should be set to stage1');
        $this->assertTrue(isset($headers['X-GPGAuth-Debug']), 'A debug message should be set in the headers');
        $this->assertEquals($headers['X-GPGAuth-Debug'], 'Decryption failed.');
    }

    /**
     * Stage 1. Authenticate user
     */
    public function testStage1UserToken()
    {
        $this->_gpgSetup();
        $this->post('/auth/login', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->adaKeyId, // Ada's key
                ]
            ]
        ]);

        // check headers
        $headers = $this->getHeaders();
        $this->assertTrue(isset($headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
        $this->assertEquals($headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
        $this->assertTrue(isset($headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
        $this->assertEquals($headers['X-GPGAuth-Progress'], 'stage1', 'The progress indicator should be set to stage1');
        $this->assertTrue(isset($headers['X-GPGAuth-User-Auth-Token']), 'User authentication token should be set');

        // try to decrypt the message
        $this->assertTrue(
            $this->_gpg->adddecryptkey($this->adaKeyId, ''),
            'CONFIG - It is not possible to use the key provided in the fixtures to decrypt.'
        );
        $msg = (stripslashes(urldecode($headers['X-GPGAuth-User-Auth-Token'])));
        $plaintext = '';
        $info = $this->_gpg->decryptverify($msg, $plaintext);
        $this->assertFalse(($info === false), 'Could not decrypt the server generated User Auth Token: ' . $msg);
        $this->assertFalse(($plaintext === ''), 'Could not decrypt the server generated User Auth Token: ' . $msg);
        $this->assertEquals(
            strtoupper($info[0]['fingerprint']),
            strtoupper(Configure::read('passbolt.gpg.serverKey.fingerprint')),
            'Server signature is not matching known fingerprint'
        );

        // Decrypt and check if the token is in the right format
        $info = explode('|', $plaintext);
        $this->assertTrue((count($info) == 4), 'Decrypted User Auth Token: sections missing or wrong delimiters: ' . $plaintext);
        list($version, $length, $uuid, $version2) = $info;
        $this->assertTrue($version == $version2, 'Decrypted User Auth Token: version numbers don\'t match: ' . $plaintext);
        $this->assertTrue($version == 'gpgauthv1.3.0', 'Decrypted User Auth Token: wrong version number: ' . $plaintext);
        $this->assertTrue($version == Validation::uuid($uuid), 'Decrypted User Auth Token: not a UUID: ' . $plaintext);
        $this->assertTrue($length == 36, 'Decrypted User Auth Token: wrong token data length');

        // Check if there is a valid AuthToken in store
        $AuthToken = TableRegistry::get('AuthenticationTokens');
        $isValid = $AuthToken->isValid($uuid, UuidFactory::uuid('user.id.ada'));
        $this->assertTrue($isValid, 'There should a valid auth token');

        // Send it back!
        $this->post('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->adaKeyId, // Ada's key
                    'user_token_result' => $plaintext
                ]
            ]
        ]);

        $headers = $this->getHeaders();
        if (isset($headers['X-GPGAuth-Debug'])) {
            $this->assertTrue(false, 'There should be no debug header set to true for token: ' .
                $uuid . '. Debug: ' . $headers['X-GPGAuth-Debug']);
        }

        $this->assertFalse(isset($headers['X-GPGAuth-Error']), 'There should not be an error header for token: ' . $uuid);
        $this->assertTrue(isset($headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
        $this->assertEquals($headers['X-GPGAuth-Authenticated'], 'true', 'The user should be authenticated at that point');
        $this->assertTrue(isset($headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
        $this->assertEquals($headers['X-GPGAuth-Progress'], 'complete', 'The progress indicator should be set to complete');

        // Authentication token should be disabled at that stage
        $isValid = $AuthToken->isValid($uuid, UuidFactory::uuid('user.id.ada'));
        $this->assertFalse($isValid, 'There should not be a valid auth token');
    }

    // ====== UTILITIES =========================================================

    /**
     * Setup GPG and import the keys to be used in the tests
     * @param string $name ada by default
     */
    protected function _gpgSetup()
    {
        // Make sure the keys are in the keyring
        // if needed we add them for later use in the tests
        $this->_gpg = new \gnupg();
        $this->_gpg->seterrormode(\gnupg::ERROR_EXCEPTION);

        // Import the server key.
        $keyInfo = $this->_gpg->import(file_get_contents(Configure::read('passbolt.gpg.serverKey.private')));
        $this->serverKeyId = $keyInfo['fingerprint'];

        // Import the key of ada.
        $keyInfo = $this->_gpg->import(file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'ada_private_nopassphrase.key'));
        $this->adaKeyId = $keyInfo['fingerprint'];
    }

    protected function getHeaders()
    {
        $headers = $this->_response->getHeaders();
        $final = [];
        foreach ($headers as $key => $header) {
            $final[$key] = $header[0];
        }

        return $final;
    }
}
