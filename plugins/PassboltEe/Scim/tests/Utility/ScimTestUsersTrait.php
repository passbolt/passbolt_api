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
 * @since         4.1.0
 */

namespace Passbolt\Scim\Test\Utility;

use Cake\I18n\DateTime;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;

/**
 * Trait with utility function for testing scim users operations
 */
trait ScimTestUsersTrait
{
    /**
     * @param string $text
     * @param \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry
     * @param int $userIndex
     * @return string
     */
    public function replaceUserPlaceholders(string $text, ScimEntry $scimEntry, int $userIndex): string
    {
        return str_replace('PLACEHOLDER_USER_ID_' . $userIndex, $scimEntry->foreign_key, $text);
    }

    /**
     * Create the SCIM test user 1
     *
     * @return \Passbolt\Scim\Model\Entity\ScimEntry
     */
    public function createScimUser1(): ScimEntry
    {
        return ScimEntryFactory::make([
            'external_identifier' => '4d36b536-42ba-4a65-9299-c4461222b47f',
            'scim_name' => 'user1@username.com',
            'created' => DateTime::now(),
            'modified' => DateTime::now(),
        ])->withUser([
            'username' => 'user1@email.com',
            'created' => DateTime::now(),
            'modified' => DateTime::now(),
            'profile' => [
                'first_name' => 'User 1',
                'last_name' => 'Scim',
            ],
        ])->persist();
    }

    /**
     * Create the SCIM test user 2
     *
     * @return \Passbolt\Scim\Model\Entity\ScimEntry
     */
    public function createScimUser2(): ScimEntry
    {
        return ScimEntryFactory::make([
            'external_identifier' => 'db8d7e50-0718-4556-bb49-92f43af1b6d4',
            'scim_name' => 'user2@username.com',
            'created' => DateTime::now(),
            'modified' => DateTime::now(),
        ])->withUser([
            'username' => 'user2@email.com',
            'created' => DateTime::now(),
            'modified' => DateTime::now(),
            'profile' => [
                'first_name' => 'User 2',
                'last_name' => 'Scim',
            ],
        ])->persist();
    }
}
