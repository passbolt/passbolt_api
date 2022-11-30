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
use Cake\Core\Configure;
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
        $decryptedSettings = $this->decryptSettings($settings);
        foreach ($data as $field => $expectedValue) {
            $this->assertSame($expectedValue, $decryptedSettings[$field]);
        }
    }

    public function testSmtpSettingsSetServiceTest_Valid_TLS_String_True_Should_Map_To_Boolean_True()
    {
        $this->gpgSetup();
        $data = $this->getSmtpSettingsData('tls', 'true');

        $settings = $this->service->saveSettings($data);

        $this->assertInstanceOf(OrganizationSetting::class, $settings);
        $this->assertSame(1, SmtpSettingFactory::count());
        $decryptedSettings = $this->decryptSettings($settings);
        $this->assertSame(true, $decryptedSettings['tls']);
    }

    public function testSmtpSettingsSetServiceTest_Invalid()
    {
        $data = $this->getSmtpSettingsData('port', 'abc');

        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('Could not validate the smtp settings.');
        $this->service->saveSettings($data);
    }

    /**
     * When setting the SMTP settings, the sender email should be valid
     */
    public function testSmtpSettingsSetServiceTest_Sender_Email_Invalid_Should_Fail()
    {
        $data = $this->getSmtpSettingsData('sender_email', 'abc');

        $this->expectException(FormValidationException::class);
        $this->expectExceptionMessage('Could not validate the smtp settings.');
        $this->service->saveSettings($data);
    }

    public function testSmtpSettingsSetServiceTest_Valid_But_Too_Many_Fields()
    {
        $this->gpgSetup();
        $data = $this->getSmtpSettingsData();
        $data['bar'] = 'Foo'; // These settings should be filtered out

        $this->service->saveSettings($data);

        /** @var \App\Model\Entity\OrganizationSetting $settings */
        $settings = SmtpSettingFactory::find()->firstOrFail();
        unset($data['bar']);
        $this->assertSame($data, $this->decryptSettings($settings));
    }

    public function data_For_testSmtpSettingsSetServiceTest_Valid(): array
    {
        return [
            [],
            ['password', 'passwordwith"'],
            ['password', "passwordwith'"],
        ];
    }

    /**
     * @param \App\Model\Entity\OrganizationSetting $settings Org setting in the DB
     * @return array
     */
    private function decryptSettings(OrganizationSetting $settings): array
    {
        $this->gpg->setDecryptKeyFromFingerprint(Configure::read('passbolt.gpg.serverKey.fingerprint'), '');
        $value = $this->gpg->decrypt($settings->get('value'));

        return json_decode($value, true);
    }
}
