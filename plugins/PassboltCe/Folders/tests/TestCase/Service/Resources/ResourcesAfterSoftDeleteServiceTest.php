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
 * @since         2.13.0
 */

namespace Passbolt\Folders\Test\TestCase\Service\Resources;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\FixtureProviderTrait;
use Passbolt\Folders\Service\Resources\ResourcesAfterSoftDeleteService;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterSoftDeleteService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterSoftDeleteService
 */
class ResourcesAfterSoftDeleteServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;

    /**
     * @var ResourcesAfterSoftDeleteService
     */
    private $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new ResourcesAfterSoftDeleteService();
    }

    public function testResourcesAfterCreateServiceSuccess_AfterResourceSoftDeleted()
    {
        [$userA, $userB] = UserFactory::make(2)->persist();
        $resource = ResourceFactory::make()
            ->withFoldersRelationsFor([$userA, $userB])
            ->withPermissionsFor([$userA, $userB])
            ->persist();

        $this->service->afterSoftDelete($resource);

        $this->assertItemIsInTrees($resource->get('id'), 0);
    }
}
