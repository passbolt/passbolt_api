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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Model\Rule;

use Cake\Core\Configure;
use Cake\Datasource\EntityInterface;

class IsNotUserFingerprintRule
{
    /**
     * Check if fingerprint is unique across all the existing users.
     *
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        // If no entity fingerprint provided the rule will fail
        $fingerprint = $entity->get('fingerprint');
        if (!isset($fingerprint) || !is_string($fingerprint)) {
            return false;
        }

        // If no server fingerprint provided the rule will fail
        $fingerprintServer = Configure::read('passbolt.gpg.serverKey.fingerprint');
        if (!isset($fingerprintServer) || !is_string($fingerprintServer)) {
            return false;
        }

        return !($fingerprintServer === $fingerprint);
    }
}
