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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Test\TestCase\Service\MetadataKey;

use App\Test\Factory\ResourceFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCaseV5;
use App\Utility\UuidFactory;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Metadata\Service\MetadataKey\MetadataKeyDeleteService;
use Passbolt\Metadata\Test\Factory\MetadataKeyFactory;
use Passbolt\Metadata\Test\Factory\MetadataPrivateKeyFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;

/**
 * @covers \Passbolt\Metadata\Service\MetadataKey\MetadataKeyDeleteService
 */
class MetadataKeyDeleteServiceTest extends AppTestCaseV5
{
    private MetadataKeyDeleteService $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MetadataKeyDeleteService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMetadataKeyDeleteService_Success(): void
    {
        EventManager::instance()->setEventList(new EventList());
        $user = UserFactory::make()->admin()->withValidGpgKey()->persist();
        $gpg = $user->get('gpgkey');
        MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $key */
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->withUserPrivateKey($gpg)->expired()->persist();

        $this->assertEquals(2, MetadataKeyFactory::count());
        $this->assertEquals(3, MetadataPrivateKeyFactory::count());

        $this->assertFalse($key->isDeleted());
        $id = $key->get('id');
        $uac = $this->makeUac($user);

        $this->service->delete($uac, $id);

        $this->assertEquals(2, MetadataKeyFactory::count());
        $this->assertEquals(1, MetadataPrivateKeyFactory::count());

        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $updatedKey */
        $updatedKey = MetadataKeyFactory::get($id);
        $this->assertTrue($updatedKey->isDeleted());

        $this->assertEventFired(MetadataKeyDeleteService::AFTER_METADATA_KEY_DELETE_SUCCESS_EVENT_NAME);
    }

    public function testMetadataKeyDeleteService_Error_NotAdmin(): void
    {
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->expired()->persist();
        $id = $key->get('id');
        $user = UserFactory::make()->user()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(ForbiddenException::class);
        $this->expectExceptionMessage('Access restricted to administrators.');
        $this->service->delete($uac, $id);
    }

    public function testMetadataKeyDeleteService_Error_IdNotUUID(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The metadata key ID should be a valid UUID.');
        $this->service->delete($uac, '🔥');
    }

    public function testMetadataKeyDeleteService_Error_RecordNotFound(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The metadata key does not exist or has been deleted.');
        $this->service->delete($uac, UuidFactory::uuid());
    }

    public function testMetadataKeyDeleteService_Error_KeyIsAlreadyDeleted(): void
    {
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->expired()->deleted()->persist();
        $id = $key->get('id');
        $user = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(NotFoundException::class);
        $this->expectExceptionMessage('The metadata key has already been deleted.');
        $this->service->delete($uac, $id);
    }

    public function testMetadataKeyDeleteService_Error_KeyIsNotExpired(): void
    {
        $key = MetadataKeyFactory::make()->withServerPrivateKey()->persist();
        $id = $key->get('id');
        $user = UserFactory::make()->admin()->persist();
        $uac = $this->makeUac($user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The metadata key should be marked as expired first.');
        $this->service->delete($uac, $id);
    }

    public function testMetadataKeyDeleteService_Error_KeyIsInUseByResources(): void
    {
        $user = UserFactory::make()->admin()->persist();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields(true)
            ->with('MetadataKeys', MetadataKeyFactory::make()->withServerPrivateKey()->expired())
            ->persist();
        $metadataKeyId = $resource->metadata_key_id;
        $uac = $this->makeUac($user);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The metadata key is still in use, migrate the remaining items to the new key first.');
        $this->service->delete($uac, $metadataKeyId);
    }

    /**
     * Given that a metadata key is associated with a resource of a deleted resource type
     * When I delete the metadata key
     * Then a bad request exception should be thrown
     * With the message "The metadata key is still in use, migrate the remaining items to the new key first."
     *
     * @return void
     */
    public function testMetadataKeyDeleteService_KeyIsUsedByResourceOfADeletedResourceType(): void
    {
        $uac = UserFactory::make()->admin()->persistedUAC();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields(true)
            ->with('MetadataKeys', MetadataKeyFactory::make()->withServerPrivateKey()->expired())
            ->with('ResourceTypes', ResourceTypeFactory::make()->deleted())
            ->persist();

        $metadataKeyId = $resource->metadata_key_id;

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('The metadata key is still in use, migrate the remaining items to the new key first.');
        $this->service->delete($uac, $metadataKeyId);
    }

    /**
     * Given that a metadata key is associated with a deleted resource
     * When I delete the metadata key
     * Then the metadata key should be marked as deleted
     * With the message "The metadata key is still in use, migrate the remaining items to the new key first."
     *
     * @return void
     */
    public function testMetadataKeyDeleteService_KeyIsUsedByASoftDeletedResource(): void
    {
        $uac = UserFactory::make()->admin()->persistedUAC();
        /** @var \App\Model\Entity\Resource $resource */
        $resource = ResourceFactory::make()
            ->v5Fields(true)
            ->with('MetadataKeys', MetadataKeyFactory::make()->withServerPrivateKey()->expired())
            ->deleted()
            ->persist();

        $metadataKeyId = $resource->metadata_key_id;

        $this->service->delete($uac, $metadataKeyId);

        $this->assertEquals(1, MetadataKeyFactory::count());
        $this->assertEquals(0, MetadataPrivateKeyFactory::count());

        /** @var \Passbolt\Metadata\Model\Entity\MetadataKey $updatedKey */
        $updatedKey = MetadataKeyFactory::get($metadataKeyId);
        $this->assertTrue($updatedKey->isDeleted());
    }
}
