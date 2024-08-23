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

use App\Model\Rule\User\IsActiveUserRule;
use Cake\Datasource\EntityInterface;

class UserIsActiveAndNotDeletedIfPresent
{
    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options): bool
    {
        $userId = $entity->get('user_id');
        // Only apply rule if user id is present
        if (is_null($userId)) {
            return true;
        }
        // only string is allowed
        if (!is_string($userId)) {
            return false;
        }

        /** @var callable $rule */
        $rule = new IsActiveUserRule();

        return $rule($entity, $options);
    }
}
