<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use App\Test\Factory\GpgkeyFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\GpgkeysDataCommand;

class TestDataDemoGpgKeysScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new GpgkeysDataCommand())->getData();
        /** @var array $gpgKeys */
        $gpgKeys = GpgkeyFactory::make($data)->persist();

        return $gpgKeys;
    }
}
