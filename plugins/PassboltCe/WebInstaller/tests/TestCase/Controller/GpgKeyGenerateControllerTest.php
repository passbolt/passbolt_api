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
namespace Passbolt\WebInstaller\Test\TestCase\Controller;

use App\Test\Lib\Model\GpgkeysModelTrait;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class GpgKeyGenerateControllerTest extends WebInstallerIntegrationTestCase
{
    use GpgkeysModelTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerGpgKeyGenerateViewSuccess()
    {
        $this->get('/install/gpg_key');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Create a new OpenPGP key for your server', $data);
    }

    public function testWebInstallerGpgKeyGeneratePostSuccess()
    {
        $postData = $this->getDummyGpgkey();
        $this->post('/install/gpg_key', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('install/options');
        $this->assertSession($postData, 'webinstaller.gpg');
    }

    public function testWebInstallerGpgKeyGeneratePostError_InvalidData()
    {
        $postData = $this->getDummyGpgkey([
            'fingerprint' => '2FC8945833C51946E937F9FED47B0811573EE67E',
        ]);
        $this->post('/install/gpg_key', $postData);
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('The data entered are not correct', $data);
    }
}
