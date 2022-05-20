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
 * @since         3.6.0
 */

namespace App\Model\Rule;

use Cake\Datasource\EntityInterface;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;

class IsNotUserKeyFingerprintRule
{
    /**
     * Check if an entity that has the fingerprint property set does not
     * reuse the same fingerprint from the gpgkey table
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        // if entity does not contain fingerprint rule fails
        $fingerprint = $entity->get('fingerprint');
        if (!isset($fingerprint) || !is_string($fingerprint)) {
            return false;
        }

        try {
            return !(TableRegistry::getTableLocator()->get('Gpgkeys')
                ->exists([
                    'fingerprint' => $fingerprint,
                    'deleted' => false,
                ]));
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
