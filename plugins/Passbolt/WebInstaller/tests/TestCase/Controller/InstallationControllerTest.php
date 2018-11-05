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
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Validation\Validation;
use Passbolt\WebInstaller\Controller\WebInstallerController;
use Passbolt\WebInstaller\Test\Lib\WebInstallerIntegrationTestCase;

class InstallationControllerTest extends WebInstallerIntegrationTestCase
{
    // Keep a copy of the passbolt config, to rollback after each test.
    private $passboltConfigOriginal = null;

    public function setUp()
    {
        parent::setUp();
        $this->mockPassboltIsNotconfigured();
        $this->initWebInstallerSession();
        if (file_exists(CONFIG . 'passbolt.php')) {
            $this->passboltConfigOriginal = file_get_contents(CONFIG . 'passbolt.php');
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        if (!empty($this->passboltConfigOriginal)) {
            file_put_contents(CONFIG . 'passbolt.php', $this->passboltConfigOriginal);
        }
        if (file_exists(CONFIG . 'license')) {
            unlink(CONFIG . 'license');
        }
    }

    public function testWebInstallerInstallationViewSuccess()
    {
        $this->get('/install/installation');
        $data = ($this->_getBodyAsString());
        $this->assertResponseOk();
        $this->assertContains('Installing', $data);
    }

    protected function getInstallSessionData()
    {
        $datasourceTest = Configure::read('Testing.Datasources.test');
        $data = [
            'initialized' => true,
            'hasExistingAdmin' => false,
            'database' => $datasourceTest,
            'gpg' => [
                'fingerprint' => '8A0F595329EA607C88FE36D99E6BAB658549B8EA',
                'public' => '/var/www/passbolt/config/gpg/serverkey.asc',
                'private' => '/var/www/passbolt/config/gpg/serverkey_private.asc'
            ],
            'email' => [
                'sender_name' => 'Webinstaller test',
                'sender_email' => 'webinstaller@passbolt.com',
                'host' => 'unreachable_host.dev',
                'tls' => 1,
                'port' => 587,
                'username' => 'webinstaller',
                'password' => 'webinstaller',
            ],
            'options' => [
                'full_base_url' => 'http://127.0.0.1:8081',
                'public_registration' => 0,
                'force_ssl' => 0,
            ],
            'first_user' => [
                'profile' => [
                    'first_name' => 'Web',
                    'last_name' => 'Installer',
                ],
                'username' => 'webinstaller@passbolt.com',
                'deleted' => 0,
                'role_id' => '0d6990c8-4aaa-4456-a333-00e803ba0828',
            ]
        ];
        if (file_exists(PLUGINS . DS . 'Passbolt' . DS . 'License')) {
            $licenseSettings = [
                'license_key' => file_get_contents(PLUGINS . DS . 'Passbolt' . DS . 'License' . DS . 'tests' . DS . 'data' . DS . 'license' . DS . 'license_dev')
            ];
            $data += $licenseSettings;
        }

        return $data;
    }

    public function testWebInstallerInstallationDoInstallSuccess()
    {
        $this->truncateTables();
        $config = $this->getInstallSessionData();
        $this->initWebInstallerSession($config);
        $this->get('/install/installation/do_install.json');
        $result = json_decode($this->_getBodyAsString(), true);

        $this->assertTrue(Validation::uuid($result['user_id']));
        $this->assertTrue(Validation::uuid($result['token']));
    }
}
