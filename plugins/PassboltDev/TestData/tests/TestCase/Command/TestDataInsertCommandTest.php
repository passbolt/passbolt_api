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
 * @since         v5.4.0
 */
namespace Passbolt\TestData\Test\TestCase\Command;

use App\Test\Factory\CommentFactory;
use App\Test\Factory\FavoriteFactory;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\GroupFactory;
use App\Test\Factory\GroupsUserFactory;
use App\Test\Factory\OrganizationSettingFactory;
use App\Test\Factory\ProfileFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\SecretFactory;
use App\Test\Factory\UserFactory;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Database\Driver\Postgres;
use Cake\Datasource\ConnectionManager;
use Cake\TestSuite\TestCase;
use Cake\Utility\Hash;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\DirectorySync\Test\Factory\DirectoryOrgSettingFactory;
use Passbolt\DirectorySync\Utility\DirectoryInterface;
use Passbolt\Edition\Test\Factory\EditionOrganizationSettingFactory;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;
use Passbolt\Folders\Test\Factory\FolderFactory;
use Passbolt\Folders\Test\Factory\FoldersRelationFactory;
use Passbolt\Folders\Test\Factory\PermissionFactory;
use Passbolt\Folders\Test\Factory\ResourceFactory;
use Passbolt\ResourceTypes\Test\Factory\ResourceTypeFactory;
use Passbolt\SecretRevisions\Test\Factory\SecretRevisionFactory;

class TestDataInsertCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use TruncateDirtyTables;

    /**
     * Basic help test
     */
    public function testTestDataInsertCommand_Help()
    {
        $this->exec('passbolt insert -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('cake passbolt insert');
    }

    public function testTestDataInsertCommand_No_Scenario()
    {
        $this->exec('passbolt insert');
        $this->assertExitError();
    }

    public function testTestDataInsertCommand_Base_Scenario()
    {
        $this->exec('passbolt insert default -q');
        $this->assertExitSuccess();

        // Avatars
        // Does not do avatars as not says it is optional

        // Users
        $this->assertEquals(45, UserFactory::count());

        // Comments
        $this->assertEquals(326, CommentFactory::count());

        // Profiles
        $this->assertEquals(45, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(44, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(37, GroupFactory::count());
        // Group Users
        $this->assertEquals(353, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(50, ResourceFactory::count());
        // Resources Types
        $this->assertEquals(11, ResourceTypeFactory::count());
        // Permissions
        $this->assertEquals(861, PermissionFactory::count());
        // 32 of Permissions are Folders Permissions

        // Roles
        $this->assertEquals(3, RoleFactory::count());

        // Secret Revisions
        $this->assertEquals(50, SecretRevisionFactory::count());

        // Secrets
        $this->assertEquals(506, SecretFactory::count());

        // Favorites
        $this->assertEquals(4, FavoriteFactory::count());

        // Email Queue
        // No emails in queue
        $this->assertEquals(0, EmailQueueFactory::count());

        // Folders
        $this->assertEquals(19, FolderFactory::count());

        // Folders Relations
        $this->assertEquals(38, FoldersRelationFactory::count());

        // Folders Permissions
        // 36 part of Permissions

        // AccountSettingsDataCommand::class,
        // TagsDataCommand::class,
        // ResourcesTagsDataCommand::class,
        // These 3 are skipped as they are Pro features


        // 1 edition pro + 1 directorySync
        $this->assertEquals(2, OrganizationSettingFactory::count());
    }

    public function testTestDataInsertCommand_Base_Scenario_Replaces_Existing_Edition_And_Directory_Sync_Settings(): void
    {
        EditionOrganizationSettingFactory::make()->ce()->persist();
        DirectoryOrgSettingFactory::make()->default()->persist();

        $this->exec('passbolt insert default -q');
        $this->assertExitSuccess();

        // 1 edition pro + 1 directorySync
        $this->assertEquals(2, OrganizationSettingFactory::count());

        /** @var \App\Model\Entity\OrganizationSetting $edition */
        $edition = OrganizationSettingFactory::find()->where(['property' => 'edition'])->first();
        $this->assertSame('pro', $edition->value);

        /** @var \App\Model\Entity\OrganizationSetting $directorySync */
        $directorySync = OrganizationSettingFactory::find()->where(['property' => 'directorySync'])->first();
        $settings = json_decode($directorySync->value, true);

        $this->assertSame('admin@passbolt.com', Hash::get($settings, 'defaultUser'));
        $this->assertSame('uniqueMember', Hash::get($settings, 'fieldsMapping.openldap.group.users'));
        $this->assertSame(DirectoryInterface::TYPE_OPENLDAP, Hash::get($settings, 'ldap.domains.org_domain.ldap_type'));
        $this->assertStringStartsWith(
            '-----BEGIN PGP MESSAGE-----',
            Hash::get($settings, 'ldap.domains.org_domain.password'),
        );
    }

    public function testTestDataInsertCommand_Alt0_Scenario()
    {
        $this->exec('passbolt insert alt0 -q');
        $this->assertExitSuccess();

        // Users
        $this->assertEquals(27, UserFactory::count());

        // Profiles
        $this->assertEquals(27, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(26, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(19, GroupFactory::count());
        // Group Users
        $this->assertEquals(22, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(32, ResourceFactory::count());
        // Resources Types
        $this->assertEquals(11, ResourceTypeFactory::count());
        // Permissions
        $this->assertEquals(60, PermissionFactory::count());

        // Secret Revisions
        $this->assertEquals(32, SecretRevisionFactory::count());

        // Roles
        $this->assertEquals(3, RoleFactory::count());

        // Secrets
        $this->assertEquals(68, SecretFactory::count());

        // Folders
        // config.php in passbolt-test-data insert does not have folders
        // nor does it have any Email Queues or avatars
    }

    public function testTestDataInsertCommand_Large_Scenario()
    {
        $this->markTestSkipped('Skiped as the insert large command takes too long to run and test.');
        $this->exec('passbolt insert large');
        $this->assertExitSuccess();

        // The $resourcesTable->findIndex throws an error on Postgres
        // in Large CommentsDataCommand and FavoritesDataCommand
        // Thus no entries are persisted for these two models
        $isRunningOnPostgres = ConnectionManager::get('default')->getDriver() instanceof Postgres;

        // Avatars
        // Does not do avatars as not says it is optional

        // Users
        $this->assertEquals(52, UserFactory::count());

        // Comments
        $this->assertEquals($isRunningOnPostgres ? 0 : 28050, CommentFactory::count());

        // Profiles
        $this->assertEquals(52, ProfileFactory::count());

        // Gpgkeys
        $this->assertEquals(52, GpgKeyFactory::count());

        // Groups
        $this->assertEquals(101, GroupFactory::count());
        // Group Users
        $this->assertEquals(51, GroupsUserFactory::count());

        // Resources
        $this->assertEquals(3050, ResourceFactory::count());
        // Resources Types
        $this->assertEquals(10, ResourceTypeFactory::count());
        // Permissions
        $this->assertEquals(3050, PermissionFactory::count());

        // Roles
        $this->assertEquals(3, RoleFactory::count());

        // Secret Revisions
        $this->assertEquals(3050, SecretRevisionFactory::count());

        // Secrets
        $this->assertEquals(28050, SecretFactory::count());

        // Favorites
        $this->assertEquals($isRunningOnPostgres ? 0 : 28050, FavoriteFactory::count());

        // Email Queue
        // EmailQueueDataCommand not called

        // FoldersPermissionsDataCommand
        // FoldersDataCommand
        // FoldersRelationsDataCommand
        // These 3 are not called

        // TagsDataCommand
        // ResourcesTagsDataCommand
        // These 2 do not work as could not describe coloumns
    }
}
