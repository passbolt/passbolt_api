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
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Migrations\AbstractMigration;
use Passbolt\Rbacs\Service\Actions\RbacsControlledActionsInsertService;
use Passbolt\Rbacs\Service\Rbacs\InsertRbacsForActionsService;
use Passbolt\Rbacs\Model\Entity\Rbac;

class V5140InsertOfflineModeRbacs extends AbstractMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        try {
            ConnectionManager::get('default')->transactional(function (): void {
                (new RbacsControlledActionsInsertService())->insertRbacsControlledActions();

                (new InsertRbacsForActionsService())->add([
                    RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_ADD,
                    RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_DELETE,
                    RbacsControlledActionsInsertService::NAME_OFFLINE_ITEMS_VIEW,
                ], Rbac::CONTROL_FUNCTION_ALLOW);
            });
        } catch (Throwable $e) {
            $msg = 'There was an error in V5140InsertOfflineModeRbacs.';
            $msg .= ' ' . $e->getMessage();
            Log::error($msg);
        }
    }
}
