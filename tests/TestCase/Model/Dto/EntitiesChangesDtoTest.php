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
 * @since         4.5.0
 */

namespace App\Test\TestCase\Model\Dto;

use App\Model\Dto\EntitiesChangesDto;
use App\Model\Entity\Resource;
use App\Utility\UuidFactory;
use Cake\TestSuite\TestCase;
use TypeError;

class EntitiesChangesDtoTest extends TestCase
{
    public function testEntitiesChangesDto_constructorEmpty()
    {
        $dto = new EntitiesChangesDto();

        $resultAddedEntities = $dto->getAddedEntities();
        $this->assertEmpty($resultAddedEntities);
        $resultUpdatedEntities = $dto->getUpdatedEntities();
        $this->assertEmpty($resultUpdatedEntities);
        $resultDeletedEntities = $dto->getDeletedEntities();
        $this->assertEmpty($resultDeletedEntities);
    }

    public function testEntitiesChangesDto_pushSingleEntitySuccess()
    {
        $dto = new EntitiesChangesDto();
        $addedEntity = new Resource(['id' => UuidFactory::uuid()]);
        $dto->pushAddedEntity($addedEntity);
        $updatedEntity = new Resource(['id' => UuidFactory::uuid()]);
        $dto->pushUpdatedEntity($updatedEntity);
        $deletedEntity = new Resource(['id' => UuidFactory::uuid()]);
        $dto->pushDeletedEntity($deletedEntity);

        $resultAddedEntities = $dto->getAddedEntities();
        $this->assertNotEmpty($resultAddedEntities);
        $this->assertEquals($addedEntity, $resultAddedEntities[0]);
        $resultUpdatedEntities = $dto->getUpdatedEntities();
        $this->assertNotEmpty($resultUpdatedEntities);
        $this->assertEquals($updatedEntity, $resultUpdatedEntities[0]);
        $resultDeletedEntities = $dto->getDeletedEntities();
        $this->assertNotEmpty($resultDeletedEntities);
        $this->assertEquals($deletedEntity, $resultDeletedEntities[0]);
    }

    public function testEntitiesChangesDto_pushSingleEntityTypeError()
    {
        $dto = new EntitiesChangesDto();

        try {
            $dto->pushAddedEntity(42);
            $this->assertFalse(true, 'Exception expected');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }

        try {
            $dto->pushUpdatedEntity(42);
            $this->assertFalse(true, 'Exception expected');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }

        try {
            $dto->pushDeletedEntity(42);
            $this->assertFalse(true, 'Exception expected');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }
    }

    public function testEntitiesChangesDto_pushMultipleEntitiesSuccess()
    {
        $dto = new EntitiesChangesDto();
        $addedEntity = new Resource(['id' => UuidFactory::uuid()]);
        $addedEntity2 = new Resource(['id' => UuidFactory::uuid()]);
        $dto->pushAddedEntities([$addedEntity, $addedEntity2]);
        $updatedEntity = new Resource(['id' => UuidFactory::uuid()]);
        $updatedEntity2 = new Resource(['id' => UuidFactory::uuid()]);
        $dto->pushUpdatedEntities([$updatedEntity, $updatedEntity2]);
        $deletedEntity = new Resource(['id' => UuidFactory::uuid()]);
        $deletedEntity2 = new Resource(['id' => UuidFactory::uuid()]);
        $dto->pushDeletedEntities([$deletedEntity, $deletedEntity2]);

        $resultAddedEntities = $dto->getAddedEntities();
        $this->assertCount(2, $resultAddedEntities);
        $this->assertContains($addedEntity, $resultAddedEntities);
        $this->assertContains($addedEntity2, $resultAddedEntities);
        $resultUpdatedEntities = $dto->getUpdatedEntities();
        $this->assertCount(2, $resultUpdatedEntities);
        $this->assertContains($updatedEntity, $resultUpdatedEntities);
        $this->assertContains($updatedEntity2, $resultUpdatedEntities);
        $resultDeletedEntities = $dto->getDeletedEntities();
        $this->assertCount(2, $resultDeletedEntities);
        $this->assertContains($deletedEntity, $resultDeletedEntities);
        $this->assertContains($deletedEntity2, $resultDeletedEntities);
    }

    public function testEntitiesChangesDto_pushMultipleEntityTypeError()
    {
        $dto = new EntitiesChangesDto();

        try {
            $dto->pushAddedEntities([42]);
            $this->assertFalse(true, 'Exception expected');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }

        try {
            $dto->pushUpdatedEntities([42]);
            $this->assertFalse(true, 'Exception expected');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }

        try {
            $dto->pushDeletedEntities([42]);
            $this->assertFalse(true, 'Exception expected');
        } catch (TypeError $error) {
            $this->assertFalse(false);
        }
    }
}
