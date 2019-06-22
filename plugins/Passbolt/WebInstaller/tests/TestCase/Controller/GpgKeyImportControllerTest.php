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
 * @since         2.5.0
 */
namespace Passbolt\WebInstaller\Test\TestCase\Controller;

use App\Test\Lib\Model\GpgkeysModelTrait;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class GpgKeyImportControllerTest extends WebInstallerIntegrationTestCase
{
    use GpgkeysModelTrait;

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
        $postData = $this->getDummyGpgkey();
        $this->post('/install/gpg_key_import', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('install/email');
        $this->assertSession($postData, 'webinstaller.gpg');
    }

    public function testWebInstallerGpgKeyImportPostError_InvalidData()
    {
        $postData = $this->getDummyGpgkey([
            'fingerprint' => '2FC8945833C51946E937F9FED47B0811573EE67E'
        ]);
        $this->post('/install/gpg_key_import', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The data entered are not correct', $data);
    }

    public function testWebInstallerGpgKeyImportPostError_PublicKey()
    {
        $postData = $this->getDummyGpgkey([
            'public_key_armored' => $this->getDummyGpgkey()['private_key_armored']
        ]);
        $this->post('/install/gpg_key_import', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The data entered are not correct', $data);
        $this->assertContains('The key is not a valid public key', $data);
    }
}
