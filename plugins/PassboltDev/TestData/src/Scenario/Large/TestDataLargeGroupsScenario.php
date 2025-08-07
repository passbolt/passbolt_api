<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Large;

use App\Test\Factory\GroupFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Large\GroupsDataCommand;

class TestDataLargeGroupsScenario implements FixtureScenarioInterface
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
