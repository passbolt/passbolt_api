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
 * @since         4.5.0
 */
namespace App\Datasource\Paging;

use Cake\Datasource\Paging\NumericPaginator;
use Cake\Datasource\QueryInterface;

/**
 * This class is used to handle automatic model data pagination.
 */
class NumericCountAwarePaginator extends NumericPaginator
{
    public const IS_COUNTING = 'isCounting';

    /**
     * @inheritDoc
     */
    protected function getCount(QueryInterface $query, array $data): ?int
    {
        $limit = $data['options']['limit'] ?? null;
        $maxLimit = ($data['options']['maxLimit'] ?? $data['defaults']['maxLimit']) ?? null;
        if (is_null($limit) || $limit == $maxLimit) {
            return $data['numResults'] ?? null;
        }
        $query->applyOptions([self::IS_COUNTING => true]);

        return parent::getCount($query, $data);
    }
}
