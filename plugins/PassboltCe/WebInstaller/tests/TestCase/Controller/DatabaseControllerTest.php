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

class DatabaseControllerTest extends WebInstallerIntegrationTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->dropAllTables();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
    }

    public function tearDown(): void
    {
        $this->restoreTestConnection();
        parent::tearDown();
    }

    public function testDatabaseControllerViewSuccess()
    {
        $this->get('/install/database');
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('Database configuration', $data);
        $this->assertStringContainsString('Database connection url', $data);
        $this->assertStringContainsString('Username', $data);
        $this->assertStringContainsString('Password', $data);
        $this->assertStringContainsString('Database name', $data);
        $this->assertStringContainsString('Schema', $data);
    }

    /**
     * Data passed by the user during the DB setup
     *
     * @return array
     */
    private function postData(): array
    {
        $dataSource = $this->getTestDatasourceFromConfig();

        return [
            'host' => $dataSource['host'],
            'port' => $dataSource['port'],
            'username' => $dataSource['username'],
            'password' => $dataSource['password'],
            'database' => $dataSource['database'],
        ];
    }

    public function testDatabaseControllerPostSuccess()
    {
        $postData = $this->getTestDatasourceFromConfig();
        $this->post('/install/database', $postData);
        $this->assertResponseCode(302);
        $this->assertRedirectContains('/install/gpg_key');
        // Not testable on redirect
        // $this->assertSession($postData, 'webinstaller.database');
        // $this->assertSession(false, 'webinstaller.hasAdmin');
    }

    public function testDatabaseControllerPostError_InvalidData()
    {
        $postData = $this->postData();
        $postData['port'] = 'invalid-port';
        $this->post('/install/database', $postData);
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('The data entered are not correct', $data);
    }

    public function testDatabaseControllerPostError_CannotConnectToTheDatabase()
    {
        // This breaks further test
        // Sessions is carried over to next test...
        $postData = $this->getTestDatasourceFromConfig();
        $postData['username'] = 'invalid-username';
        $this->post('/install/database', $postData);
        $data = $this->_getBodyAsString();
        $this->assertResponseOk();
        $this->assertStringContainsString('A connection could not be established with the credentials provided. Please verify the settings.', $data);
    }
}
