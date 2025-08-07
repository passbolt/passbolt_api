<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\TestData\Command\Base\FoldersPermissionsDataCommand;

class TestDataDemoFoldersPermissionsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new FoldersPermissionsDataCommand())->getData();
        /** @var array $folderPermissions */
        $folderPermissions = PermissionFactory::make($data)->persist();

        return $folderPermissions;
    }
}
