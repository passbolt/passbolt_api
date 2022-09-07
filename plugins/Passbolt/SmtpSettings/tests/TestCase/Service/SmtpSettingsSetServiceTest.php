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
use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\SmtpSettingsSetService;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsSetService
 */
class SmtpSettingsSetServiceTest extends TestCase
{
    use GpgAdaSetupTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SmtpSettingsSetService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsSetService(
            UserFactory::make()->admin()->nonPersistedUAC()
        );
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    /**
     * @dataProvider data_For_testSmtpSettingsSetServiceTest_Valid
     */
    public function testSmtpSettingsSetServiceTest_Valid(?string $validField = null, $value = null)
    {
        $this->gpgSetup();
        $data = $this->getSmtpSettingsData($validField, $value);

        $settings = $this->service->saveSettings($data);

        $this->assertInstanceOf(OrganizationSetting::class, $settings);
        $this->assertSame(1, SmtpSettingFactory::count());
    }

    /**
     * @dataProvider data_For_testSmtpSettingsSetServiceTest_Invalid
     */
    public function testSmtpSettingsSetServiceTest_Invalid(string $failingField, $value)
    {
        $data = $this->getSmtpSettingsData($failingField, $value);

        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('Could not validate the smtp settings.');
        $this->service->saveSettings($data);
    }

    public function data_For_testSmtpSettingsSetServiceTest_Valid(): array
    {
        return [
            [],
            ['username', null],
            ['password', null],
            ['tls', null],
            ['tls', 1],
            ['tls', '1'],
            ['port', '123'],
        ];
    }

    public function data_For_testSmtpSettingsSetServiceTest_Invalid(): array
    {
        return [
            ['sender_name', ''],
            ['sender_email', 'foo'],
            ['sender_email', ''],
            ['host', ''],
            ['tls', 'true'],
            ['tls', false],
            ['tls', 0],
            ['tls', '0'],
            ['port', 'abc'],
            ['port', 0],
            ['port', 1.2],
        ];
    }
}
