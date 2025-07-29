<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Large;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\TestData\Command\Large\ResourcesDataCommand;

class TestDataLargeResourcesScenario implements FixtureScenarioInterface
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
