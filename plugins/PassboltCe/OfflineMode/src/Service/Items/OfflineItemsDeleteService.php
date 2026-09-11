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
namespace Passbolt\OfflineMode\Service\Items;

use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Validation\Validation;

/**
 * Deletes the caller's offline_items row by id.
 *
 * Ownership check returns 404 (not 403) on "row belongs to another user" —
 * see ticket WP 3.3 for the rationale (avoid leaking existence of other
 * users' offline items).
 */
class OfflineItemsDeleteService
{
    use LocatorAwareTrait;

    /**
     * @param \App\Utility\UserAccessControl $uac The caller.
     * @param string $id The offline_items row id.
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException Invalid uuid.
     * @throws \Cake\Http\Exception\NotFoundException Id missing, or row belongs to another user.
     */
    public function delete(UserAccessControl $uac, string $id): void
    {
        if (!Validation::uuid($id)) {
            throw new BadRequestException(__('The offline item identifier should be a valid UUID.'));
        }

        /** @var \Passbolt\OfflineMode\Model\Table\OfflineItemsTable $OfflineItems */
        $OfflineItems = $this->fetchTable('Passbolt/OfflineMode.OfflineItems');

        $affected = $OfflineItems->deleteAll([
            'id' => $id,
            'user_id' => $uac->getId(),
        ]);
        if ($affected === 0) {
            throw new NotFoundException(__('The offline item does not exist.'));
        }
    }
}
