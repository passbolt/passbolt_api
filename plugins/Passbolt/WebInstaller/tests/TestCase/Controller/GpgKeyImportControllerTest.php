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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Controller;

use App\Utility\Healthchecks;
use Cake\Core\Configure;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class GpgKeyImportControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerGpgKeyImportViewSuccess()
    {
        $this->get('/install/gpg_key_import');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Copy paste the private key below', $data);
    }

    public function testWebInstallerGpgKeyImportPostSuccess()
    {
        $postData = [
            'armored_key' => file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'server_prod_unsecure_private.key')
        ];
        $this->post('/install/gpg_key_import', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('install/email');
        $this->assertSession($postData, 'webinstaller.gpg');
    }

    public function testWebInstallerGpgKeyImportPostError_InvalidData()
    {
        $postData = [
            'armored_key' => 'invalid-key'
        ];
        $this->post('/install/gpg_key_import', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The key is not valid', $data);
        $this->assertContains('The key is not a valid private key', $data);
    }

    public function testWebInstallerGpgKeyImportPostError_PublicKey()
    {
        $postData = [
            'armored_key' => file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'server_prod_unsecure_public.key')
        ];
        $this->post('/install/gpg_key_import', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The key is not valid', $data);
        $this->assertContains('The key is not a valid private key', $data);
    }

    public function testWebInstallerGpgKeyImportPostError_PrivateKeyWithExpiryDate()
    {
        $postData = [
            'armored_key' => file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'ada_private.key')
        ];
        $this->post('/install/gpg_key_import', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The key is not valid', $data);
        $this->assertContains('The key cannot have an expiry date', $data);
    }

    public function testWebInstallerGpgKeyImportPostError_PrivateKeyProtectedWithSecret()
    {
        $postData = [
            'armored_key' => file_get_contents(PASSBOLT_TEST_DATA_GPGKEY_PATH . DS . 'server_test_no_expiry_with_secret_private.key')
        ];
        $this->post('/install/gpg_key_import', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The key is not valid', $data);
        $this->assertContains('The key cannot be used to encrypt/decrypt', $data);
    }
}
