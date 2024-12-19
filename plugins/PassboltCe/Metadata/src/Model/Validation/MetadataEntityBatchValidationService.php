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
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Cake\Validation\Validation;
use Passbolt\Metadata\Form\RotateKey\MetadataRotateKeyResourcesForm;

abstract class MetadataEntityBatchValidationService
{
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
        $requestData = Hash::combine($requestData, '{n}.id', '{n}');
        $entitiesInDB = $this->queryEntitiesFromIds($entityIds);
        $entitiesInDB = Hash::combine($entitiesInDB->all()->toArray(), '{n}.id', '{n}');

        $data = [];
        foreach ($requestData as $entityId => $entity) {
            $entity['metadata_key'] = $entitiesInDB[$entityId]['metadata_key'] ?? null;
            $form = new MetadataRotateKeyResourcesForm();
            if (!$form->execute($entity)) {
                $errors[] = $form->getErrors();
                throw new CustomValidationException(__('Could not validate the metadata key data for the entity with ID: {0}.', $entityId), $errors); // phpcs:ignore;
            }
            $data[] = $form->getData();
        }

        return $data;
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
                'MetadataKeys.id',
                'MetadataKeys.expired',
                'MetadataKeys.deleted',
            ])
            ->contain('MetadataKeys')
            ->where([
                $Table->aliasField('id') . ' IN' => $entityIds,
            ])
            ->disableHydration()
            ->orderDesc($Table->aliasField('id'));
    }
}
