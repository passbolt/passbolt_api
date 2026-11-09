<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         5.14.0
 */
namespace Passbolt\TestData\Scenario;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Exception;
use Passbolt\TestData\Scenario\Default\TestDataDefaultEditionScenario;
use Passbolt\TestData\Scenario\Default\TestDataDefaultOpenLdapSettingsScenario;
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
            TestDataDefaultEditionScenario::class,
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
            TestDataDefaultOpenLdapSettingsScenario::class,
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
