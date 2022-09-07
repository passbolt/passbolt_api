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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Service;

use App\Error\Exception\FormValidationException;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\Mailer\TransportFactory;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetService;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsSetService
 */
class SmtpSettingsGetServiceTest extends TestCase
{
    use GpgAdaSetupTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SmtpSettingsGetService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsGetService();
        $this->loadPlugins(['Passbolt/SmtpSettings' => []]);
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testSmtpSettingsGetServiceTest_Valid_File_Source()
    {
        TransportFactory::get('default')->setConfig([
            'host' => 'some test host',
            'tls' => true,
            'port' => '25',
            'username' => 'user',
            'password' => 'secret',
        ]);

        try {
            $settings = $this->service->getSettings();
        } catch (FormValidationException $e) {
            $this->fail($e->getMessage() . ' ' . json_encode($e->getErrors()));
        }

        $this->assertSettingsHaveTheRightKeys($settings);
        $this->assertSame('file', $settings['source']);
    }

    public function testSmtpSettingsGetServiceTest_Valid_DB_Source()
    {
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $encryptedSettings = $this->gpg->encrypt(json_encode($this->getSmtpSettingsData()));
        SmtpSettingFactory::make()->value($encryptedSettings)->persist();

        $settings = $this->service->getSettings();

        $this->assertSettingsHaveTheRightKeys($settings);
        $this->assertSame('db', $settings['source']);
    }

    private function assertSettingsHaveTheRightKeys(array $settings)
    {
        $expectedKeys = [
            'source', 'host', 'port', 'username', 'password', 'tls', 'sender_email', 'sender_name',
        ];
        foreach ($expectedKeys as $key) {
            $this->assertArrayHasKey($key, $settings, "The key $key is not returned in the settings");
        }
    }
}
