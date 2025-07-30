<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Large;

use App\Test\Factory\SecretFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Large\SecretsDataCommand;

class TestDataLargeSecretsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new SecretsDataCommand())->getData();
        /** @var array $secrets */
        $secrets = SecretFactory::make($data)->persist();

        return $secrets;
    }
}
