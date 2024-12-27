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
 * @since         4.11.0
 */
namespace Passbolt\Metadata\Model\Validation;

use App\Error\Exception\CustomValidationException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\Metadata\Form\RotateKey\MetadataBatchUpdateForm;

abstract class MetadataBatchUpdateValidationService
{
    protected array $entities = [];

    /**
     * The entity model (i.e. Resources, Folders, etc.).
     *
     * @return string
     */
    abstract public function getModel(): string;

    /**
     * @param array $requestData Request data.
     * @return array
     * @throws \Cake\Http\Exception\BadRequestException If data is invalid.
     * @throws \App\Error\Exception\CustomValidationException If data is invalid.
     * @throws \Cake\Http\Exception\NotFoundException If one or more resources are not found.
     */
    public function validateMany(array $requestData): array
    {
        foreach ($requestData as $values) {
            if (!is_array($values)) {
                throw new BadRequestException(__('The entity must be an array.'));
            }

            $id = $values['id'] ?? null;
            if (!Validation::uuid($id)) {
                throw new BadRequestException(__('The identifier should be a valid UUID.'));
            }
        }

        $entityIds = Hash::extract($requestData, '{n}.id');
        $this->entities = $this->queryEntitiesFromIds($entityIds)->all()->toArray();
        // Re-arrange entities array to set key as identifier and value as entity object to easily find it
        $this->entities = Hash::combine($this->entities, '{n}.id', '{n}');

        $data = [];
        foreach ($requestData as $i => $entity) {
            $entityId = $entity['id'];
            if (!array_key_exists($entityId, $this->entities)) {
                throw new NotFoundException(__('Entity {0} not found.', $entityId));
            }

            /** @var \Passbolt\Metadata\Model\Entity\MetadataKey|null $metadataKey */
            $metadataKey = $this->entities[$entityId]['metadata_key'] ?? null;
            if (!is_null($metadataKey)) {
                $metadataKey = $metadataKey->toArray();
            }
            $entity['metadata_key'] = $metadataKey;

            $form = new MetadataBatchUpdateForm();
            if (!$form->execute($entity)) {
                $errors[] = $form->getErrors();
                throw new CustomValidationException(__('Could not validate the metadata key data for the entity with ID: {0}.', $entityId), $errors); // phpcs:ignore;
            }
            $data[$i] = $form->getData();
        }

        return $data;
    }

    /**
     * Returns fetched entities from the DB.
     *
     * @return array
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @param array $entityIds Resource ids to find.
     * @return \Cake\ORM\Query
     */
    protected function queryEntitiesFromIds(array $entityIds): Query
    {
        $Table = TableRegistry::getTableLocator()->get($this->getModel());

        return $Table->find()
            ->select([
                $Table->aliasField('id'),
                $Table->aliasField('modified'),
                $Table->aliasField('modified_by'),
                'MetadataKeys.id',
                'MetadataKeys.expired',
                'MetadataKeys.deleted',
            ])
            ->contain('MetadataKeys')
            ->where([
                $Table->aliasField('id') . ' IN' => $entityIds,
            ])
            ->orderDesc($Table->aliasField('id'));
    }
}
