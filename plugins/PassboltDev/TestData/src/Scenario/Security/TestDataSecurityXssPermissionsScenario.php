<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Security;

use App\Test\Factory\PermissionFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Security\XssPermissionsDataCommand;

class TestDataSecurityXssPermissionsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new XssPermissionsDataCommand())->getData();
        /** @var array $permissions */
        $permissions = PermissionFactory::make($data)->persist();

        return $permissions;
    }
}
