<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Security;

use App\Test\Factory\GroupFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Security\XssGroupsDataCommand;

class TestDataSecurityXssGroupsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new XssGroupsDataCommand())->getData();
        /** @var array $groups */
        $groups = GroupFactory::make($data)->persist();

        return $groups;
    }
}
