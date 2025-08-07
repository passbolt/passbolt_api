<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Security;

use App\Test\Factory\CommentFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Security\XssCommentsDataCommand;

class TestDataSecurityXssCommentsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new XssCommentsDataCommand())->getData();
        /** @var array $comments */
        $comments = CommentFactory::make($data)->persist();

        return $comments;
    }
}
