<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Demo;

use App\Test\Factory\CommentFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Base\CommentsDataCommand;

class TestDataDemoCommentsScenario implements FixtureScenarioInterface
{
    /**
     * @param mixed ...$args
     * @return array
     */
    public function load(mixed ...$args): array
    {
        $data = (new CommentsDataCommand())->getData();
        /** @var array $comments */
        $comments = CommentFactory::make($data)->persist();

        return $comments;
    }
}
