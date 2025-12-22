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

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\ORM\TableRegistry;
use Exception;

class IsUniqueCaseInsensitive
{
    /**
     * Performs the check.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        if (!isset($options['errorField']) || !isset($options['table'])) {
            return false;
        }

        $field = $options['errorField'];
        // Only apply check if field is "dirty"
        if (!empty($options['checkDirty']) && !$entity->isDirty($field)) {
            return true;
        }

        try {
            $table = TableRegistry::getTableLocator()->get($options['table']);
            $alias = $table->getAlias();
            $value = $entity->get($field);

            $conditions["LOWER({$alias}.{$field}) IS"] = strtolower($value);

            // Exclude soft-deleted records from uniqueness check
            if ($table->getSchema()->hasColumn('deleted')) {
                $columnType = $table->getSchema()->getColumnType('deleted');
                if ($columnType === 'boolean') {
                    $conditions["{$alias}.deleted"] = false;
                } else {
                    $conditions["{$alias}.deleted IS"] = null;
                }
            }

            return !$table->exists($conditions);
        } catch (Exception $e) {
        }

        return false;
    }
}
