<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Default;

use Cake\Log\Log;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Exception;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\TestData\Command\Base\ResourceTypesDataCommand;

class TestDataDefaultResourceTypesScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new ResourceTypesDataCommand())->getData();
        try {
            /** @var array $resourceTypes */
            $resourceTypes = ResourceTypeFactory::make($data)->persist();
        } catch (Exception $e) {
            Log::debug('Resource types are already persisted in DB.');

            return [];
        }

        return $resourceTypes;
    }
}
