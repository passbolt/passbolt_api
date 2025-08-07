<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use App\Test\Factory\GroupsUserFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\GroupsUsersDataCommand;

class TestDataDemoGroupsUsersScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new GroupsUsersDataCommand())->getData();
        /** @var array $groupsUsers */
        $groupsUsers = GroupsUserFactory::make($data)->persist();

        return $groupsUsers;
    }
}
