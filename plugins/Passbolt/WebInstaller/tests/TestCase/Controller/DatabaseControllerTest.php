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

class DatabaseControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function testWebInstallerDatabaseViewSuccess()
    {
        $this->get('/install/database');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Database configuration', $data);
    }

    public function testWebInstallerDatabasePostSuccess()
    {
        $this->truncateTables();
        $postData = Configure::read('Testing.Datasources.test');
        $this->post('/install/database', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/install/gpg_key');
        $this->assertSession($postData, 'webinstaller.database');
        $this->assertSession(false, 'webinstaller.hasAdmin');
    }

    public function testWebInstallerDatabasePostError_InvalidData()
    {
        $postData = Configure::read('Testing.Datasources.test');
        $postData['port'] = 'invalid-port';
        $this->post('/install/database', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('The data entered are not correct', $data);
    }

    public function testWebInstallerDatabasePostError_CannotConnectToTheDatabase()
    {
        $postData = Configure::read('Testing.Datasources.test');
        $postData['username'] = 'invalid-username';
        $this->post('/install/database', $postData);
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('A connection could not be established with the credentials provided. Please verify the settings.', $data);
    }
}
