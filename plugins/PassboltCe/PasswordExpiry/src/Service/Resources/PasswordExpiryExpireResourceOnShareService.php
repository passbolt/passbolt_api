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

use App\Model\Entity\Resource;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;
use App\Service\Resources\ExpireResourceOnShareServiceInterface;
use App\Service\Resources\PasswordExpiryValidationServiceInterface;
use Cake\Event\EventDispatcherTrait;

/**
 * Mark resources as expired if:
 * - OR
 *  - some users have lost permission to this resource
 * - AND these users have viewed the secret in the past
 * - AND the permission is not expired yet
 */
class PasswordExpiryExpireResourceOnShareService implements ExpireResourceOnShareServiceInterface
{
    use EventDispatcherTrait;

    public const EVENT_EXPIRE_RESOURCE_ON_SHARE = 'PasswordExpiry.ExpireResourcesOnResourceShareService.expire';

    protected PasswordExpiryValidationServiceInterface $passwordExpiryValidationService;

    /**
     * @var string[]|null
     */
    protected ?array $userIdsWithPermissionOnResourceBeforeShare;

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
    public function collectUsersIdsHavingAccessToResource(
        PermissionsGetUsersIdsHavingAccessToService $getUsersIdsHavingAccessToService,
        Resource $resource,
        array $changes
    ): void {
        if (!$this->hasDeleteInChanges($changes)) {
            return;
        }
        if (!$this->passwordExpiryValidationService->isExpiryAutomatic()) {
            return;
        }

        $this->userIdsWithPermissionOnResourceBeforeShare = $getUsersIdsHavingAccessToService
            ->getUsersIdsHavingAccessTo($resource->id);
    }

    /**
     * @inheritDoc
     */
    public function expireResourceIfUsersLostPermission(
        PermissionsGetUsersIdsHavingAccessToService $getUsersIdsHavingAccessToService,
        Resource $resource
    ): bool {
        if ($resource->isExpired()) {
            return false;
        }
        if (empty($this->userIdsWithPermissionOnResourceBeforeShare)) {
            return false;
        }

        $userIdsWithAccessAfterShare = $getUsersIdsHavingAccessToService->getUsersIdsHavingAccessTo($resource->id);

        $userIdsLosingPermission = array_diff(
            $this->userIdsWithPermissionOnResourceBeforeShare,
            $userIdsWithAccessAfterShare
        );

        $resourcesExpiring = (new PasswordExpiryExpireResourcesService())
            ->expireOneResource($resource->id, $userIdsLosingPermission);

        $hasExpiredResources = !empty($resourcesExpiring);
        if ($hasExpiredResources) {
            $this->dispatchEvent(
                self::EVENT_EXPIRE_RESOURCE_ON_SHARE,
                ['resourceIds' => [$resource->id]],
                $resource
            );
        }

        return $hasExpiredResources;
    }

    /**
     * Check if permissions are going to be removed, before unnecessarily querying the users table
     *
     * @param array $changes changes in the payload
     * @return bool
     */
    protected function hasDeleteInChanges(array $changes): bool
    {
        foreach ($changes as $change) {
            if (isset($change['delete']) && $change['delete']) {
                return true;
            }
        }

        return false;
    }
}
