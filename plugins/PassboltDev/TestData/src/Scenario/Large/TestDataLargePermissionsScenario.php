<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Large;

use App\Test\Factory\PermissionFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Large\PermissionsDataCommand;

class TestDataLargePermissionsScenario implements FixtureScenarioInterface
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
