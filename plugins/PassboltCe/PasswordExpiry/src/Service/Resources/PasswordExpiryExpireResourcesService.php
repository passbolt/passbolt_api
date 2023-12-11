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

namespace Passbolt\PasswordExpiry\Service\Resources;

use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\I18n\FrozenTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

/**
 * Mark resources as expired if:
 * - OR
 *  - a user has lost permission to this resource
 *  - a user has lost permission on a group with access to the resources
 * - AND the user has viewed the secret in the past
 * - AND the permission is not expired yet
 */
class PasswordExpiryExpireResourcesService
{
    use EventDispatcherTrait;
    use LocatorAwareTrait;

    /**
     * @param \Cake\ORM\Query|array<int, string> $resourceIds resource IDs to expire
     * @param string $userId user ID loosing permission
     * @return ?array returns the number of resources expired by the action
     */
    public function expireResources($resourceIds, string $userId): ?array
    {
        if (is_array($resourceIds) && count($resourceIds) === 0) {
            return null;
        }
        $resourceIds = $this->filterByResourceAccessForOneUser($userId, $resourceIds);

        $resourceIdsExpired = $this->setResourcesAsExpired($resourceIds);

        if (empty($resourceIdsExpired)) {
            return null;
        }

        return $resourceIdsExpired;
    }

    /**
     * @param string $resourceId resource ID to expire
     * @param array|\Cake\ORM\Query $userIdsLosingPermission check if one of these user IDs accessed to this resource
     * @return ?string[] returns the list of resources expired by this action
     */
    public function expireOneResource(string $resourceId, $userIdsLosingPermission): ?array
    {
        if (is_array($userIdsLosingPermission) && count($userIdsLosingPermission) === 0) {
            return null;
        }

        $usersThatAccessedToThisResourceSecretInThePast = $this->filterByResourceAccessForOneResource(
            $resourceId,
            $userIdsLosingPermission
        )->toArray();

        if (count($usersThatAccessedToThisResourceSecretInThePast) === 0) {
            return null;
        }

        $resourcesExpiring = $this->setResourcesAsExpired([$resourceId]);
        if (empty($resourcesExpiring)) {
            return null;
        }

        return $resourcesExpiring;
    }

    /**
     * @param array|\Cake\ORM\Query $resourceIds resources to expire
     * @return array returns a list of the resource IDs being expired by the action
     */
    protected function setResourcesAsExpired($resourceIds): array
    {
        /** @var \App\Model\Table\ResourcesTable $ResourcesTable */
        $ResourcesTable = $this->fetchTable('Resources');
        $filterByNotExpiredResourceExpression = function () use ($ResourcesTable) {
            return $ResourcesTable->notExpiredQueryExpression();
        };
        $conditionsToExpire = [$filterByNotExpiredResourceExpression, 'Resources.id IN' => $resourceIds];

        $resourcesExpiring = $ResourcesTable->find('list', ['valueField' => 'id'])
            ->select('id')
            ->disableHydration()
            ->where($conditionsToExpire)
            ->toArray();

        // If no resources will be expired, return empty
        if (empty($resourcesExpiring)) {
            return $resourcesExpiring;
        }

        $ResourcesTable->updateAll(
            [PasswordExpiryValidationServiceInterface::PASSWORD_EXPIRED_DATE => FrozenTime::now()->subSeconds(2)],
            $conditionsToExpire,
        );

        return $resourcesExpiring;
    }

    /**
     * @param string $userId user ID loosing permission
     * @param array|\Cake\ORM\Query $resourceIds Resources ids that are to be expired if the user ever viewed them
     * @return \Cake\ORM\Query
     */
    protected function filterByResourceAccessForOneUser(string $userId, $resourceIds): Query
    {
        return $this->fetchTable('Passbolt/Log.SecretAccesses')
            ->find()
            ->select(['SecretAccesses.resource_id'])
            ->where([
                'SecretAccesses.user_id' => $userId,
                'SecretAccesses.resource_id IN' => $resourceIds,
            ]);
    }

    /**
     * @param string $resourceId resource ID potentially expiring
     * @param array|\Cake\ORM\Query $userIds check if one of these user IDs accessed to this resource
     * @return \Cake\ORM\Query
     */
    protected function filterByResourceAccessForOneResource(string $resourceId, $userIds): Query
    {
        return $this->fetchTable('Passbolt/Log.SecretAccesses')
            ->find()
            ->select(['SecretAccesses.user_id'])
            ->where([
                'SecretAccesses.user_id IN' => $userIds,
                'SecretAccesses.resource_id' => $resourceId,
            ]);
    }
}
