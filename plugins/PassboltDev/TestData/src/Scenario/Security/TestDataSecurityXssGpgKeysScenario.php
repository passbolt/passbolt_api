<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Security;

use App\Test\Factory\GpgkeyFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Security\XssGpgkeysDataCommand;

class TestDataSecurityXssGpgKeysScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new XssGpgkeysDataCommand())->getData();
        /** @var array $gpgKeys */
        $gpgKeys = GpgkeyFactory::make($data)->persist();

        return $gpgKeys;
    }
}
