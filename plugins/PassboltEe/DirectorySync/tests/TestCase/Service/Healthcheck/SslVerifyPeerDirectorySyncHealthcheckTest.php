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
 * @since         4.9.0
 */

namespace Passbolt\DirectorySync\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Service\Healthcheck\SslVerifyPeerDirectorySyncHealthcheck;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;

/**
 * @covers \Passbolt\DirectorySync\Service\Healthcheck\SslVerifyPeerDirectorySyncHealthcheck
 */
class SslVerifyPeerDirectorySyncHealthcheckTest extends AppTestCase
{
    private SslVerifyPeerDirectorySyncHealthcheck $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SslVerifyPeerDirectorySyncHealthcheck();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testSslVerifyPeerDirectorySyncHealthcheck_Pass_DefaultConfig(): void
    {
        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testSslVerifyPeerDirectorySyncHealthcheck_Pass_CustomConfiguration(): void
    {
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.enabled', true);
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.verifyPeer', true);
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.cadir', '/path/to/certs');
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.cafile', 'cert.pem');

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testSslVerifyPeerDirectorySyncHealthcheck_Pass_CustomOptionNotEnabled(): void
    {
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.enabled', false);
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.verifyPeer', false);

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testSslVerifyPeerDirectorySyncHealthcheck_Fail(): void
    {
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.enabled', true);
        Configure::write('passbolt.plugins.directorySync.security.sslCustomOptions.verifyPeer', false);

        $result = $this->service->check();

        $this->assertFalse($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    /** Helper methods */

    /**
     * Make sure other metadata info are correct.
     *
     * @param SslVerifyPeerDirectorySyncHealthcheck $result Result object.
     * @return void
     */
    private function assertMetadataInfo(SslVerifyPeerDirectorySyncHealthcheck $result): void
    {
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_DIRECTORY_SYNC, $result->domain());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_DIRECTORY_SYNC, $result->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $result->level());
        $this->assertStringContainsString('SSL certification verification for LDAP server is enabled', $result->getSuccessMessage());
        $this->assertStringContainsString('SSL certification verification for LDAP server is disabled', $result->getFailureMessage());
    }
}
