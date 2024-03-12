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

class DefaultContentDatabaseHealthcheck extends AbstractDatabaseHealthcheck
{
    /**
     * @inheritDoc
     */
    public function check(): HealthcheckServiceInterface
    {
        try {
            /** @var \Cake\Database\Connection $connection */
            $connection = ConnectionManager::get($this->getDatasource());
            $nRoles = $connection->selectQuery('id')
                ->from('roles')
                ->rowCountAndClose();
            $this->status = ($nRoles >= 3);
        } catch (MissingConnectionException | \PDOException $e) {
        }

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getSuccessMessage(): string
    {
        return __('Some default content is present.');
    }

    /**
     * @inheritDoc
     */
    public function getFailureMessage(): string
    {
        return __('No default content found.');
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
        return 'defaultContent';
    }
}
