<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Exception;
use Passbolt\TestData\Scenario\Default\TestDataDefaultResourceTypesScenario;
use Passbolt\TestData\Scenario\Default\TestDataDefaultRolesScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoCommentsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFavoritesScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFoldersPermissionsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFoldersRelationsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoFoldersScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoGpgKeysScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoGroupsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoGroupsUsersScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoPermissionScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoResourcesScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoSecretsScenario;
use Passbolt\TestData\Scenario\Demo\TestDataDemoUsersScenario;

class TestDataDemoScenario implements FixtureScenarioInterface
{
    /**
     * Load all default test data scenarios
     *
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $results = [];
        $scenarios = [
            TestDataDefaultRolesScenario::class,
            TestDataDefaultResourceTypesScenario::class,
            TestDataDemoUsersScenario::class,
            TestDataDemoGpgKeysScenario::class,
            TestDataDemoGroupsScenario::class,
            TestDataDemoGroupsUsersScenario::class,
            TestDataDemoResourcesScenario::class,
            TestDataDemoPermissionScenario::class,
            TestDataDemoFavoritesScenario::class,
            TestDataDemoCommentsScenario::class,
            TestDataDemoSecretsScenario::class,
            TestDataDemoFoldersPermissionsScenario::class,
            TestDataDemoFoldersScenario::class,
            TestDataDemoFoldersRelationsScenario::class,
        ];

        foreach ($scenarios as $scenarioClass) {
            try {
                /** @var \CakephpFixtureFactories\Scenario\FixtureScenarioInterface $scenario */
                $scenario = new $scenarioClass();
                $results[] = $scenario->load(...$args);
            } catch (Exception $e) {
                throw new Exception("Failed to load scenario: {$scenarioClass}. Reason: " . $e->getMessage(), 0, $e);
            }
        }

        return array_merge(...$results);
    }
}
