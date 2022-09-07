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

namespace Passbolt\SmtpSettings\Test\TestCase\Mailer\Transport;

use App\Test\Lib\Utility\Gpg\GpgAdaSetupTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Mailer\Transport\SmtpTransport;
use Passbolt\SmtpSettings\Test\Factory\SmtpSettingFactory;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

/**
 * @covers \Passbolt\SmtpSettings\Mailer\Transport\SmtpTransport
 */
class SmtpTransportTest extends TestCase
{
    use GpgAdaSetupTrait;
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    public function testSmtpTransport_With_Valid_DB_Settings()
    {
        $configInDb = $this->getSmtpSettingsData();
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $encryptedSettings = $this->gpg->encrypt(json_encode($configInDb));
        SmtpSettingFactory::make()->value($encryptedSettings)->persist();

        $configInFile = ['sender_name' => 'Ada Lovelace'];
        $transport = new SmtpTransport($configInFile);
        $configInTransport = $transport->getConfig();

        // The config in DB should be returned
        $this->assertSettingsHaveTheRightKeyValues($configInDb, $configInTransport);
    }

    public function testSmtpTransport_Without_DB_Settings_Should_Return_File_Settings()
    {
        $configInFile = $this->getSmtpSettingsData();

        $transport = new SmtpTransport($configInFile);
        $configInTransport = $transport->getConfig();

        // The config in File should be returned
        $this->assertSettingsHaveTheRightKeyValues($configInFile, $configInTransport);
    }

    public function testSmtpTransport_In_DB_Not_Decryptable_Should_Return_File_Settings()
    {
        $configInDb = $this->getSmtpSettingsData();
        $configInFile = $configInDb + ['sender_name' => 'Ada Lovelace'];
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        SmtpSettingFactory::make()->value('Foo')->persist();

        $transport = new SmtpTransport($configInFile);
        $configInTransport = $transport->getConfig();

        // The config in File should be returned
        $this->assertSettingsHaveTheRightKeyValues($configInFile, $configInTransport);
    }

    public function testSmtpTransport_No_Valid_Data_Should_Return_File_Values()
    {
        $configInFile = $this->getSmtpSettingsData();
        $configInDb = $configInFile + ['sender_name' => null]; // This is not a valid data
        $this->gpgSetup();
        $this->gpg->setEncryptKeyFromFingerprint($this->serverKeyId);
        $encryptedSettings = $this->gpg->encrypt(json_encode($configInDb));
        SmtpSettingFactory::make()->value($encryptedSettings)->persist();

        $transport = new SmtpTransport($configInFile);
        $configInTransport = $transport->getConfig();

        // The config in File should be returned
        $this->assertSettingsHaveTheRightKeyValues($configInFile, $configInTransport);
    }

    private function assertSettingsHaveTheRightKeyValues(array $configExpected, array $configInTransport)
    {
        $settingKeys = [
            'host', 'port', 'username', 'password', 'tls', 'sender_email', 'sender_name',
        ];

        foreach ($settingKeys as $v) {
            $this->assertSame($configExpected[$v], $configInTransport[$v]);
        }
    }
}
