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
 * @since         4.1.0
 */

namespace Passbolt\Rbacs\Service\Rbacs;

use App\Model\Entity\Role;
use Cake\ORM\TableRegistry;
use Passbolt\Rbacs\Model\Entity\Rbac;

class RbacsInsertDefaultsService
{
    /**
     * @throws \Cake\ORM\Exception\PersistenceFailedException if default rbacs insert fails because of valation errors
     * @throws \Exception If an entity couldn't be saved because of an internal error
     * @return array<\Passbolt\Rbacs\Model\Entity\Rbac> array of Rbacs entities
     */
    public function allowAllUiActionsForUsers(): array
    {
        $Roles = TableRegistry::getTableLocator()->get('roles');

        /** @var \App\Model\Entity\Role $role */
        $role = $Roles->find()->where(['name' => Role::USER])->firstOrFail();

        $Rbacs = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.Rbacs');
        $alreadyExistingRbacUiActionIds = $Rbacs->find()->select('foreign_id');

        $UiActions = TableRegistry::getTableLocator()->get('Passbolt/Rbacs.UiActions');
        $uiactions = $UiActions->find()
            ->where(['UiActions.id NOT IN' => $alreadyExistingRbacUiActionIds]);

        $entities = [];

        foreach ($uiactions as $uiaction) {
            $entities[] = $Rbacs->newEntity([
                'role_id' => $role->id,
                'foreign_id' => $uiaction->id,
                'foreign_model' => Rbac::FOREIGN_MODEL_UI_ACTION,
                'control_function' => Rbac::CONTROL_FUNCTION_ALLOW,
            ], ['accessibleFields' => [
                'role_id' => true,
                'foreign_id' => true,
                'foreign_model' => true,
                'control_function' => true,
            ]]);
        }

        // Get a PersistenceFailedException if any records fail to save.
        /** @var array<\Passbolt\Rbacs\Model\Entity\Rbac> $savedEntities */
        $savedEntities = $Rbacs->saveManyOrFail($entities);

        return $savedEntities;
    }
}
