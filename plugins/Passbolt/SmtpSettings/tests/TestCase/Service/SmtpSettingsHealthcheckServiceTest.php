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

use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\SmtpSettingsHealthcheckService;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Service\SmtpSettingsHealthcheckService
 */
class SmtpSettingsHealthcheckServiceTest extends TestCase
{
    use GpgAdaSetupTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    /**
     * @var SmtpSettingsHealthcheckService
     */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsHealthcheckService($this->dummyPassboltFile);
    }

    public function tearDown(): void
    {
        unset($this->service);
        $this->deletePassboltDummyFile();
        parent::tearDown();
    }

    public function testSmtpSettingsHealthcheckServiceTest_Valid_DB()
    {
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $encryptedSettings = $this->gpg->encrypt(json_encode($this->getSmtpSettingsData()));
        SmtpSettingFactory::make()->value($encryptedSettings)->persist();
        $otherChecks = ['foo' => 'bar'];

        $checks = $this->service->check($otherChecks);
        $expected = $otherChecks + ['smtpSettings' => [
            'isEnabled' => true,
            'errorMessage' => false,
            'source' => 'database',
            'isInDb' => true,
        ],];

        $this->assertSame($expected, $checks);
    }

    public function testSmtpSettingsHealthcheckServiceTest_Invalid_DB()
    {
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $encryptedSettings = $this->gpg->encrypt(json_encode($this->getSmtpSettingsData('port', 0)));
        SmtpSettingFactory::make()->value($encryptedSettings)->persist();
        $otherChecks = ['foo' => 'bar'];

        $checks = $this->service->check($otherChecks);
        $expected = $otherChecks + ['smtpSettings' => [
            'isEnabled' => true,
            'errorMessage' => '{"port":{"range":"The port number should be between 1 and 65535."}}',
            'source' => 'database',
            'isInDb' => true,
        ],];

        $this->assertSame($expected, $checks);
    }

    public function testSmtpSettingsHealthcheckServiceTest_Valid_File()
    {
        $this->setTransportConfig();
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $checks = $this->service->check();
        $expected = ['smtpSettings' => [
            'isEnabled' => true,
            'errorMessage' => false,
            'source' => CONFIG . 'passbolt.php',
            'isInDb' => false,
        ],];

        $this->assertSame($expected, $checks);
    }

    public function testSmtpSettingsHealthcheckServiceTest_Invalid_File()
    {
        $this->setTransportConfig('port', 0);
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $checks = $this->service->check();
        $expected = ['smtpSettings' => [
            'isEnabled' => true,
            'errorMessage' => '{"port":{"range":"The port number should be between 1 and 65535."}}',
            'source' => CONFIG . 'passbolt.php',
            'isInDb' => false,
        ],];

        $this->assertSame($expected, $checks);
    }

    public function testSmtpSettingsHealthcheckServiceTest_Valid_Env()
    {
        $this->setTransportConfig();

        $checks = $this->service->check();
        $expected = ['smtpSettings' => [
            'isEnabled' => true,
            'errorMessage' => false,
            'source' => 'env variables',
            'isInDb' => false,
        ],];

        $this->assertSame($expected, $checks);
    }

    public function testSmtpSettingsHealthcheckServiceTest_Invalid_Env()
    {
        $this->setTransportConfig('port', 0);

        $checks = $this->service->check();
        $expected = ['smtpSettings' => [
            'isEnabled' => true,
            'errorMessage' => '{"port":{"range":"The port number should be between 1 and 65535."}}',
            'source' => 'env variables',
            'isInDb' => false,
        ],];

        $this->assertSame($expected, $checks);
    }

    public function testSmtpSettingsHealthcheckServiceTest_Decryption_Error()
    {
        SmtpSettingFactory::make()->value(['foo' => 'bar'])->persist();

        $checks = $this->service->check()['smtpSettings'];

        $this->assertTrue($checks['isEnabled']);
        $this->assertSame('database', $checks['source']);
        $this->assertTextContains(
            'The OpenPGP server key cannot be used to decrypt the SMTP settings stored in database.',
            $checks['errorMessage']
        );
        $this->assertTextContains(
            'To fix this problem, you need to configure the SMTP server again.',
            $checks['errorMessage']
        );
    }
}
