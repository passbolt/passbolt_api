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

use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class OptionsControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerOptionViewSuccess()
    {
        $this->get('/install/options');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Options', $data);
    }

    public function testWebInstallerOptionPostSuccess()
    {
        $postData = [
            'full_base_url' => 'http://passbolt.dev/',
            'force_ssl' => 0,
        ];
        $this->post('/install/options', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/install/email');

        // The full base url last / should be trimed.
        //$expectedSessionSettings = $postData;
        //$expectedSessionSettings['full_base_url'] = 'http://passbolt.dev';
        //$this->assertSession($expectedSessionSettings, 'webinstaller.options');
    }

    public function testWebInstallerOptionPostSuccess_AdminAlreadyExists()
    {
        $this->session(['webinstaller' => ['initialized' => true, 'hasAdmin' => true]]);
        $postData = [
            'full_base_url' => 'http://passbolt.dev',
            'force_ssl' => 0,
        ];
        $this->post('/install/options', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/install/email');
    }

    public function testWebInstallerOptionPostError_InvalidData()
    {
        $postData = [
            'full_base_url' => 'http://passbolt.dev',
            'force_ssl' => 'not-a-boolean',
        ];
        $this->post('/install/options', $postData);
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('The data entered are not correct', $data);
        $this->assertSession(null, 'webinstaller.options');
    }
}
