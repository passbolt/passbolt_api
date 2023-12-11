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
use Cake\Event\EventDispatcherTrait;
use Passbolt\PasswordExpiry\Service\Settings\PasswordExpiryGetSettingsService;

/**
 * Mark resources as expired if:
 * - OR
 *  - some users have lost permission to this resource
 * - AND these users have viewed the secret in the past
 * - AND the permission is not expired yet
 */
class PasswordExpiryExpireResourcesOnResourceShareService
{
    use EventDispatcherTrait;

    public const EVENT_EXPIRE_RESOURCE_ON_SHARE = 'PasswordExpiry.ExpireResourcesOnResourceShareService.expire';

    /**
     * Expire the resource if some users lost permission and accessed the resource
     * secret in the past.
     *
     * @param \App\Model\Entity\Resource $resource resources to be expired if exposed to users
     * @param string[] $userIdsWithAccessBeforeShare users with access before the share
     * @param string[] $userIdsWithAccessAfterShare users which lost permission after the share
     * @return bool true if some resources got expired
     */
    public function expireResourceIfUsersLostPermission(
        Resource $resource,
        array $userIdsWithAccessBeforeShare,
        array $userIdsWithAccessAfterShare
    ): bool {
        if ($resource->isExpired()) {
            return false;
        }
        $service = new PasswordExpiryGetSettingsService();
        $pwdExpirySettings = $service->get();
        if (!$pwdExpirySettings->isSettingsEnabled()) {
            return false;
        }

        $userIdsLosingPermission = array_diff($userIdsWithAccessBeforeShare, $userIdsWithAccessAfterShare);

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
}
