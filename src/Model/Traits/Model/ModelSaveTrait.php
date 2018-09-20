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
namespace App\Model\Traits\Model;

use Cake\Datasource\EntityInterface;

trait ModelSaveTrait
{
    /**
     * Extend the Cakephp save method to retry a save if the PDO layer throws a Deadlock Exception.
     * @param EntityInterface $entity The entity to save
     * @param array $options The save options
     * @return mixed
     * @throws \Exception If too many attempts to save the entity. It can happen when the sql server return deadlock errors 3 times in a row.
     */
    public function saveOrRetry(EntityInterface $entity, $options = [])
    {
        if (!isset($entity->_saveAttemps)) {
            $entity->_saveAttemps = 0;
        }
        $entity->_saveAttemps++;

        try {
            return $this->save($entity, $options);
        } catch (\PDOException $e) {
            if ($e->getCode() == 4001) {
                if ($entity->_saveAttemps >= 3) {
                    throw new \Exception('Too many attempts', 429);
                }
                usleep(100);
                $this->saveOrRetry($entity, $options);
            }
            throw $e;
        }
    }
}
