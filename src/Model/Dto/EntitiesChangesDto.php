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
namespace App\Model\Dto;

use Cake\ORM\Entity;

class EntitiesChangesDto
{
    /**
     * Array of added entities.
     *
     * @var \Cake\ORM\Entity[]
     */
    protected array $added = [];

    /**
     * Array of deleted entities.
     *
     * @var \Cake\ORM\Entity[]
     */
    protected array $deleted = [];

    /**
     * Arrau of updated entities.
     *
     * @var \Cake\ORM\Entity[]
     */
    protected array $updated = [];

    /**
     * Constructor.
     *
     * @param \Cake\ORM\Entity[]|null $added The added entities
     * @param \Cake\ORM\Entity[]|null $updated The updated entities
     * @param \Cake\ORM\Entity[]|null $deleted The deleted entities
     */
    final public function __construct(
        ?array $added = [],
        ?array $updated = [],
        ?array $deleted = []
    ) {
        $this->pushAddedEntities($added);
        $this->pushUpdatedEntities($updated);
        $this->pushDeletedEntities($deleted);
    }

    /**
     * Push an array of added entities.
     *
     * @param \Cake\ORM\Entity[] $entities The array of entities.
     * @return void
     */
    public function pushAddedEntities(array $entities = []): void
    {
        $this->pushEntities($this->added, $entities);
    }

    /**
     * Push an array of entities.
     *
     * @param array $destEntitiesStack The instance array reference to push the entities to.
     * @param \Cake\ORM\Entity[] $entities The array of entities.
     * @return void
     */
    private function pushEntities(array &$destEntitiesStack, array $entities): void
    {
        foreach ($entities as $entity) {
            $this->pushEntity($destEntitiesStack, $entity);
        }
    }

    /**
     * Push an added entity.
     *
     * @param array $destEntitiesStack The instance array reference to push the entities to.
     * @param \Cake\ORM\Entity|null $entity The entity to add.
     * @return void
     * @throws \TypeError If the provided entity argument is not a valid Entity.
     */
    private function pushEntity(array &$destEntitiesStack, ?Entity $entity = null): void
    {
        if (!$entity) {
            return;
        }
        if (!($entity instanceof Entity)) {
            throw new \TypeError('The entity parameter should be either a valid Entity instance or null.');
        }
        $destEntitiesStack[] = $entity;
    }

    /**
     * Push updated entities.
     *
     * @param \Cake\ORM\Entity[] $entities The array of entities.
     * @return void
     */
    public function pushUpdatedEntities(array $entities = []): void
    {
        $this->pushEntities($this->updated, $entities);
    }

    /**
     * Push an array of deleted entities.
     *
     * @param \Cake\ORM\Entity[] $entities The array of entities.
     * @return void
     */
    public function pushDeletedEntities(array $entities = []): void
    {
        $this->pushEntities($this->deleted, $entities);
    }

    /**
     * Push a deleted entity.
     *
     * @param \Cake\ORM\Entity $entity entity deleted
     * @return void
     */
    public function pushDeletedEntity(Entity $entity): void
    {
        $this->pushEntity($this->deleted, $entity);
    }

    /**
     * Push an added entity.
     *
     * @param \Cake\ORM\Entity $entity entity added
     * @return void
     */
    public function pushAddedEntity(Entity $entity): void
    {
        $this->pushEntity($this->added, $entity);
    }

    /**
     * Push an updated entity.
     *
     * @param \Cake\ORM\Entity|null $entity entity updated
     * @return void
     */
    public function pushUpdatedEntity(?Entity $entity): void
    {
        $this->pushEntity($this->updated, $entity);
    }

    /**
     * Get the deleted entities.
     *
     * @param string|null $filterClassName Filter the result by entities type.
     * @return \Cake\ORM\Entity[]
     */
    public function getDeletedEntities(?string $filterClassName = null): array
    {
        return $this->getEntities($this->deleted, $filterClassName);
    }

    /**
     * Merge entities changes.
     *
     * @param \App\Model\Dto\EntitiesChangesDto $entitiesChangesDto The entities changes to merge.
     * @return void
     */
    public function merge(EntitiesChangesDto $entitiesChangesDto): void
    {
        $this->pushAddedEntities($entitiesChangesDto->added);
        $this->pushUpdatedEntities($entitiesChangesDto->updated);
        $this->pushDeletedEntities($entitiesChangesDto->deleted);
    }

    /**
     * Get added entities.
     *
     * @param string|null $filterClassName Filter the result by entities type.
     * @return \Cake\ORM\Entity[]
     */
    public function getAddedEntities(?string $filterClassName = null): array
    {
        return $this->getEntities($this->added, $filterClassName);
    }

    /**
     * Get entities.
     *
     * @param array $destEntitiesStack The instance array reference to push the entities to.
     * @param string|null $filterClassName Filter the result by entities type.
     * @return \Cake\ORM\Entity[]
     */
    private function getEntities(array $destEntitiesStack, ?string $filterClassName = null): array
    {
        if (!$filterClassName) {
            return $destEntitiesStack;
        }

        return array_filter($destEntitiesStack, function ($entity) use ($filterClassName) {
            return is_a($entity, $filterClassName);
        });
    }

    /**
     * Get updated entities.
     *
     * @param string|null $filterClassName Filter the result by entities type.
     * @return \Cake\ORM\Entity[]
     */
    public function getUpdatedEntities(?string $filterClassName = null): array
    {
        return $this->getEntities($this->updated, $filterClassName);
    }
}
