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
 * @since         4.7.0
 */

namespace App\Service\Healthcheck\Database;

use App\Service\Healthcheck\HealthcheckServiceInterface;
use Cake\Database\Exception\MissingConnectionException;
use Cake\Datasource\ConnectionManager;

class TablesCountDatabaseHealthcheck extends AbstractDatabaseHealthcheck
{
    protected int $tableCount = 0;

    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            $connection = ConnectionManager::get($this->getDatasource());
            $this->tableCount = count($connection->getSchemaCollection()->listTables());

            $this->status = $this->tableCount > 0;
        } catch (MissingConnectionException $connectionError) {
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('{0} tables found.', $this->tableCount);
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('No table found.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Run the install script to install the database tables'),
            'sudo su -s /bin/bash -c "' . ROOT . DS . 'bin/cake passbolt install" ' . PROCESS_USER,
        ];
    }

    /**
     * @inheritDoc
     */
    public function getLegacyArrayKey(): string
    {
        return 'tablesCount';
    }

    /**
     * @deprecated As of v4.7.0, just kept it here for BC.
     * @return int
     */
    public function getTableCount(): int
    {
        return $this->tableCount;
    }
}
