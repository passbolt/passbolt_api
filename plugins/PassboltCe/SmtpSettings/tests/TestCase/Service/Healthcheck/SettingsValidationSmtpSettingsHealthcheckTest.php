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
 * @since         4.7.0
 */

namespace Passbolt\SmtpSettings\Test\TestCase\Service\Healthcheck;

use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\Healthcheck\SettingsValidationSmtpSettingsHealthcheck;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsHealthcheckService
 */
class SettingsValidationSmtpSettingsHealthcheckTest extends TestCase
{
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    protected SettingsValidationSmtpSettingsHealthcheck $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SettingsValidationSmtpSettingsHealthcheck($this->dummyPassboltFile);
    }

    public function tearDown(): void
    {
        unset($this->service);
        $this->deletePassboltDummyFile();
        parent::tearDown();
    }

    public function testSettingsValidationSmtpSettings_Valid_DB()
    {
        $data = $this->getSmtpSettingsData();
        $this->encryptAndPersistSmtpSettings($data);

        $this->service->check();
        $this->assertTrue($this->service->isPassed());
    }

    public function testSettingsValidationSmtpSettings_Invalid_DB()
    {
        $data = $this->getSmtpSettingsData('port', 0);
        $this->encryptAndPersistSmtpSettings($data);

        $this->service->check();
        $this->assertFalse($this->service->isPassed());
        $this->assertSame(
            '{"port":{"range":"The port number should be between 1 and 65535."}}',
            $this->service->getValidationError()
        );
    }

    public function testSettingsValidationSmtpSettings_Decryption_Error()
    {
        // Invalid settings
        SmtpSettingFactory::make()->persist();

        $this->service->check();
        $this->assertFalse($this->service->isPassed());
        $this->assertSame(
            'The OpenPGP server key cannot be used to decrypt the SMTP settings stored in database. To fix this problem, you need to configure the SMTP server again. Decryption failed. decrypt failed',
            $this->service->getValidationError()
        );
    }
}
