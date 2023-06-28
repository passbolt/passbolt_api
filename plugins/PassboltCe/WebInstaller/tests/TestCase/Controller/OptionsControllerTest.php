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

use Cake\Core\Configure;
use Cake\Routing\Router;
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
        $html = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Options', $html);
        $this->assertStringContainsString('<option value="0" selected="selected">', $html);
    }

    /**
     * SSL force dropdown option should be set to true if webinstaller is launched over https.
     *
     * @return void
     */
    public function testWebInstallerOptionViewOverHttps_Success()
    {
        Configure::write('App.fullBaseUrl', 'https://passbolt.local');

        $this->get(Router::url('/install/options', true));

        $html = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Options', $html);
        $this->assertStringContainsString('<option value="1" selected="selected">', $html);
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
