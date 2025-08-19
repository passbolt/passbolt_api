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
 * @since         5.5.0
 */

namespace Passbolt\Scim\Test\Utility;

use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\I18n\DateTime;
use Cake\ORM\TableRegistry;
use Passbolt\Scim\Model\Entity\ScimEntry;
use Passbolt\Scim\Test\Factory\ScimEntryFactory;
use Passbolt\Scim\Utility\ScimTools;

/**
 * Trait with utility function for testing scim users operations
 */
trait ScimTestUsersTrait
{
    public const DATETIME_TEST_NOW = '2025-07-18 12:00:00';

    public const USER_1_SCIM_NAME = 'user1@username.com';
    public const USER_1_EMAIL = 'user1@email.com';

    /**
     * @return void
     */
    protected function setTestNow(): void
    {
        DateTime::setTestNow(self::DATETIME_TEST_NOW);
    }

    /**
     * @param string $text
     * @param \Passbolt\Scim\Model\Entity\ScimEntry $scimEntry
     * @param int $userIndex
     * @return string
     */
    public function replaceUserPlaceholders(string $text, ScimEntry $scimEntry, int $userIndex): string
    {
        $text = str_replace('PLACEHOLDER_SCIM_ENTRY_CREATED_' . $userIndex, ScimTools::formatDateTimeToScim($scimEntry->created), $text);
        $text = str_replace('PLACEHOLDER_SCIM_ENTRY_MODIFIED_' . $userIndex, ScimTools::formatDateTimeToScim($scimEntry->modified), $text);

        return str_replace('PLACEHOLDER_USER_ID_' . $userIndex, $scimEntry->user?->id, $text);
    }

    /**
     * Create the SCIM test user 1
     *
     * @return \Passbolt\Scim\Model\Entity\ScimEntry|iterable<\Passbolt\Scim\Model\Entity\ScimEntry>
     */
    public function createScimUser1(): ScimEntry|iterable
    {
        $user = UserFactory::make([
            'username' => self::USER_1_EMAIL,
            'created' => DateTime::now(),
            'modified' => DateTime::now(),
            'profile' => [
                'first_name' => 'User 1',
                'last_name' => 'Scim',
            ],
        ])->user();

        return ScimEntryFactory::make([
            'external_identifier' => '4d36b536-42ba-4a65-9299-c4461222b47f',
            'scim_name' => self::USER_1_SCIM_NAME,
            'created' => DateTime::now(),
            'modified' => DateTime::now(),
        ])->withUser($user)->persist();
    }

    /**
     * Create the SCIM test user 2
     *
     * @return \Passbolt\Scim\Model\Entity\ScimEntry|iterable<\Passbolt\Scim\Model\Entity\ScimEntry>
     */
    public function createScimUser2(): ScimEntry|iterable
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

    /**
     * @param string $username
     * @param bool $isDeleted
     * @return \App\Model\Entity\User|null
     */
    public function getUserByUsername(string $username, bool $isDeleted = false): ?User
    {
        /** @var \App\Model\Table\UsersTable $usersTable */
        $usersTable = TableRegistry::getTableLocator()->get('Users');
        /** @var \App\Model\Entity\User|null $user */
        $user = $usersTable
            ->find()
            ->contain(['Profiles'])
            ->where([
                $usersTable->aliasField('username') => $username,
                $usersTable->aliasField('deleted') => $isDeleted,
            ])
            ->first();

        return $user;
    }

    /**
     * @param string $scimName
     * @param bool $addUser
     * @param bool $isDeleted
     * @return \Passbolt\Scim\Model\Entity\ScimEntry|null
     */
    public function getScimEntryByName(string $scimName, bool $addUser = false, bool $isDeleted = false): ?ScimEntry
    {
        /** @var \Passbolt\Scim\Model\Table\ScimEntriesTable $scimEntriesTable */
        $scimEntriesTable = TableRegistry::getTableLocator()->get('Passbolt/Scim.ScimEntries');
        $query = $scimEntriesTable
            ->find()
            ->where([
                $scimEntriesTable->aliasField('scim_name') => $scimName,
                $scimEntriesTable->aliasField('deleted') . ' ' . ($isDeleted ? 'IS NOT NULL' : 'IS NULL'),
            ]);
        if ($addUser) {
            $query->contain(['Users.Profiles']);
        }
        /** @var \Passbolt\Scim\Model\Entity\ScimEntry|null $scimEntry */
        $scimEntry = $query->first();

        return $scimEntry;
    }
}
