<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

class IsNotSoleManagerOfNonEmptyGroupRule
{
    /**
     * Check if the user is sole manager of group that is not empty
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        $GroupsUsers = TableRegistry::get('GroupsUsers');
        $groups = $GroupsUsers->findNonEmptyGroupsWhereUserIsSoleManager($entity->id);

        return (empty($groups));
    }
}
