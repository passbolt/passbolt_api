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
 * @since         5.16.0
 */
namespace App\Database\Migration;

use Cake\Database\Schema\TableSchema;
use Migrations\Db\Adapter\PostgresAdapter;

class PassboltPostgresAdapter extends PostgresAdapter
{
    /**
     * @inheritDoc
     */
    protected function mapColumnData(array $data): array
    {
        $data = parent::mapColumnData($data);

        // To prevent "collation "utf8mb4_unicode_ci" for encoding "UTF8" does not exist" error on the postgres,
        // when collation is defined for the MySQL column.
        unset($data['collate']);

        // To prevent "type "blob" does not exist" error on the postgres, where the equivalent type is BYTEA.
        if ($data['type'] === self::PHINX_TYPE_BLOB) {
            $data['type'] = TableSchema::TYPE_BINARY;
        }

        return $data;
    }
}
