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
namespace App\Test\TestCase\Service\Healthcheck;

use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableDefinedGpgHealthcheck;
use App\Service\Healthcheck\Gpg\HomeVariableWritableGpgHealthcheck;
use App\Service\Healthcheck\Gpg\PhpGpgModuleInstalledGpgHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Lib\AppTestCase;

/**
 * @covers \App\Service\Healthcheck\HealthcheckServiceCollector
 */
class HealthcheckServiceCollectorTest extends AppTestCase
{
    private ?HealthcheckServiceCollector $service = null;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new HealthcheckServiceCollector();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testHealthcheckServiceCollector_GetServicesFiltered_DuplicateServices(): void
    {
        // Specify services to be extracted from collector
        $domainsIncluded = [];
        $servicesIncluded = [
            PhpGpgModuleInstalledGpgHealthcheck::class,
            HomeVariableDefinedGpgHealthcheck::class,
            HomeVariableWritableGpgHealthcheck::class,
        ];
        // Push few services to test
        $this->service->addService(new PhpVersionHealthcheck());
        $this->service->addService(new PhpGpgModuleInstalledGpgHealthcheck());
        $this->service->addService(new HomeVariableDefinedGpgHealthcheck());
        $this->service->addService(new HomeVariableWritableGpgHealthcheck());

        $services = $this->service->getServicesFiltered($domainsIncluded, $servicesIncluded);

        $this->assertCount(3, $services);
    }
}
