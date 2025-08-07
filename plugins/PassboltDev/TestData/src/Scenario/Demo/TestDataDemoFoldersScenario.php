<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\TestData\Command\Base\FoldersDataCommand;

class TestDataDemoFoldersScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new FoldersDataCommand())->getData();
        /** @var array $folders */
        $folders = FolderFactory::make($data)->persist();

        return $folders;
    }
}
