<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use App\Test\Factory\GroupFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\GroupsDataCommand;

class TestDataDemoGroupsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new GroupsDataCommand())->getData();
        /** @var array $groups */
        $groups = GroupFactory::make($data)->persist();

        return $groups;
    }
}
