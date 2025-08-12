<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Exception;
use Passbolt\TestData\Scenario\Default\TestDataDefaultResourceTypesScenario;
use Passbolt\TestData\Scenario\Default\TestDataDefaultRolesScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssCommentsScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssGpgKeysScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssGroupsScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssGroupsUsersScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssPermissionsScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssResourcesScenario;
use Passbolt\TestData\Scenario\Security\TestDataSecurityXssUsersScenario;

class TestDataSecurityScenario implements FixtureScenarioInterface
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
            TestDataSecurityXssUsersScenario::class,
            TestDataSecurityXssGpgKeysScenario::class,
            TestDataSecurityXssGroupsScenario::class,
            TestDataSecurityXssGroupsUsersScenario::class,
            TestDataSecurityXssResourcesScenario::class,
            TestDataSecurityXssPermissionsScenario::class,
            TestDataSecurityXssCommentsScenario::class,
            //TestDataDemoSecretsScenario::class,
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
