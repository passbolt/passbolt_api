<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Large;

use App\Test\Factory\FavoriteFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Large\FavoritesDataCommand;

class TestDataLargeFavoritesScenario implements FixtureScenarioInterface
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
