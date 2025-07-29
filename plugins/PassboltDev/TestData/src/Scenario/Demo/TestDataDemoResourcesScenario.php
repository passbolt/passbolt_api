<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\TestData\Command\Base\ResourcesDataCommand;

class TestDataDemoResourcesScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new ResourcesDataCommand())->getData();
        /** @var array $resources */
        $resources = ResourceFactory::make($data)->persist();

        return $resources;
    }
}
