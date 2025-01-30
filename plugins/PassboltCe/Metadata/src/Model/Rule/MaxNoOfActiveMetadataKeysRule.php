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

namespace Passbolt\Metadata\Model\Rule;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;

/**
 * Make sure to have only maximum of two active keys at a time.
 */
class MaxNoOfActiveMetadataKeysRule
{
    /**
     * Checks if there are already two keys active in the database.
     *
     * @param \Cake\ORM\Entity $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Entity $entity, array $options): bool
    {
        $metadataKeysTable = TableRegistry::getTableLocator()->get('Passbolt/Metadata.MetadataKeys');
        $metadataKeysCount = $metadataKeysTable
            ->find('active')
            ->select(['id'])
            ->all()
            ->count();
        // Consider the current one being added
        $metadataKeysCount = $metadataKeysCount + 1;

        return $metadataKeysCount <= 2;
    }
}
