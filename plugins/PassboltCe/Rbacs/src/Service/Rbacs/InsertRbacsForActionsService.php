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

namespace Passbolt\Rbacs\Service\Rbacs;

use App\Model\Entity\Role;
use App\Model\Table\RolesTable;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Passbolt\Rbacs\Model\Entity\Rbac;
use Passbolt\Rbacs\Model\Table\RbacsTable;

class InsertRbacsForActionsService
{
    private ?RolesTable $Roles;
    private ?RbacsTable $Rbacs;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->Roles = TableRegistry::getTableLocator()->get('Roles');
        $this->Rbacs = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.Rbacs');
    }

    /**
     * @param array<string> $actionNames Actions to add.
     * @return int
     */
    public function add(array $actionNames): int
    {
        // Find all roles apart from admin & guest
        $roles = $this->Roles->find()->where(['name NOT IN' => [Role::GUEST, Role::ADMIN]])->all();

        if ($roles->isEmpty()) {
            return 0;
        }

        $actions = $this->getActionsToAdd($actionNames);

        $insertQuery = $this->Rbacs
            ->insertQuery()
            ->insert(['id', 'role_id', 'control_function', 'foreign_model', 'foreign_id', 'created', 'modified']);

        foreach ($roles as $role) {
            foreach ($actions as $action) {
                $insertQuery->values([
                    'id' => Text::uuid(),
                    'role_id' => $role->id,
                    'control_function' => Rbac::CONTROL_FUNCTION_DENY,
                    'foreign_model' => Rbac::FOREIGN_MODEL_ACTION,
                    'foreign_id' => UuidFactory::uuid($action->name),
                    'created' => DateTime::now()->format('Y-m-d H:i:s'),
                    'modified' => DateTime::now()->format('Y-m-d H:i:s'),
                ]);
            }
        }

        return $insertQuery->execute()->rowCount();
    }

    /**
     * @param array<string> $actionNames Action names.
     * @return array<\Passbolt\Log\Model\Entity\Action>
     */
    private function getActionsToAdd(array $actionNames): array
    {
        $alreadyExistingRbacActionsIds = $this->Rbacs
            ->find()
            ->select('foreign_id')
            ->where(['foreign_model' => Rbac::FOREIGN_MODEL_ACTION]);

        $actionsTable = TableRegistry::getTableLocator()->get('Passbolt/Log.Actions');

        $conditions = ['Actions.name IN' => $actionNames];
        if (!$alreadyExistingRbacActionsIds->all()->isEmpty()) {
            $conditions['Actions.id NOT IN'] = $alreadyExistingRbacActionsIds;
        }

        return $actionsTable
            ->find()
            ->where($conditions)
            ->all()
            ->toArray();
    }
}
