<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use App\Test\Factory\FavoriteFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\FavoritesDataCommand;

class TestDataDemoFavoritesScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new FavoritesDataCommand())->getData();
        /** @var array $favorites */
        $favorites = FavoriteFactory::make($data)->persist();

        return $favorites;
    }
}
