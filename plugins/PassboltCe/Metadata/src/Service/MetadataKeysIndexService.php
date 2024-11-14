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
namespace Passbolt\Metadata\Service;

use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\Query;

class MetadataKeysIndexService
{
    use LocatorAwareTrait;

    /**
     * @param string $userId User identifier.
     * @param array|null $contain Contain values.
     * @param array|null $filters Filter values.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function get(string $userId, ?array $contain = null, ?array $filters = null): Query
    {
        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');

        $query = $metadataKeysTable->find()->select([
            'id',
            'fingerprint',
            'armored_key',
            'created',
            'modified',
            'created_by',
            'modified_by',
            'expired',
            'deleted',
        ]);

        if (is_array($contain) && !empty($contain)) {
            if (isset($contain['metadata_private_keys'])) {
                $query->contain(['MetadataPrivateKeys' => function ($q) use ($userId) {
                    return $q->where(['MetadataPrivateKeys.user_id' => $userId]);
                }]);
            }
        }

        if (is_array($filters) && !empty($filters)) {
            if (isset($filters['deleted'])) {
                $query->where($filters['deleted'] ? ['deleted IS NOT NULL'] : ['deleted IS NULL']);
            }
            if (isset($filters['expired'])) {
                $query->where($filters['expired'] ? ['expired IS NOT NULL'] : ['expired IS NULL']);
            }
        }

        return $query;
    }
}
