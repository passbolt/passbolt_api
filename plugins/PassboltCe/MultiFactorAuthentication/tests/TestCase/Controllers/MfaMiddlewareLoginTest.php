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
 * @since         2.5.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Controllers;

use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use Cake\Core\Configure;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;
use Passbolt\MultiFactorAuthentication\Test\Lib\MfaIntegrationTestCase;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Totp\MfaTotpScenario;

class MfaMiddlewareLoginTest extends MfaIntegrationTestCase
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
        $this->enableFeaturePlugin('JwtAuthentication');
    }

    /**
     * Regression test
     * POST /auth/login with correct data should return 200 even when MFA is required
     *
     * @group mfa
     * @group mfaMiddleware
     */
    public function testMfaMiddlewareLoginSuccess200()
    {
        $adaId = 'f848277c-5398-58f8-a82a-72397af2d450';
        $user = UserFactory::get($adaId, ['contain' => 'Roles']);
        $this->loadFixtureScenario(MfaTotpScenario::class, $user);
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
        $msg = stripslashes(urldecode($headers['X-GPGAuth-User-Auth-Token']));
        $this->gpg->setVerifyKeyFromFingerprint(Configure::read('passbolt.gpg.serverKey.fingerprint'));
        $this->gpg->setDecryptKeyFromFingerprint($this->adaKeyId, '');
        $plaintext = $this->gpg->decrypt($msg, true);
        $this->assertFalse(!$plaintext, 'Could not decrypt the server generated User Auth Token: ' . $msg);

        // Decrypt and check if the token is in the right format
        $info = explode('|', $plaintext);
        [$version, $length, $uuid, $version2] = $info;

        // Send it back!
        $this->postJson('/auth/login.json', [
            'data' => [
                'gpg_auth' => [
                    'keyid' => $this->adaKeyId, // Ada's key
                    'user_token_result' => $plaintext,
                ],
            ],
        ]);

        $this->assertResponseCode(200);
        $headers = $this->getHeaders();
        $this->assertTrue(isset($headers['X-GPGAuth-Authenticated']), 'Authentication headers should be set');
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
        $key = Configure::read('passbolt.gpg.serverKey.private');
        $this->serverKeyId = $this->gpg->importKeyIntoKeyring(file_get_contents($key));
        $this->gpg->importKeyIntoKeyring(file_get_contents(Configure::read('passbolt.gpg.serverKey.public')));

        // Import the key of ada.
        $key = FIXTURES . DS . 'Gpgkeys' . DS . 'ada_private_nopassphrase.key';
        $this->adaKeyId = $this->gpg->importKeyIntoKeyring(file_get_contents($key));
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
