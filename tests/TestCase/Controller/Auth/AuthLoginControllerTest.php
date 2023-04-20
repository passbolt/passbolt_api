<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Auth;

use App\Test\Lib\AppIntegrationTestCase;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;
use Passbolt\Log\Test\Factory\ActionLogFactory;

class AuthLoginControllerTest extends AppIntegrationTestCase
{
    public $fixtures = [
        'app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles',
        'app.Base/Gpgkeys', 'app.Base/GroupsUsers',
    ];
    public $keyid;

    /**
     * @var \App\Utility\OpenPGP\OpenPGPBackend $gpg
     */
    public $gpg;

    // Keys ids used in this test. Set in _gpgSetup.
    protected $adaKeyId;
    protected $serverKeyId;

    public function setUp(): void
    {
        parent::setUp();
        $jwtKeyPairService = new JwtKeyPairService();
        $jwtKeyPairService->createKeyPair();
        $this->enableFeaturePlugin('JwtAuthentication');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('JwtAuthentication');
    }

    /**
     * Test getting login started with deleted account
     */
    public function testAuthLoginControllerUserLoginAsDeletedUserError()
    {
        $this->postJson('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => '252B91CB28A96C6D67E8FC139020576F08D8B763',
                ],
            ],
        ]);
        $msg = 'There is no user associated with this key. User not found.';
        $this->assertHeader('X-GPGAuth-Debug', $msg);
    }

    /**
     * Test error 500 if the GnuPG fingerprint config for the server is missing.
     * It can happen if a sysop overrides the GnuPG config for the server post installation.
     */
    public function testAuthLoginControllerLoginServerKeyFingerprintMissing()
    {
        // Disable this plugin in this test as long as the cookie pepper is the fingerprint.
        $this->disableFeaturePlugin('JwtAuthentication');

        Configure::delete('passbolt.gpg.serverKey.fingerprint');
        $this->postJson('/auth/login.json');
        $this->assertResponseCode(500);
        $this->assertResponseFailure('The GnuPG config for the server is not available or incomplete.');
    }

    /**
     * Test error 500 if the GnuPG fingerprint config for the server is invalid.
     * It can happen if a sysop changed the server key fingerprint without loading this key in the OpenPGP keyring post installation.
     */
    public function testAuthLoginControllerLoginBadServerKeyFingerprint()
    {
        $fingerprint = '0000000000000000000000000000000000000000';
        Configure::write('passbolt.gpg.serverKey.fingerprint', $fingerprint);
        $this->postJson('/auth/login.json');
        $this->assertResponseCode(500);
        $this->assertResponseFailure('The OpenPGP server key defined in the config cannot be used to decrypt. There is an issue with the OpenPGP server key. The fingerprint does not match the one associated with the key on file.');
    }

    /**
     * Check that GPGAuth headers are set everywhere
     * Check that the action is not persisted in the action logs
     */
    public function testAuthLoginControllerGetHeaders()
    {
        $isLogEnabled = $this->isFeaturePluginEnabled('Log');
        $this->enableFeaturePlugin('Log');

        $this->get('/auth/login');
        $this->assertResponseOk();
        $this->assertHeader('X-GPGAuth-Version', '1.3.0');
        $this->assertHeader('X-GPGAuth-Verify-URL', '/auth/verify');
        $this->assertHeader('X-GPGAuth-Pubkey-URL', '/auth/verify.json');
        $this->assertHeader('X-GPGAuth-Login-URL', '/auth/login');
        $this->assertHeader('X-GPGAuth-Logout-URL', '/auth/logout');

        $this->assertSame(0, ActionLogFactory::count());
        if (!$isLogEnabled) {
            $this->disableFeaturePlugin('Log');
        }
    }

    /**
     * Check that GET /auth/login.json triggers a not found error.
     */
    public function testAuthLoginControllerGetJson()
    {
        $this->getJson('/auth/login.json');
        $this->assertResponseError('Page not found.');
        $this->assertResponseCode(404);
    }

    /**
     * Check that GPGAuth headers are set everywhere
     */
    public function testAuthLoginControllerGetHeadersPost()
    {
        $this->postJson('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => 'testid',
                ],
            ],
        ]);
        $this->assertHeader('X-GPGAuth-Progress', 'stage0');
    }

    /**
     * Test authentication with wrong user key fingerprint
     */
    public function testAuthLoginControllerAllStagesFingerprint()
    {
        $this->gpgSetup(); // add ada's keys
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
            $this->postJson('/auth/login.json', [
                'data' => [
                    'gpg_auth' => [
                        'keyid' => $keyid,
                    ],
                ],
            ]);
            $headers = $this->getHeaders();
            $this->assertTrue(isset($headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set for keyid:"' . $keyid . '"');
            $this->assertEquals($headers['X-GPGAuth-Authenticated'], 'false', 'The user should not be authenticated at that point');
            $this->assertTrue(isset($headers['X-GPGAuth-Progress']), 'The progress indicator should be set in the headers');
            $this->assertNotEquals($headers['X-GPGAuth-Progress'], 'stage2', 'The progress indicator should not be stage 2');
            $this->assertNotEquals($headers['X-GPGAuth-Progress'], 'complete', 'The progress indicator should not be stage 2');

            if ($expectSuccess) {
                $msg = isset($headers['X-GPGAuth-Debug']) ? $headers['X-GPGAuth-Debug'][0] . ': ' . $keyid :
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
    public function testAuthLoginControllerStage0MessageFormat()
    {
        $this->gpgSetup();
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
            'gpgauthv1.3.0|36|' . $uuid . '|gpgauthv1.3.0' => true, // right
        ];

        foreach ($fix as $token => $expectSuccess) {
            $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
            $this->gpg->setSignKeyFromFingerprint($this->adaKeyId, '');
            $msg = $this->gpg->encryptSign($token);
            $this->postJson('/auth/verify.json', [
                'data' => [
                    'gpg_auth' => [
                        'keyid' => $this->adaKeyId, // Ada's key
                        'server_verify_token' => $msg,
                    ],
                ],
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
                    'The debug message should contain "Invalid verify token format" got: ' . $headers['X-GPGAuth-Debug']
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
    public function testAuthLoginControllerStage0WrongServerKey()
    {
        $this->gpgSetup();
        $uuid = UuidFactory::uuid();

        // Use betty public key instead of server
        $wrongPublicKey = FIXTURES . DS . 'Gpgkeys' . DS . 'betty_public.key';
        $this->serverKeyId = $this->gpg->importKeyIntoKeyring(file_get_contents($wrongPublicKey));
        $token = 'gpgauthv1.3.0|36|' . $uuid . '|gpgauthv1.3.0';
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $msg = $this->gpg->encrypt($token);

        $this->post('/auth/verify.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->adaKeyId,
                    'server_verify_token' => $msg,
                ],
            ],
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
    public function testAuthLoginControllerStage1UserToken()
    {
        $this->gpgSetup();
        $this->postJson('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->adaKeyId, // Ada's key
                ],
            ],
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
            $this->gpg->setDecryptKeyFromFingerprint($this->adaKeyId, ''),
            'CONFIG - It is not possible to use the key provided in the fixtures to decrypt.'
        );
        $msg = stripslashes(urldecode($headers['X-GPGAuth-User-Auth-Token']));
        $signatureInfo = [];
        $this->gpg->setVerifyKeyFromFingerprint(Configure::read('passbolt.gpg.serverKey.fingerprint'));
        $plaintext = $this->gpg->decrypt($msg, true);
        $this->assertFalse(($plaintext === false), 'Could not decrypt the server generated User Auth Token: ' . $msg);

        // Decrypt and check if the token is in the right format
        $info = explode('|', $plaintext);
        $this->assertTrue((count($info) == 4), 'Decrypted User Auth Token: sections missing or wrong delimiters: ' . $plaintext);
        [$version, $length, $uuid, $version2] = $info;
        $this->assertTrue($version == $version2, 'Decrypted User Auth Token: version numbers don\'t match: ' . $plaintext);
        $this->assertTrue($version == 'gpgauthv1.3.0', 'Decrypted User Auth Token: wrong version number: ' . $plaintext);
        $this->assertTrue($version == Validation::uuid($uuid), 'Decrypted User Auth Token: not a UUID: ' . $plaintext);
        $this->assertTrue($length == 36, 'Decrypted User Auth Token: wrong token data length');

        // Check if there is a valid AuthToken in store
        $AuthToken = TableRegistry::getTableLocator()->get('AuthenticationTokens');
        $isValid = $AuthToken->isValid($uuid, UuidFactory::uuid('user.id.ada'));
        $this->assertTrue($isValid, 'There should a valid auth token');

        // Send it back!
        $this->postJson('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->adaKeyId, // Ada's key
                    'user_token_result' => $plaintext,
                ],
            ],
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
     *
     * @param string $name ada by default
     */
    protected function gpgSetup()
    {
        // Make sure the keys are in the keyring
        // if needed we add them for later use in the tests
        if (Configure::read('passbolt.gpg.putenv')) {
            putenv('GNUPGHOME=' . Configure::read('passbolt.gpg.keyring'));
        }

        $this->gpg = OpenPGPBackendFactory::get();
        $this->gpg->clearKeys();

        // Import the server key.
        $this->serverKeyId = $this->gpg->importKeyIntoKeyring(file_get_contents(Configure::read('passbolt.gpg.serverKey.private')));
        $this->gpg->importKeyIntoKeyring(file_get_contents(Configure::read('passbolt.gpg.serverKey.public')));

        // Import the key of ada.
        $this->adaKeyId = $this->gpg->importKeyIntoKeyring(file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key'));
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
