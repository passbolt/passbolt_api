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
 * @since         4.12.1
 */
namespace Passbolt\Tags\Model\Table;

use Cake\Core\Configure;
use Cake\Log\Log;
use PDOException;

trait TagsTableBackupAwareTrait
{
    /**
     * The tags and resources_tags table are backed up when doing delicate migrations in DB
     * In case of a rollback, the backupMode flag will switch the target tables to backup_* tables
     * If the backup tables are not found, fallback on the default tables.
     *
     * @param string $defaultTableName the name of the table if not in backup mode
     * @return void
     */
    public function setTableNameBackupModeAware(string $defaultTableName): void
    {
        if (!Configure::read('passbolt.plugins.tags.backupMode')) {
            $this->setTable($defaultTableName);

            return;
        }
        $backupTableName = 'backup_' . $defaultTableName;

        try {
            // Check that the backup table exists
            $this->getConnection()->selectQuery()->select('*')->from($backupTableName)->limit(1)->execute();
            $this->setTable($backupTableName);
        } catch (PDOException $e) {
            Log::error($e->getMessage());
            $this->setTable($defaultTableName);
        }
    }
}
