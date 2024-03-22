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

namespace Passbolt\SmtpSettings\Test\TestCase\Service\Healthcheck;

use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\SmtpSettings\Service\Healthcheck\SmtpSettingsSettingsSourceHealthcheck;
use Passbolt\SmtpSettings\Test\Lib\SmtpSettingsTestTrait;

class SmtpSettingsSourceHealthcheckTest extends TestCase
{
    use SmtpSettingsTestTrait;
    use TruncateDirtyTables;

    protected SmtpSettingsSettingsSourceHealthcheck $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new SmtpSettingsSettingsSourceHealthcheck($this->dummyPassboltFile);
    }

    public function tearDown(): void
    {
        unset($this->service);
        $this->deletePassboltDummyFile();
        parent::tearDown();
    }

    public function testSmtpSettingsSourceHealthcheck_Valid_DB()
    {
        $data = $this->getSmtpSettingsData();
        $this->encryptAndPersistSmtpSettings($data);

        $this->service->check();
        $this->assertTrue($this->service->isPassed());
        $this->assertSame('database', $this->service->getSource());
    }

    public function testSmtpSettingsSourceHealthcheck_Invalid_DB()
    {
        $data = $this->getSmtpSettingsData('port', 0);
        $this->encryptAndPersistSmtpSettings($data);

        $this->service->check();
        $this->assertTrue($this->service->isPassed());
        $this->assertSame('database', $this->service->getSource());
    }

    public function testSmtpSettingsSourceHealthcheck_Valid_File()
    {
        $this->setTransportConfig();
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $this->service->check();
        $this->assertFalse($this->service->isPassed());
        $this->assertSame(CONFIG . 'passbolt.php', $this->service->getSource());
    }

    public function testSmtpSettingsSourceHealthcheck_Invalid_File()
    {
        $this->setTransportConfig('port', 0);
        $this->makeDummyPassboltFile([
            'EmailTransport' => 'Foo',
            'Email' => 'Bar',
        ]);

        $this->service->check();
        $this->assertFalse($this->service->isPassed());
        $this->assertSame(CONFIG . 'passbolt.php', $this->service->getSource());
    }

    public function testSmtpSettingsSourceHealthcheck_Valid_Env()
    {
        $this->setTransportConfig();

        $this->service->check();
        $this->assertFalse($this->service->isPassed());
        $this->assertSame('env variables', $this->service->getSource());
    }

    public function testSmtpSettingsSourceHealthcheck_Invalid_Env()
    {
        $this->setTransportConfig('port', 0);

        $this->service->check();
        $this->assertFalse($this->service->isPassed());
        $this->assertSame('env variables', $this->service->getSource());
    }
}
