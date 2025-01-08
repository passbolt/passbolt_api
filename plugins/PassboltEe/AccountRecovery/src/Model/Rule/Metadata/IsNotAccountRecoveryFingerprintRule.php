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
 * @since         4.11.0
 */

namespace Passbolt\AccountRecovery\Model\Rule\Metadata;

use Cake\Log\Log;
use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

class IsNotAccountRecoveryFingerprintRule
{
    /**
     * @param \Cake\ORM\Entity $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Entity $entity, array $options): bool
    {
        // if entity does not contain fingerprint rule fails
        $fingerprint = $entity->get('fingerprint');
        if (!isset($fingerprint) || !is_string($fingerprint)) {
            return false;
        }

        $query = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys')->find(); // phpcs:ignore
        try {
            return !$query->where(['fingerprint' => $fingerprint])->firstOrFail();
        } catch (\Exception $e) {
            Log::error($e->getMessage());

            return false;
        }
    }
}
