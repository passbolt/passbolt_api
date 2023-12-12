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

namespace Passbolt\PasswordExpiryPolicies\Service\Resources;

use App\Model\Entity\Permission;
use App\Model\Table\PermissionsTable;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use App\Service\Resources\ResourcesUpdateService;
use App\Utility\UserAccessControl;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\Http\Exception\BadRequestException;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validation;

/**
 * Class PasswordExpiryPoliciesResourcesExpiryUpdateService.
 *
 * Enable the expiry date to be null or any parsable date
 */
class PasswordExpiryPoliciesResourcesExpiryUpdateService
{
    use EventDispatcherTrait;

    private PasswordExpiryValidationServiceInterface $validationService;

    /**
     * @param \App\Service\Resources\PasswordExpiryValidationServiceInterface $validationService validation service
     */
    public function __construct(PasswordExpiryValidationServiceInterface $validationService)
    {
        $this->validationService = $validationService;
    }

    /**
     * Update a resource for the logged-in user.
     *
     * @param \App\Utility\UserAccessControl $uac The current user
     * @param array $data The resource data
     * @return \Cake\Datasource\ResultSetInterface
     * @throws \Exception If an unexpected error occurred
     * @throws \Cake\ORM\Exception\PersistenceFailedException If a resource couldn't be saved.
     */
    public function updateMany(UserAccessControl $uac, array $data = []): ResultSetInterface
    {
        $expiryDateList = $this->validateAndParsePayload($data);
        $resourceIds = array_keys($expiryDateList);
        $this->validateUacPermissions($uac, $resourceIds);
        $resources = $this->updateResourcesExpiryDate($uac, $expiryDateList);

        foreach ($resources as $resource) {
            $eventData = ['resource' => $resource, 'accessControl' => $uac,];
            $this->dispatchEvent(ResourcesUpdateService::UPDATE_SUCCESS_EVENT_NAME, $eventData);
        }

        return $resources;
    }

    /**
     * @param array $data payload
     * @return array<string, \Cake\I18n\FrozenTime|null> array with the resourceIds as keys and the expiry date as value
     * @throws \Cake\Http\Exception\BadRequestException if the expired value are not valid
     * @throws \Cake\Http\Exception\BadRequestException if the resource_id value are not valid
     * @throws \Cake\Http\Exception\BadRequestException if the resource_id value is found twice in the payload
     * @throws \Cake\Http\Exception\BadRequestException if the sanitized array is empty
     */
    protected function validateAndParsePayload(array $data): array
    {
        $dataSanitized = [];
        foreach ($data as $resource) {
            if (!is_array($resource)) {
                throw new BadRequestException(__('An array of arrays is expected.'));
            }
            $resourceId = $resource['id'] ?? null;
            if (!Validation::uuid($resourceId)) {
                throw new BadRequestException(__('The identifier should be a valid UUID.'));
            }
            $isExpiredDefined = array_key_exists($this->validationService::PASSWORD_EXPIRED_DATE, $resource);
            if (!$isExpiredDefined) {
                throw new BadRequestException(__('The expiry date is required.'));
            }
            $this->validationService->validateAndParseExpiryDate($resource);
            $expiryDate = $resource[$this->validationService::PASSWORD_EXPIRED_DATE];
            if (array_key_exists($resourceId, $dataSanitized)) {
                throw new BadRequestException(__('The identifier should be unique: {0}.', $resourceId));
            }
            $dataSanitized[$resourceId] = is_null($expiryDate) ? $expiryDate : new FrozenTime($expiryDate);
        }
        if (empty($dataSanitized)) {
            throw new BadRequestException(__('The data should not be empty.'));
        }

        return $dataSanitized;
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC
     * @param array $resourceIds the list of the resourceIds to update
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the user does not have update rights on one of the resources
     */
    protected function validateUacPermissions(UserAccessControl $uac, array $resourceIds = [])
    {
        /** @var \App\Model\Table\PermissionsTable $PermissionsTable */
        $PermissionsTable = TableRegistry::getTableLocator()->get('Permissions');
        $resourcesWithPermissionForUac = $PermissionsTable
            ->findAllByAro(PermissionsTable::RESOURCE_ACO, $uac->getId(), ['checkGroupsUsers' => true])
            ->select(['Permissions.aco_foreign_key', 'Permissions.type'])
            ->where(['Permissions.aco_foreign_key IN' => $resourceIds])
            ->orderAsc('Permissions.type')
            ->all();

        if ($resourcesWithPermissionForUac->isEmpty()) {
            throw new BadRequestException(__('You are not allowed to update these resources.'));
        }
        /** @var \App\Model\Entity\Permission $lowestPermissionOnResourcesRequested */
        $lowestPermissionOnResourcesRequested = $resourcesWithPermissionForUac->first();
        if ($lowestPermissionOnResourcesRequested->type < Permission::UPDATE) {
            throw new BadRequestException(__(
                'You are not allowed to update this resource: {0}',
                $lowestPermissionOnResourcesRequested->aco_foreign_key
            ));
        }

        $resourceIdsWithPermissionForUac = $resourcesWithPermissionForUac->extract('aco_foreign_key')->toArray();
        $resourceIdsWithNoPermissionForUac = array_diff($resourceIds, $resourceIdsWithPermissionForUac);
        if (!empty($resourceIdsWithNoPermissionForUac)) {
            // Notify that the user does not have update rights for one of the resources
            throw new BadRequestException(__(
                'You are not allowed to update this resource: {0}',
                array_pop($resourceIdsWithNoPermissionForUac)
            ));
        }
    }

    /**
     * @param \App\Utility\UserAccessControl $uac UAC performing the action
     * @param array<string, \Cake\I18n\FrozenTime|null> $expiryDateList array with the resourceIds as keys and the expiry date as value
     * @return \Cake\Datasource\ResultSetInterface
     * @throws \Exception if the entities could not be saved
     */
    protected function updateResourcesExpiryDate(UserAccessControl $uac, array $expiryDateList): ResultSetInterface
    {
        /** @var \App\Model\Table\ResourcesTable $ResourcesTable */
        $ResourcesTable = TableRegistry::getTableLocator()->get('Resources');
        $resources = $ResourcesTable
            ->find()
            ->whereInList('id', array_keys($expiryDateList))
            ->select([
                'id',
                'name',
                $this->validationService::PASSWORD_EXPIRED_DATE,
                'created',
                'modified',
                'created_by',
                'modified_by',
            ])
            ->all();

        foreach ($expiryDateList as $resourceId => $expiryDatetime) {
            /** @var \App\Model\Entity\Resource|null $resource */
            $resource = $resources->firstMatch(['id' => $resourceId]);
            if (is_null($resource)) {
                throw new BadRequestException(__('The resource with ID {0} does not exist.', $resourceId));
            }
            $ResourcesTable->patchEntity($resource, [
                'id' => $resourceId,
                $this->validationService::PASSWORD_EXPIRED_DATE => $expiryDatetime,
                'modified_by' => $uac->getId(),
            ], [
                'accessibleFields' => [
                    $this->validationService::PASSWORD_EXPIRED_DATE => true,
                    'modified_by' => true,
                ],
            ]);
        }

        // Here no need to skip the rules as these are run only on the fields being modified.
        // There are no rules on expired and modified_by, so skipping the rules is here not necessary.
        // Yet in case additional rules are added in the future on these fields, we skip the rules.
        $ResourcesTable->saveManyOrFail($resources, ['checkRules' => false]);

        return $resources;
    }
}
