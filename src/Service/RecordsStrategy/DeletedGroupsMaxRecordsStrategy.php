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

class DeletedGroupsMaxRecordsStrategy extends AbstractMaxRecordsStrategy implements
    GroupsDeleteRecordsStrategyInterface
{
    public const CONFIG_KEY = 'passbolt.security.maxRecords.groupsDelete';

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
        return $this->fetchTable('Groups')->find()
            ->where(['Groups.deleted' => true])
            ->count();
    }

    /**
     * @inheritDoc
     */
    protected function getErrorMessage(): string
    {
        return __('The maximum number of deleted groups for this organization has been reached.');
    }
}
