<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Security;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\TestData\Command\Security\XssResourcesDataCommand;

class TestDataSecurityXssResourcesScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new XssResourcesDataCommand())->getData();
        /** @var array $resources */
        $resources = ResourceFactory::make($data)->persist();

        return $resources;
    }
}
