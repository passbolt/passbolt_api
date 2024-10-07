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

namespace App\Model\Rule;

use App\Utility\UuidFactory;
use Cake\Datasource\EntityInterface;
use Passbolt\ResourceTypes\Model\Entity\ResourceType;

class IsNotV5PasswordStringType
{
    /**
     * @param \Cake\Datasource\EntityInterface $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(EntityInterface $entity, array $options)
    {
        $v5PasswordStringId = UuidFactory::uuid('resource-types.id.' . ResourceType::SLUG_V5_PASSWORD_STRING);

        return $entity->get('resource_type_id') !== $v5PasswordStringId;
    }
}
