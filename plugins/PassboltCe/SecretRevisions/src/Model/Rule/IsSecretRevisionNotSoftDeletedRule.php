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
 * @since         5.7.0
 */

namespace Passbolt\SecretRevisions\Model\Rule;

use App\Model\Rule\IsNotSoftDeletedRule;
use Cake\Datasource\EntityInterface;

class IsSecretRevisionNotSoftDeletedRule extends IsNotSoftDeletedRule
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
        // Skip the rule if the resource is being created,
        // as the secret revision is persisted after the secrets
        if ($options['isResourceAdded'] ?? false) {
            return true;
        }

        return parent::__invoke($entity, $options);
    }
}
