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

use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class AccountCreationControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession(['database' => $this->getTestDatasourceFromConfig()]);
    }

    public function testWebInstallerAccountCreationViewSuccess()
    {
        $this->get('/install/account_creation');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Admin user details', $data);
    }

    public function testWebInstallerAccountCreationPostSuccess()
    {
        $postData = [
            'username' => 'aurore@passbolt.com',
            'first_name' => 'Aurore',
            'last_name' => 'Avarguès-Weber'
        ];
        $this->post('/install/account_creation', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('install/installation');

        $expectedData = [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber'
            ]
        ];
        $this->assertSession($expectedData, 'webinstaller.first_user');
    }

    public function testWebInstallerAccountCreationPostError_InvalidData()
    {
        $postData = [
            'username' => 'invalid-email',
            'first_name' => 'Aurore',
            'last_name' => 'Avarguès-Weber'
        ];
        $this->post('/install/account_creation', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The data entered are not correct', $data);
    }
}
