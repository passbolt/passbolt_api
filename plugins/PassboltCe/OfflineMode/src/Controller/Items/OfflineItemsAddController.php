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
 * @since         5.16.0
 */
namespace Passbolt\OfflineMode\Controller\Items;

use App\Controller\AppController;
use App\Utility\UuidFactory;
use Passbolt\OfflineMode\Service\Items\OfflineItemsAddService;
use Passbolt\OfflineMode\Service\Settings\OfflineSettingsGetService;
use Passbolt\Rbacs\Service\ActionAccessControl\RoleActionAccessControlServiceInterface;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;

/**
 * HTTP entry point for `POST|PUT /offline/<foreign_model>/<uuid>.json`.
 */
class OfflineItemsAddController extends AppController
{
    /**
     * Mark an item as available offline for the authenticated user.
     *
     * @param \Passbolt\Rbacs\Service\ActionAccessControl\RoleActionAccessControlServiceInterface $accessControlService RBAC service resolved via DI.
     * @param \Passbolt\OfflineMode\Service\Settings\OfflineSettingsGetService $offlineSettingsGetService Offline settings get service.
     * @param string $foreignModel Foreign model (i.e. `resource`, `folder`).
     * @param string $foreignKey The target object id.
     * @return void
     * @throws \Cake\Http\Exception\ForbiddenException RBAC deny for the user's role, Offline Mode disabled, or target item is not v5.
     * @throws \Cake\Http\Exception\BadRequestException Invalid uuid or unknown foreign model.
     * @throws \Cake\Http\Exception\NotFoundException Object missing / soft-deleted / no access.
     * @throws \App\Error\Exception\ValidationException The user is at the `max_items` limit.
     */
    public function add(
        RoleActionAccessControlServiceInterface $accessControlService,
        OfflineSettingsGetService $offlineSettingsGetService,
        string $foreignModel,
        string $foreignKey,
    ): void {
        $this->assertJson();

        $offlineSettingsGetService->throwExceptionIfDisabled();

        $accessControlService->controlUserRoleActionAccess(
            $this->User->getRoleEntity(),
            UuidFactory::uuid(RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_ADD),
        );

        $result = (new OfflineItemsAddService())->add(
            $this->User->getAccessControl(),
            $foreignModel,
            $foreignKey,
        );

        $this->success(__('The operation was successful.'), $result);
    }
}
