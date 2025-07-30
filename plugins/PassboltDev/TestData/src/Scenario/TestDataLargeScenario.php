<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Exception;
use Passbolt\TestData\Scenario\Default\TestDataDefaultResourceTypesScenario;
use Passbolt\TestData\Scenario\Default\TestDataDefaultRolesScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargeCommentsScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargeFavoritesScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargeGroupsScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargeGroupsUsersScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargePermissionsScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargeResourcesScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargeSecretsScenario;
use Passbolt\TestData\Scenario\Large\TestDataLargeUsersScenario;

class TestDataLargeScenario implements FixtureScenarioInterface
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
            TestDataLargeUsersScenario::class,
            TestDataLargeGroupsScenario::class,
            TestDataLargeGroupsUsersScenario::class,
            TestDataLargeResourcesScenario::class,
            TestDataLargePermissionsScenario::class,
            TestDataLargeFavoritesScenario::class,
            TestDataLargeCommentsScenario::class,
            TestDataLargeSecretsScenario::class,
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
