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

use App\Model\Entity\Permission;
use App\Test\Fixture\Base\GroupsFixture;
use App\Test\Fixture\Base\PermissionsFixture;
use App\Test\Fixture\Base\ResourcesFixture;
use App\Test\Fixture\Base\SecretsFixture;
use App\Test\Fixture\Base\UsersFixture;
use App\Test\Lib\Model\ResourcesModelTrait;
use App\Test\Lib\Utility\FixtureProviderTrait;
use App\Utility\UuidFactory;
use Passbolt\Folders\Service\Resources\ResourcesAfterSoftDeleteService;
use Passbolt\Folders\Test\Lib\FoldersTestCase;
use Passbolt\Folders\Test\Lib\Model\FoldersModelTrait;
use Passbolt\Folders\Test\Lib\Model\FoldersRelationsModelTrait;

/**
 * Passbolt\Folders\Service\Folders\ResourcesAfterSoftDeleteService Test Case
 *
 * @uses \Passbolt\Folders\Service\Resources\ResourcesAfterSoftDeleteService
 */
class ResourcesAfterSoftDeleteServiceTest extends FoldersTestCase
{
    use FixtureProviderTrait;
    use FoldersModelTrait;
    use FoldersRelationsModelTrait;
    use ResourcesModelTrait;

    public $fixtures = [
        GroupsFixture::class,
        PermissionsFixture::class,
        UsersFixture::class,
        ResourcesFixture::class,
        SecretsFixture::class,
    ];

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
        [$resource, $userAId, $userBId] = $this->insertFixture_AfterResourceSoftDeleted();

        $this->service->afterSoftDelete($resource);

        $this->assertItemIsInTrees($resource->id, 0);
    }

    private function insertFixture_AfterResourceSoftDeleted()
    {
        $userAId = UuidFactory::uuid('user.id.ada');
        $userBId = UuidFactory::uuid('user.id.betty');
        $resource = $this->addResourceFor(['name' => 'R1'], [$userAId => Permission::OWNER, $userBId => Permission::OWNER]);

        return [$resource, $userAId, $userBId];
    }
}
