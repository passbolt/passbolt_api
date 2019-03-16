<?php
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
 * @since         2.0.0
 */

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;

class HasValidParentRule
{
    /**
     * Performs the check
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        if (!isset($options['errorField']) || !isset($options['table'])) {
            return false;
        }
        if (empty($entity->{$options['errorField']})) {
            return true;
        }
        try {
            $Table = TableRegistry::getTableLocator()->get($options['table']);
            $id = $entity->get($options['errorField']);
            $lookupEntity = $Table->get($id);
            if ($lookupEntity->{$options['resourceField']} == $entity->{$options['resourceField']}) {
                return true;
            }
        } catch (\Exception $e) {
        }

        return false;
    }
}
