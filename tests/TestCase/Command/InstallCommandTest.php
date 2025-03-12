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
 * @since         3.1.0
 */
namespace App\Test\TestCase\Command;

use App\Model\Entity\Role;
use App\Service\Subscriptions\DefaultSubscriptionCheckInCommandService;
use App\Service\Subscriptions\SubscriptionCheckInCommandServiceInterface;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\Http\Client;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Faker\Factory;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;

class InstallCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use HealthcheckRequestTestTrait;
    use EmailNotificationSettingsTestTrait;
    use EmailQueueTrait;
    use PassboltCommandTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->emptyDirectory(CACHE . 'database' . DS);
        $this->loadNotificationSettings();
        $this->mockService(Client::class, function () {
            return $this->getMockedHealthcheckStatusRequest();
        });
        $this->mockProcessUserService('www-data');
    }

    /**
     * Basic help test
     */
    public function testInstallCommandHelp()
    {
        $this->exec('passbolt install -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Installation shell for the passbolt application.');
        $this->assertOutputContains('cake passbolt install');
    }

    /**
     * @Given I am root
     * @When I run "passbolt migrate"
     * @Then the migrations cannot be run.
     */
    public function testInstallCommandAsRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser('install');
    }

    /**
     * Quick install with no existing backup
     */
    public function testInstallCommandQuickWithNoExistingBackup()
    {
        $this->exec('passbolt install --quick -q');
        $this->assertExitError();
    }

    /**
     * Quick install with existing backup
     */
    public function testInstallCommandQuickWithExistingBackup()
    {
        // Create a backup
        $cmd = "
            INSERT INTO avatars (id, profile_id, created, modified)
            VALUES (
                '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                '0da907bd-5c57-5acc-ba39-c6ebe091f613',
                '2021-03-25 05:48:54',
                '2021-03-25 05:48:54'
            );
        ";
        file_put_contents(CACHE . 'database' . DS . 'backup_foo.sql', $cmd);

        $this->exec('passbolt install --quick -q');
        $this->assertExitSuccess();

        $this->assertSame(
            1,
            TableRegistry::getTableLocator()->get('Avatars')->find()->all()->count()
        );
    }

    /**
     * Normal installation will fail because tables are present
     */
    public function testInstallCommandNormalWithExistingTables()
    {
        $this->exec('passbolt install -q');
        $this->assertExitError();
    }

    /**
     * Normal installation force
     *
     * @group mysqldump
     */
    public function testInstallCommandNormalForceWithoutAdmin()
    {
        $this->exec('passbolt install --force --no-admin --backup -q -d test');
        $this->assertExitSuccess();
    }

    /**
     * Normal installation force with data import
     *
     * @group mysqldump
     */
    public function testInstallCommandNormalForceWithDataImport()
    {
        $this->exec('passbolt install --force --no-admin --backup -q -d test');
        $this->assertExitSuccess();
    }

    public function testInstallCommandNormalNoForce_Will_Fail()
    {
        $this->mockService(SubscriptionCheckInCommandServiceInterface::class, function () {
            return new DefaultSubscriptionCheckInCommandService();
        });
        $this->exec('passbolt install -d test');
        $this->assertExitError();
        $this->assertOutputContains('<error>Some tables are already present in the database. A new installation would override existing data.</error>');
        $this->assertOutputContains('<error>Please use --force to proceed anyway.</error>');
    }

    public function testInstallCommandForce_Will_Fail_If_BaseUrlIsNotValid()
    {
        $this->mockService(SubscriptionCheckInCommandServiceInterface::class, function () {
            return new DefaultSubscriptionCheckInCommandService();
        });
        Configure::write('App.fullBaseUrl', 'foo');
        $this->exec('passbolt install --force -d test');
        $this->assertExitError();
        $this->assertOutputContains('<error>App.fullBaseUrl does not validate. foo.</error>');
    }

    /**
     * Normal installation force with admin data
     *
     * @group mysqldump
     */
    public function testInstallCommandNormalForceWithAdminData()
    {
        $faker = Factory::create();
        $userName = $faker->email();
        $firstName = $faker->firstNameFemale();
        $lastName = $faker->lastName();
        $cmd = 'passbolt install --force --backup -q ';
        $cmd .= ' --admin-first-name ' . $firstName;
        $cmd .= ' --admin-last-name ' . $lastName;
        $cmd .= ' --admin-username ' . $userName;
        $cmd .= ' -d test';

        $this->exec($cmd);
        $this->assertExitSuccess();

        $UsersTable = TableRegistry::getTableLocator()->get('Users');
        $admins = $UsersTable->find()
            ->contain('Profiles')
            ->innerJoinWith('Roles', function (Query $q) {
                return $q->where(['Roles.name' => Role::ADMIN]);
            });
        $this->assertSame(1, $admins->all()->count());
        $admin = $admins->first();
        $this->assertSame($userName, $admin->get('username'));
        $this->assertSame($firstName, $admin->profile->first_name);
        $this->assertSame($lastName, $admin->profile->last_name);
        $this->assertFalse($admin->get('active'));
        $this->assertEmailQueueCount(1);
        $this->assertEmailInBatchContains("Welcome to passbolt, $firstName!", $userName);
    }
}
