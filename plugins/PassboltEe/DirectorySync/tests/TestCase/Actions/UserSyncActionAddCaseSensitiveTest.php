<?php
declare(strict_types=1);

/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         4.4.0
 */
namespace Passbolt\DirectorySync\Test\TestCase\Actions;

use App\Model\Table\UsersTable;
use App\Service\Resources\ResourcesExpireResourcesFallbackServiceService;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Passbolt\DirectorySync\Actions\UserSyncAction;
use Passbolt\DirectorySync\Test\Utility\DirectorySyncDeprecatedIntegrationTestCase;

class UserSyncActionAddCaseSensitiveTest extends DirectorySyncDeprecatedIntegrationTestCase
{
    /**
     * @var array No fixtures in this test case, using factories
     */
    public array $fixtures = [];

    public function setUp(): void
    {
        parent::setUp();
        UserFactory::make()->admin()->persist();
        $this->action = new UserSyncAction(
            new ResourcesExpireResourcesFallbackServiceService()
        );
        $this->action->getDirectory()->setUsers([]);
    }

    public function testDirectorySyncUserAdd_Existing_Username_Case_Insensitive_Should_Map_On_Existing_User()
    {
        // Creating a deleted user. This user must be reported as already existing
        $created = DateTime::yesterday();
        $username = 'JOHN@passbolt.com';
        UserFactory::make(compact('username', 'created'))->user()->persist();
        $this->mockDirectoryUserData(
            'foo',
            'bar',
            $username,
            $created->subDays(1),
            $created->subDays(1),
        );
        $reports = $this->action->execute();
        $message = $reports[0]->getMessage();
        $this->assertSame(
            "The user $username was mapped with an existing user in passbolt.",
            $message,
        );
        $this->assertSame(2, UserFactory::count());
    }

    public function testDirectorySyncUserAdd_Existing_Username_Case_Sensitive_Should_Create_A_New_User()
    {
        Configure::write(UsersTable::PASSBOLT_SECURITY_USERNAME_CASE_SENSITIVE, true);
        // Creating a deleted user. This user must be reported as already existing
        $created = DateTime::yesterday();
        $username = 'JOHN@passbolt.com';
        UserFactory::make(compact('username', 'created'))->user()->persist();
        $this->mockDirectoryUserData(
            'foo',
            'bar',
            $username,
            $created->subDays(1),
            $created->subDays(1),
        );
        $reports = $this->action->execute();
        $message = $reports[0]->getMessage();
        $this->assertSame(
            'The user john@passbolt.com was successfully added to passbolt.',
            $message,
        );
        $this->assertSame(3, UserFactory::count());
    }

    /**
     * Test that directory sync works with multi-byte (e.g. Cyrillic) names that
     * produce a directory_name exceeding 255 bytes.
     *
     * The DN is constructed as: CN={fname} {lname},OU=PassboltUsers,DC=passbolt,DC=local
     * With 127 Cyrillic chars (254 bytes) for first name, the DN exceeds 255 bytes.
     * DirectoryEntriesTable::buildEntityFromData() uses strlen()/substr() which
     * operate on bytes, not characters. substr() at a byte boundary can split a
     * multi-byte character, producing invalid UTF-8 that fails validation.
     *
     * @see \Passbolt\DirectorySync\Model\Table\DirectoryEntriesTable::buildEntityFromData()
     */
    public function testDirectorySyncUserAdd_MultiByteCharacters_Should_Sync_Successfully()
    {
        // Ensure 'user' role exists (register() needs it to assign role_id)
        RoleFactory::make()->user()->persist();

        // Cyrillic first name: 127 chars = 254 bytes in UTF-8
        // DN will be ~304 bytes but only ~172 characters, well under the 255 char limit.
        // The byte-level truncation (substr) splits a Cyrillic character mid-byte,
        // producing invalid UTF-8 and causing the sync to fail.
        $firstName = str_repeat('б', 127);
        $lastName = 'тест';
        $username = 'cyrillic-user@passbolt.com';
        $created = DateTime::yesterday();

        $this->mockDirectoryUserData(
            $firstName,
            $lastName,
            $username,
            $created->subDays(1),
            $created->subDays(1),
        );
        $reports = $this->action->execute();
        $message = $reports[0]->getMessage();
        $this->assertStringContainsString(
            'was successfully added to passbolt',
            $message,
        );
        $this->assertSame(2, UserFactory::count());
        $userInserted = UserFactory::find()->contain('Profiles')
            ->where(compact('username'))
            ->firstOrFail();
        $this->assertSame($userInserted->profile->first_name, $firstName);
        $this->assertSame($userInserted->profile->last_name, $lastName);
        $this->assertSame($userInserted->username, $username);
    }
}
