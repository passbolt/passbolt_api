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

namespace App\Service\RecordsStrategy;

class DeletedResourcesMaxRecordsStrategy extends AbstractMaxRecordsStrategy implements
    ResourcesDeleteRecordsStrategyInterface
{
    public const CONFIG_KEY = 'passbolt.security.maxRecords.resourcesDelete';

    /**
     * @inheritDoc
     */
    protected function getMax(): ?int
    {
        return $this->readMax(self::CONFIG_KEY);
    }

    /**
     * @inheritDoc
     */
    protected function countRecords(): int
    {
        return $this->fetchTable('Resources')->find()
            ->where(['Resources.deleted' => true])
            ->count();
    }

    /**
     * @inheritDoc
     */
    protected function getErrorMessage(): string
    {
        return __('The maximum number of deleted resources for this organization has been reached.');
    }
}
