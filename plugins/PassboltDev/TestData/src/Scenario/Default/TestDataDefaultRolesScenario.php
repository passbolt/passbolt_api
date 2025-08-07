<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Default;

use App\Test\Factory\RoleFactory;
use Cake\Log\Log;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Exception;
use Passbolt\TestData\Command\Base\RolesDataCommand;

class TestDataDefaultRolesScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new RolesDataCommand())->getData();
        try {
            /** @var array $roles */
            $roles = RoleFactory::make($data)->persist();
        } catch (Exception $e) {
            Log::debug('Roles are already persisted in DB');

            return [];
        }

        return $roles;
    }
}
