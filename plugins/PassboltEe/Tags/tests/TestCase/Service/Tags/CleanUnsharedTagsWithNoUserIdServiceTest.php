<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SA (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SA (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         3.7.1
 */

namespace Passbolt\Tags\Test\TestCase\Service\Tags;

use App\Utility\UuidFactory;
use Passbolt\Tags\Service\Tags\CleanUnsharedTagsWithNoUserIdService;
use Passbolt\Tags\Test\Factory\ResourcesTagFactory;
use Passbolt\Tags\Test\Factory\TagFactory;
use Passbolt\Tags\Test\Lib\TagTestCase;

class CleanUnsharedTagsWithNoUserIdServiceTest extends TagTestCase
{
    /**
     * @var CleanUnsharedTagsWithNoUserIdService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new CleanUnsharedTagsWithNoUserIdService();
    }

    public function testCleanUnsharedTagsWithNoUserIdService(): void
    {
        $rTagsSharedWithUserId = ResourcesTagFactory::make(rand(5, 10))
            ->setField('user_id', UuidFactory::uuid())
            ->with('Tags', TagFactory::make()->isShared())
            ->persist();
        $rTagsSharedWithoutUserId = ResourcesTagFactory::make(rand(5, 10))
            ->with('Tags', TagFactory::make()->isShared())
            ->persist();
        $rTagsNotSharedWithUserId = ResourcesTagFactory::make(rand(5, 10))
            ->setField('user_id', UuidFactory::uuid())
            ->with('Tags')
            ->persist();
        $rTagsNotSharedWithoutUserId = ResourcesTagFactory::make(rand(5, 10))
            ->with('Tags')
            ->persist();

        $result = $this->service->cleanUp();
        $this->assertSame(count($rTagsNotSharedWithoutUserId), $result);

        $rTagsCount = ResourcesTagFactory::count();
        $rTagsToBeKept = array_merge($rTagsSharedWithUserId, $rTagsSharedWithoutUserId, $rTagsNotSharedWithUserId);
        $this->assertSame($rTagsCount, count($rTagsToBeKept));
    }

    public function testCleanUnsharedTagsWithNoUserIdService_With_Nothing_To_Clean_Should_Not_Throw_An_Error(): void
    {
        $result = $this->service->cleanUp();
        $this->assertSame(0, $result);
    }
}
