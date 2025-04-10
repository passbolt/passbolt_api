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
 * @since         3.0.0
 */

namespace Passbolt\ResourceTypes\Service;

use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;

class ResourceTypesDeleteService
{
    use LocatorAwareTrait;

    /**
     * Soft delete a given resource type
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $resourceTypeId uuid
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if there are still resource of the target type
     * @throws \Cake\Http\Exception\BadRequestException if the resource type is already soft deleted
     * @throws \Cake\Http\Exception\NotFoundException if resource type is not present
     */
    public function delete(UserAccessControl $uac, string $resourceTypeId): void
    {
        $uac->assertIsAdmin();

        if (!Validation::uuid($resourceTypeId)) {
            throw new BadRequestException(__('The resource type identifier should be a UUID.'));
        }

        try {
            /** @var \Passbolt\ResourceTypes\Model\Table\ResourceTypesTable $resourcesTypesTable */
            $resourcesTypesTable = $this->fetchTable('Passbolt/ResourceTypes.ResourceTypes');
            /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
            $resourceType = $resourcesTypesTable->find()->where(['id' => $resourceTypeId])->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The resource type does not exist.'));
        }

        if ($resourceType->isDeleted()) {
            throw new BadRequestException(__('The resource type is already deleted.'));
        }

        $highlander = new ResourceTypesIsTheLastOneCheckService();
        if ($highlander->isLastOfTheDefaultVersion($resourceType)) {
            throw new BadRequestException(__('You cannot delete the last resource type of the default version.'));
        }
        if ($highlander->isTheOnlyOne($resourceType)) {
            throw new BadRequestException(__('You cannot delete the last resource type available.'));
        }

        /** @var \App\Model\Table\ResourcesTable $resourcesTable */
        $resourcesTable = $this->fetchTable('Resources');
        $count = $resourcesTable->find()->where([
            'resource_type_id' => $resourceTypeId,
            'deleted' => false,
        ])->all()->count();
        if ($count !== 0) {
            $msg = __('The resource type can not be deleted as resources of this type still exist.');
            throw new BadRequestException($msg);
        }

        $resourceType->deleted = DateTime::now();
        if (!$resourcesTypesTable->save($resourceType)) {
            throw new InternalErrorException(__('The resource type could not be deleted.'));
        }
    }

    /**
     * Undo soft delete a given resource type
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param string $resourceTypeId uuid
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the resource type is not deleted
     * @throws \Cake\Http\Exception\NotFoundException if resource type is not present
     */
    public function undoDelete(UserAccessControl $uac, string $resourceTypeId): void
    {
        $uac->assertIsAdmin();

        if (!Validation::uuid($resourceTypeId)) {
            throw new BadRequestException(__('The resource type identifier should be a UUID.'));
        }

        try {
            /** @var \Passbolt\ResourceTypes\Model\Table\ResourceTypesTable $resourcesTypesTable */
            $resourcesTypesTable = $this->fetchTable('Passbolt/ResourceTypes.ResourceTypes');
            /** @var \Passbolt\ResourceTypes\Model\Entity\ResourceType $resourceType */
            $resourceType = $resourcesTypesTable->find()->where(['id' => $resourceTypeId])->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new NotFoundException(__('The resource type does not exist.'));
        }

        if (!$resourceType->isDeleted()) {
            throw new BadRequestException(__('The resource type is not deleted.'));
        }

        $resourceType->deleted = null;

        /** @var \Passbolt\ResourceTypes\Model\Table\ResourceTypesTable $resourcesTypesTable */
        $resourcesTypesTable = $this->fetchTable('Passbolt/ResourceTypes.ResourceTypes');
        if (!$resourcesTypesTable->save($resourceType)) {
            throw new InternalErrorException(__('The resource type could not be deleted.'));
        }
    }
}
