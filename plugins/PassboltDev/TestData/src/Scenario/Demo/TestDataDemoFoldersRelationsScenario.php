<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\TestData\Command\Base\FoldersRelationsDataCommand;

class TestDataDemoFoldersRelationsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new FoldersRelationsDataCommand())->getData();
        /** @var array $folderRelations */
        $folderRelations = FoldersRelationFactory::make($data)->persist();

        return $folderRelations;
    }
}
