<?php
declare(strict_types=1);

namespace Passbolt\TestData\Scenario\Large;

use App\Test\Factory\CommentFactory;
use CakephpFixtureFactories\Scenario\FixtureScenarioInterface;
use Passbolt\TestData\Command\Large\CommentsDataCommand;

class TestDataLargeCommentsScenario implements FixtureScenarioInterface
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
