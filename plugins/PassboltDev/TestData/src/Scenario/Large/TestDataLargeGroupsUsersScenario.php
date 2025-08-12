<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Large;

use App\Test\Factory\GroupsUserFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Large\GroupsUsersDataCommand;

class TestDataLargeGroupsUsersScenario implements FixtureScenarioInterface
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
