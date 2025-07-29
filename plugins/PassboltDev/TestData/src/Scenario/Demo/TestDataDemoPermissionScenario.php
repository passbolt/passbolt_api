<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use App\Test\Factory\PermissionFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\PermissionsDataCommand;

class TestDataDemoPermissionScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new PermissionsDataCommand())->getData();
        /** @var array $permissions */
        $permissions = PermissionFactory::make($data)->persist();

        return $permissions;
    }
}
