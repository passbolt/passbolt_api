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
 * @since         4.6.0
 */

namespace App\Service\Healthcheck\Database;

use App\Service\Healthcheck\HealthcheckServiceInterface;
use App\Utility\Migration;
use Cake\Datasource\ConnectionManager;

class SchemaUpToDateDatabaseHealthcheck extends AbstractDatabaseHealthcheck
{
    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        $datasource = $this->getDatasource();
        try {
            /** @var \Cake\Database\Connection $connection */
            $connection = ConnectionManager::get($datasource);
            $connection->getDriver()->connect();
            $this->status = !Migration::needMigration($datasource);
        } catch (\Exception $e) {
            // Do nothing
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('The database schema up to date.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('The database schema is not up to date.');
    }

    /**
     * @inheritDoc
     */
    public function getHelpMessage()
    {
        return [
            __('Run the migration scripts:'),
            'sudo su -s /bin/bash -c "' . ROOT . DS . 'bin/cake migrations migrate --no-lock" ' . PROCESS_USER,
            __('See. https://www.passbolt.com/help/tech/update'),
        ];
    }
}
