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

namespace Passbolt\DirectorySync\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;
use Cake\Core\Configure;
use Passbolt\DirectorySync\Middleware\DirectorySyncEndpointsSecurityMiddleware;
use Passbolt\DirectorySync\Service\Healthcheck\DirectorySyncEndpointsDisabledHealthcheck;
use Passbolt\Ee\Service\Healthcheck\EeHealthcheckServiceCollector;

/**
 * @covers \Passbolt\DirectorySync\Service\Healthcheck\DirectorySyncEndpointsDisabledHealthcheck
 */
class DirectorySyncEndpointsDisabledHealthcheckTest extends AppTestCase
{
    private DirectorySyncEndpointsDisabledHealthcheck $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new DirectorySyncEndpointsDisabledHealthcheck();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testDirectorySyncEndpointsDisabledHealthcheck_Fail_WithDefaultConfig(): void
    {
        $result = $this->service->check();

        $this->assertFalse($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    public function testDirectorySyncEndpointsDisabledHealthcheck_Pass(): void
    {
        Configure::write(DirectorySyncEndpointsSecurityMiddleware::SECURITY_CONFIG_KEY, true);

        $result = $this->service->check();

        $this->assertTrue($result->isPassed());
        $this->assertMetadataInfo($result);
    }

    /** Helper methods */

    /**
     * Make sure other metadata info are correct.
     *
     * @param DirectorySyncEndpointsDisabledHealthcheck $result Result object.
     * @return void
     */
    private function assertMetadataInfo(DirectorySyncEndpointsDisabledHealthcheck $result): void
    {
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_DIRECTORY_SYNC, $result->domain());
        $this->assertSame(EeHealthcheckServiceCollector::DOMAIN_DIRECTORY_SYNC, $result->cliOption());
        $this->assertSame(HealthcheckServiceCollector::LEVEL_WARNING, $result->level());
        $this->assertStringContainsString('The endpoints for updating the users directory configurations are disabled', $result->getSuccessMessage());
        $this->assertStringContainsString('The endpoints for updating the users directory configurations are enabled', $result->getFailureMessage());
    }
}
