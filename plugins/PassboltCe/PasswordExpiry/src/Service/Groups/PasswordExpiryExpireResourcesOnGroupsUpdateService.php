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

namespace Passbolt\PasswordExpiry\Service\Groups;

use App\Model\Entity\Group;
use App\Model\Table\PermissionsTable;
use App\Service\Groups\ExpireResourcesOnGroupsUpdateServiceInterface;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\Event\EventDispatcherTrait;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
use Passbolt\PasswordExpiry\Service\Resources\PasswordExpiryExpireResourcesService;

/**
 * Mark resources as expired if:
 * - OR
 *  - some users lost access to resources by losing group access
 * - AND these users have viewed the secret in the past
 * - AND the permission is not expired yet
 */
class PasswordExpiryExpireResourcesOnGroupsUpdateService implements ExpireResourcesOnGroupsUpdateServiceInterface
{
    use EventDispatcherTrait;

    public const EVENT_EXPIRE_RESOURCES_ON_GROUP_UPDATE = 'PasswordExpiry.ExpireResourcesOnGroupsUpdateService.expire';

    protected PasswordExpiryValidationServiceInterface $passwordExpiryValidationService;

    /**
     * @param \App\Service\Resources\PasswordExpiryValidationServiceInterface $passwordExpiryValidationService password expiry validation service
     */
    public function __construct(PasswordExpiryValidationServiceInterface $passwordExpiryValidationService)
    {
        $this->passwordExpiryValidationService = $passwordExpiryValidationService;
    }

    /**
     * @inheritDoc
     */
    public function expireResourcesIfUsersLostPermission(Group $group, array $deletedGroupsUsers): bool
    {
        // Return if no users were removed from the group
        $userIdsRemovedFromGroup = Hash::extract($deletedGroupsUsers, '{n}.user_id');
        if (empty($userIdsRemovedFromGroup)) {
            return false;
        }

        if (!$this->passwordExpiryValidationService->isExpiryAutomatic()) {
            return false;
        }

        $expireService = (new PasswordExpiryExpireResourcesService());
        /** @var \App\Model\Table\GroupsTable $GroupsTable */
        $GroupsTable = TableRegistry::getTableLocator()->get('Groups');
        $resourceIdExpiringNow = [];
        foreach ($userIdsRemovedFromGroup as $userId) {
            $resourcesLostForUser = $GroupsTable->Permissions->findAcosAccessesDiffBetweenGroupAndUser(
                PermissionsTable::RESOURCE_ACO,
                $group->id,
                $userId
            );
            $result = $expireService->expireResources($resourcesLostForUser, $userId);
            if (!empty($result)) {
                $resourceIdExpiringNow = array_merge($resourceIdExpiringNow, $result);
            }
        }

        $hasExpiredResources = !empty($resourceIdExpiringNow);
        if ($hasExpiredResources) {
            $this->notifyGroupMembersAboutResourceExpiry($resourceIdExpiringNow);
        }

        return $hasExpiredResources;
    }

    /**
     * @param string[] $resourceIds Resource ids that are now expiring
     * @return void
     */
    protected function notifyGroupMembersAboutResourceExpiry(
        array $resourceIds
    ): void {
        $this->dispatchEvent(
            self::EVENT_EXPIRE_RESOURCES_ON_GROUP_UPDATE,
            compact('resourceIds'),
            $this
        );
    }
}
