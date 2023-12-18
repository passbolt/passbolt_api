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
     * @var array
     */
    protected array $added;
    /**
     * @var array
     */
    protected array $deleted;
    /**
     * @var array
     */
    protected array $updated;

    /**
     * Constructor.
     *
     * @param array|null $added The added entities
     * @param array|null $updated The updated entities
     * @param array|null $deleted The deleted entities
     */
    final public function __construct(
        ?array $added = [],
        ?array $updated = [],
        ?array $deleted = []
    ) {
        $this->added = $added ?? [];
        $this->updated = $updated ?? [];
        $this->deleted = $deleted ?? [];
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function addDeletedEntity(Entity $entity):void
    {
        $this->addEntity($this->deleted, $entity);
    }

    /**
     * @param array $destEntitiesStack
     * @param $entity
     * @return void
     */
    private function addEntity(array &$destEntitiesStack, $entity): void
    {
        if (!$entity){
            return;
        }
        // @todo assert entity
        $destEntitiesStack[] = $entity;
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function addAddedEntity(Entity $entity):void
    {
        $this->addEntity($this->added, $entity);
    }

    /**
     * @param Entity $entity
     * @return void
     */
    public function addUpdatedEntity(?Entity $entity):void
    {
        $this->addEntity($this->updated, $entity);
    }

    /**
     * @param string|null $filterClassName
     * @return array
     */
    public function getDeletedEntities(?string $filterClassName): array
    {
        if (!$filterClassName) {
            return $this->deleted;
        }

        return array_filter($this->deleted, function ($entity) use($filterClassName) {
            return is_a($entity, $filterClassName);
        });
    }

    /**
     * @param EntitiesChangesDto $entitiesChangesDto
     * @return void
     */
    public function merge(EntitiesChangesDto $entitiesChangesDto): void
    {
        $this->addAddedEntities($entitiesChangesDto->added);
        $this->addUpdatedEntities($entitiesChangesDto->updated);
        $this->addDeletedEntities($entitiesChangesDto->deleted);
    }

    /**
     * @param array $entities
     * @return void
     */
    public function addAddedEntities(array $entities = []):void
    {
        $this->addEntities($this->added, $entities);
    }

    /**
     * @param array $destEntitiesStack
     * @param array $entities
     * @return void
     */
    private function addEntities(array &$destEntitiesStack, array $entities): void
    {
        foreach($entities as $entity)  {
//            dd($destEntitiesStack);
            $this->addEntity($destEntitiesStack, $entity);
        }
    }

    /**
     * @param array $entities
     * @return void
     */
    public function addUpdatedEntities(array $entities = []):void
    {
        $this->addEntities($this->updated, $entities);
    }

    /**
     * @param array $entities
     * @return void
     */
    public function addDeletedEntities(array $entities = []):void
    {
        $this->addEntities($this->deleted, $entities);
    }

    /**
     * @param string|null $filterClassName
     * @return array
     */
    public function getAddedEntities(?string $filterClassName): array
    {
        return $this->getEntities($this->added, $filterClassName);
    }

    /**
     * @param array $destEntitiesStack
     * @param string|null $filterClassName
     * @return array
     */
    private function getEntities(array $destEntitiesStack, ?string $filterClassName): array
    {
        if (!$filterClassName) {
            return $destEntitiesStack;
        }

        return array_filter($destEntitiesStack, function ($entity) use($filterClassName) {
            return is_a($entity, $filterClassName);
        });
    }

    /**
     * @param string|null $filterClassName
     * @return array
     */
    public function getUpdatedEntities(?string $filterClassName): array
    {
        return $this->getEntities($this->updated, $filterClassName);
    }
}
