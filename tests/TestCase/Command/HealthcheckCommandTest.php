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

use App\Command\HealthcheckCommand;
use App\Model\Validation\EmailValidationRule;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class HealthcheckCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use PassboltCommandTestTrait;
    use SelfRegistrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        HealthcheckCommand::$isUserRoot = false;
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();

        TableRegistry::getTableLocator()->clear();
    }

    /**
     * Basic help test
     */
    public function testHealthcheckCommandHelp()
    {
        $this->exec('passbolt healthcheck -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Check the configuration of this installation and associated environment.');
        $this->assertOutputContains('cake passbolt healthcheck');
        // Ensure that all checks are displayed in the help
        foreach (HealthcheckCommand::ALL_HEALTH_CHECKS as $check) {
            $this->assertOutputContains($check);
        }
    }

    /**
     * Will fail if run as root
     */
    public function testHealthcheckCommandRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser(HealthcheckCommand::class);
    }

    /**
     * Basic test
     */
    public function testHealthcheckCommand()
    {
        $this->exec('passbolt healthcheck -d test');
        $this->assertExitSuccess();
        // Since the tests run with debug on, here will always be at least one error in the healthcheck.
        $this->assertOutputContains('error(s) found. Hang in there!');
    }

    public function testHealthcheckCommand_Environment()
    {
        $this->exec('passbolt healthcheck -d test --environment');

        $this->assertExitSuccess();

        $expectedOutput = 'No error found. Nice one sparky!';
        if (version_compare(PHP_VERSION, '7.4', '<')) {
            $expectedOutput = 'error(s) found. Hang in there!';
        }
        $this->assertOutputContains($expectedOutput);
    }

    public function testHealthcheckCommand_Application_Happy_Path()
    {
        Configure::write('passbolt.version', '9.9.9');
        Configure::write('passbolt.remote.version', '9.9.9');
        Configure::write('passbolt.ssl.force', true);
        Configure::write('App.fullBaseUrl', 'https://passbolt.local');
        Configure::write('passbolt.selenium.active', false);
        Configure::write('passbolt.meta.robots', 'noindex');
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        Configure::write('passbolt.js.build', 'production');
        Configure::write('passbolt.email.send', '');

        $this->exec('passbolt healthcheck -d test --application');

        $this->assertExitSuccess();
        $this->assertOutputContains('Using latest passbolt version');
        $this->assertOutputContains('Passbolt is configured to force SSL use.');
        $this->assertOutputContains('App.fullBaseUrl is set to HTTPS.');
        $this->assertOutputContains('Selenium API endpoints are disabled.');
        $this->assertOutputContains('Search engine robots are told not to index content.');
        $this->assertOutputContains('The Self Registration plugin is enabled.');
        $this->assertOutputContains('<info>[INFO]</info> Registration is closed, only administrators can add users.');
        $this->assertOutputContains('Host availability will be checked.');
        $this->assertOutputContains('Serving the compiled version of the javascript app.');
        $this->assertOutputContains('All email notifications will be sent.');
        $this->assertOutputContains('No error found. Nice one sparky!');
    }

    public function testHealthcheckCommand_Application_Unhappy_Path()
    {
        Configure::write('passbolt.version', '1.0.0');
        Configure::write('passbolt.remote.version', '9.9.9');
        Configure::write('passbolt.ssl.force', false);
        Configure::write('App.fullBaseUrl', 'http://passbolt.local');
        Configure::write('passbolt.selenium.active', true);
        Configure::write('passbolt.meta.robots', '');
        Configure::write('passbolt.registration.public', true);
        $this->setSelfRegistrationSettingsData();
        Configure::write(EmailValidationRule::MX_CHECK_KEY, false);
        Configure::write('passbolt.js.build', 'test');
        Configure::write('passbolt.email.send', 'false');

        $this->exec('passbolt healthcheck -d test --application');

        $this->assertExitSuccess();
        $this->assertOutputContains('This installation is not up to date');
        $this->assertOutputContains('Passbolt is not configured to force SSL use.');
        $this->assertOutputContains('App.fullBaseUrl is not set to HTTPS.');
        $this->assertOutputContains('Selenium API endpoints are active.');
        $this->assertOutputContains('Search engine robots are not told not to index content.');
        $this->assertOutputContains('<info>[INFO]</info> The self registration provider is: Email domain safe list.');
        $this->assertOutputContains('You may remove the "passbolt.registration.public" setting.');
        $this->assertOutputContains('Host availability checking is disabled.');
        $this->assertOutputContains('Using non-compiled Javascript.');
        $this->assertOutputContains('Some email notifications are disabled by the administrator.');
        $this->assertOutputContains('error(s) found');
    }

    public function testHealthcheckCommand_Database_ConnectionError()
    {
        /**
         * Create a dummy database connection, so we can get error.
         *
         * Here we have to use alias since we are only allowing 'default' and 'test' connection to run healthcheck on.
         */
        ConnectionManager::setConfig('invalid', ['url' => 'mysql://foo:bar@localhost/invalid_database']);
        ConnectionManager::alias('invalid', 'default');

        $this->exec('passbolt healthcheck -d default --database');

        $this->assertExitSuccess();
        $this->assertOutputContains('not able to connect to the database');
        $this->assertOutputContains('No table found');
        $this->assertOutputContains('No default content found');
        $this->assertOutputContains('database schema is not up to date');
        $this->assertOutputContains('4 error(s) found. Hang in there');
        /**
         * Clean up: Drop connection created for testing and reinstate default alias to 'test'.
         *
         * @see https://book.cakephp.org/4/en/development/testing.html#test-connections
         */
        ConnectionManager::alias('test', 'default');
        ConnectionManager::drop('invalid');
    }
}
