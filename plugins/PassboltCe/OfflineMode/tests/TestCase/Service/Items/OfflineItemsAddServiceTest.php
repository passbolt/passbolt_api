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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Test\TestCase\Service\Items;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\OfflineMode\Model\Entity\OfflineItem;
use Passbolt\OfflineMode\Model\Table\OfflineItemsTable;
use Passbolt\OfflineMode\Service\Items\OfflineItemsAddService;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Service\Items\OfflineItemsAddService
 */
class OfflineItemsAddServiceTest extends AppTestCase
{
    use UserAccessControlTrait;

    private OfflineItemsAddService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new OfflineItemsAddService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testOfflineItemsAddService_Success_PersistsRowAndReturnsEntity(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();
        $uac = $this->makeUac($user);

        $result = $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $resource->get('id'));

        $this->assertInstanceOf(OfflineItem::class, $result);
        $this->assertSame($user->get('id'), $result->get('user_id'));
        $this->assertSame(OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $result->get('foreign_model'));
        $this->assertSame($resource->get('id'), $result->get('foreign_key'));
        $this->assertSame($user->get('id'), $result->get('created_by'));
        $this->assertNotEmpty($result->get('created'));
        $this->assertSame(1, OfflineItemFactory::count());
    }

    public function testOfflineItemsAddService_Success_IsIdempotent(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->v5Fields()->withCreatorAndPermission($user)->persist();
        $uac = $this->makeUac($user);

        $first = $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $resource->get('id'));
        $second = $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $resource->get('id'));

        $this->assertSame($first->get('id'), $second->get('id'));
        $this->assertSame(1, OfflineItemFactory::count());
    }

    public function testOfflineItemsAddService_Error_BadRequest_InvalidForeignKeyUuid(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(BadRequestException::class);
        $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, 'not-a-uuid');
    }

    public function testOfflineItemsAddService_Error_BadRequest_UnknownForeignModel(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(BadRequestException::class);
        $this->service->add($uac, 'group', UuidFactory::uuid());
    }

    public function testOfflineItemsAddService_Error_NotFound_ResourceMissing(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);
        $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, UuidFactory::uuid());
    }

    public function testOfflineItemsAddService_Error_NotFound_ResourceSoftDeleted(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->setDeleted()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);
        $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $resource->get('id'));
    }

    public function testOfflineItemsAddService_Error_NotFound_UserHasNoReadAccess(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->persist(); // no permission for $user and item is v4
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);
        $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $resource->get('id'));
    }

    public function testOfflineItemsAddService_Error_Forbidden_ResourceIsV4(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $uac = $this->makeUac($user);

        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Offline mode is only available for v5 items.');
        $this->service->add($uac, OfflineItemsTable::FOREIGN_MODEL_RESOURCE, $resource->get('id'));
    }
}
