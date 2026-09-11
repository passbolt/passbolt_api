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
use Cake\Http\Exception\NotFoundException;
use Passbolt\OfflineMode\Service\Items\OfflineItemsDeleteService;
use Passbolt\OfflineMode\Test\Factory\OfflineItemFactory;

/**
 * @covers \Passbolt\OfflineMode\Service\Items\OfflineItemsDeleteService
 */
class OfflineItemsDeleteServiceTest extends AppTestCase
{
    use UserAccessControlTrait;

    private OfflineItemsDeleteService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new OfflineItemsDeleteService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testOfflineItemsDeleteService_Success_DeletesRow(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $offlineItem = OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();

        $this->service->delete($this->makeUac($user), $offlineItem->get('id'));

        $this->assertSame(0, OfflineItemFactory::count());
    }

    public function testOfflineItemsDeleteService_Success_DoesNotTouchOtherUsersRows(): void
    {
        $userA = UserFactory::make()->user()->active()->persist();
        $userB = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withPermissionsFor([$userA, $userB])->persist();
        $offlineA = OfflineItemFactory::make()->setUser($userA)->setResource($resource)->persist();
        $offlineB = OfflineItemFactory::make()->setUser($userB)->setResource($resource)->persist();

        $this->service->delete($this->makeUac($userA), $offlineA->get('id'));

        $this->assertSame(1, OfflineItemFactory::count());
        $this->assertNotNull(OfflineItemFactory::find()->where(['id' => $offlineB->get('id')])->first());
    }

    public function testOfflineItemsDeleteService_Error_BadRequest_InvalidUuid(): void
    {
        $user = UserFactory::make()->user()->active()->persist();

        $this->expectException(BadRequestException::class);
        $this->service->delete($this->makeUac($user), 'not-a-uuid');
    }

    public function testOfflineItemsDeleteService_Error_NotFound_IdDoesNotExist(): void
    {
        $user = UserFactory::make()->user()->active()->persist();

        $this->expectException(NotFoundException::class);
        $this->service->delete($this->makeUac($user), UuidFactory::uuid());
    }

    public function testOfflineItemsDeleteService_Error_NotFound_RowBelongsToAnotherUser(): void
    {
        $owner = UserFactory::make()->user()->active()->persist();
        $intruder = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($owner)->persist();
        $offlineItem = OfflineItemFactory::make()->setUser($owner)->setResource($resource)->persist();

        try {
            $this->service->delete($this->makeUac($intruder), $offlineItem->get('id'));
            $this->fail('Expected NotFoundException');
        } catch (NotFoundException $e) {
            // Row must still be in the DB.
            $this->assertSame(1, OfflineItemFactory::count());
            $this->assertNotNull(
                OfflineItemFactory::find()->where(['id' => $offlineItem->get('id')])->first(),
            );
        }
    }

    /**
     * A second delete on the same id (e.g. a retried client request, or two
     * tabs firing the same DELETE) must surface as a clean 404 — never 500.
     * The `deleteAll` shape gives us this for free; the test guards against a
     * future refactor reintroducing a find + deleteOrFail race window.
     */
    public function testOfflineItemsDeleteService_Idempotent_SecondDeleteOnSameIdReturns404(): void
    {
        $user = UserFactory::make()->user()->active()->persist();
        $resource = ResourceFactory::make()->withCreatorAndPermission($user)->persist();
        $offlineItem = OfflineItemFactory::make()->setUser($user)->setResource($resource)->persist();
        $uac = $this->makeUac($user);
        $id = $offlineItem->get('id');

        $this->service->delete($uac, $id);

        $this->expectException(NotFoundException::class);
        $this->service->delete($uac, $id);
    }
}
