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

class GpgKeyGenerateControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerGpgKeyGenerateViewSuccess()
    {
        $this->get('/install/gpg_key');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Create a new GPG key for your server', $data);
    }

    public function testWebInstallerGpgKeyGeneratePostSuccess()
    {
        $postData = [
            'name' => 'Aurore Avarguès-Weber',
            'email' => 'aurore@passbolt.com',
            'comment' => 'Bees are everything'
        ];
        $this->post('/install/gpg_key', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('install/email');
        $this->assertSession($postData, 'webinstaller.gpg');
    }

    public function testWebInstallerGpgKeyGeneratePostError_InvalidData()
    {
        $postData = [
            'name' => 'Aurore Avarguès-Weber',
            'email' => 'invalid-email',
            'comment' => 'Bees are everything'
        ];
        $this->post('/install/gpg_key', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The data entered are not correct', $data);
    }
}
