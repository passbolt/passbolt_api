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

namespace App\Service\Resources;

use App\Model\Entity\Resource;
use App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService;

/**
 * Class PasswordExpiryExpireResourcesOnResourceShareServiceInterface.
 */
interface ExpireResourceOnShareServiceInterface
{
    /**
     * Before permissions are being edited, check if some permissions will
     * be removed in the $changes passed in the payload
     * If so, query the users having access to this resource, in order to check
     * after the permission was updated, if some users lost permission and resources
     * should be expired accordingly
     *
     * @param \App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService $getUsersIdsHavingAccessToService to retrieve permissions
     * @param \App\Model\Entity\Resource $resource resource on which permissions are being updated
     * @param array $changes The list of permissions changes to apply
     * @return void
     */
    public function collectUsersIdsHavingAccessToResource(
        PermissionsGetUsersIdsHavingAccessToService $getUsersIdsHavingAccessToService,
        Resource $resource,
        array $changes
    ): void;

    /**
     * Query the users having lost permission to this resource after the permission
     * update and mark this resource as expired if these users decrypted this resource in the past
     *
     * @param \App\Service\Permissions\PermissionsGetUsersIdsHavingAccessToService $getUsersIdsHavingAccessToService users which lost permission after the share
     * @param \App\Model\Entity\Resource $resource resource on which permissions are being updated
     * @return bool true if some resources were expired
     */
    public function expireResourceIfUsersLostPermission(
        PermissionsGetUsersIdsHavingAccessToService $getUsersIdsHavingAccessToService,
        Resource $resource
    ): bool;
}
