<?php
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
use Cake\Core\Configure;
use Cake\TestSuite\IntegrationTestTrait;

class AuthVerifyControllerTest extends AppIntegrationTestCase
{
    use IntegrationTestTrait;

    public $fixtures = ['app.Base/Users', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/AuthenticationTokens'];

    public function testAuthVerifyControllerUserGetSuccess()
    {
        $this->get('/auth/verify.json');
        $data = json_decode($this->_getBodyAsString());
        $this->assertEquals($data->body->fingerprint, Configure::read('passbolt.gpg.serverKey.fingerprint'));
        $this->assertResponseOk();
    }

    /**
     * Test error 500 if config is invalid
     */
    public function testAuthVerifyControllerBadConfig()
    {
        Configure::write('passbolt.gpg.serverKey.public', 'wrong');
        $this->get('/auth/verify.json');
        $this->assertResponseFailure();
        $data = $this->_getBodyAsString();
        $expect = 'The public key for this passbolt instance was not found.';
        $this->assertContains($expect, $data);
    }

    /**
     * Test that the passbolt instance public keys is available in the address provided in the headers
     */
    public function testAuthVerifyControllerGetServerPublicKey()
    {
        // get the server public key
        $this->get('/auth/login');
        $verifyUrl = $this->_response->getHeader('X-GPGAuth-Pubkey-URL')[0];
        $this->get($verifyUrl);
        $result = json_decode($this->_getBodyAsString());
        $this->assertTrue(isset($result->body->fingerprint));
        $this->assertTrue(isset($result->body->keydata));
        $fingerprint = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $this->assertEquals($result->body->fingerprint, $fingerprint);
    }
}
