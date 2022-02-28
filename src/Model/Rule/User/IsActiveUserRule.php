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
 * @since         3.5.0
 */

namespace App\Model\Rule\User;

use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

class IsActiveUserRule
{
    /**
     * Performs the check
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        if (!$entity->has('user_id')) {
            return false;
        }
        $UsersTable = TableRegistry::getTableLocator()->get('Users');

        return $UsersTable
            ->find()
            ->where([
                $UsersTable->aliasField('id') => $entity->get('user_id'),
                $UsersTable->aliasField('deleted') => false,
                $UsersTable->aliasField('active') => true,
            ])->count() > 0;
    }
}
