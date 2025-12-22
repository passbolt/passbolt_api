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
 * @since         5.8.0
 */

namespace Passbolt\Rbacs\Service\Actions;

use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Passbolt\Rbacs\Model\Entity\Rbac;

class RbacsControlledActionsInsertService
{
    /**
     * Available RBACS actions
     */
    public const NAME_GROUPS_ADD = 'GroupsAdd.addPost';
    public const NAME_ACCOUNT_RECOVERY_REQUESTS_INDEX = 'AccountRecoveryRequestsIndex.index';
    public const NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW = 'AccountRecoveryRequestsView.view';
    public const NAME_ACCOUNT_RECOVERY_RESPONSES_CREATE = 'AccountRecoveryResponsesCreate.post';
    public const RBACS_CONTROLLED_ACTIONS = [
        self::NAME_GROUPS_ADD => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_ACCOUNT_RECOVERY_REQUESTS_INDEX => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_ACCOUNT_RECOVERY_REQUESTS_VIEW => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
        self::NAME_ACCOUNT_RECOVERY_RESPONSES_CREATE => [
            Rbac::CONTROL_FUNCTION_ALLOW,
            Rbac::CONTROL_FUNCTION_DENY,
        ],
    ];

    /**
     * @return iterable
     * @throws \CakephpFixtureFactories\Error\PersistenceException if the actions could not be persisted
     */
    public function insertRbacsControlledActions(): iterable
    {
        /** @var \Passbolt\Log\Model\Table\ActionsTable $ActionsTable */
        $ActionsTable = TableRegistry::getTableLocator()->get('Actions');

        $actions = [];
        foreach (array_keys(self::RBACS_CONTROLLED_ACTIONS) as $actionName) {
            $id = UuidFactory::uuid($actionName);
            if ($ActionsTable->exists(compact('id'))) {
                continue;
            }
            $actions[] = [
                'id' => $id,
                'name' => $actionName,
            ];
        }

        if (empty($actions)) {
            return $actions;
        }

        $actions = $ActionsTable->newEntities($actions);

        return $ActionsTable->saveManyOrFail($actions);
    }
}
