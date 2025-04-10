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

use App\Model\Table\RolesTable;
use App\Model\Validation\EmailValidationRule;
use App\Service\Healthcheck\Environment\NextMinPhpVersionHealthcheck;
use App\Service\Healthcheck\Environment\PhpVersionHealthcheck;
use App\Service\Healthcheck\HealthcheckServiceCollector;
use App\Test\Factory\RoleFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\HealthcheckRequestTestTrait;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\Datasource\ConnectionManager;
use Cake\Http\Client;
use Cake\Http\TestSuite\HttpClientTrait;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Passbolt\SelfRegistration\SelfRegistrationPlugin;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

class HealthcheckCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use HealthcheckRequestTestTrait;
    use PassboltCommandTestTrait;
    use SelfRegistrationTestTrait;
    use HttpClientTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->mockProcessUserService('www-data');
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        parent::tearDown();

        // Reset state
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
        $cliDomains = [
            HealthcheckServiceCollector::DOMAIN_APPLICATION,
            HealthcheckServiceCollector::DOMAIN_CONFIG_FILES,
            HealthcheckServiceCollector::DOMAIN_CORE,
            HealthcheckServiceCollector::DOMAIN_DATABASE,
            HealthcheckServiceCollector::DOMAIN_ENVIRONMENT,
            HealthcheckServiceCollector::DOMAIN_GPG,
            HealthcheckServiceCollector::DOMAIN_JWT,
            HealthcheckServiceCollector::DOMAIN_SMTP_SETTINGS,
            HealthcheckServiceCollector::DOMAIN_SSL,
        ];
        foreach ($cliDomains as $cliDomain) {
            $this->assertOutputContains("--$cliDomain");
        }
        // Additional option for database domain
        $this->assertOutputContains('--datasource');
    }

    /**
     * Will fail if run as root
     */
    public function testHealthcheckCommandRoot()
    {
        $this->assertCommandCannotBeRunAsRootUser('healthcheck');
    }

    /**
     * Basic test
     */
    public function testHealthcheckCommand_All_Checks()
    {
        $clientStub = $this->getMockBuilder(Client::class)->onlyMethods(['get'])->getMock();
        $clientStub->method('get')->willThrowException(new CakeException());
        // Ensure that since the full base URL is not reachable, the SSL healthchecks to not query the call URl, as it is unnecessary
        $clientStub->expects($this->once())->method('get');
        $this->mockService('fullBaseUrlReachableClient', function () use ($clientStub) {
            return $clientStub;
        });
        $this->mockService('sslHealthcheckClient', function () use ($clientStub) {
            return $clientStub;
        });

        $this->exec('passbolt healthcheck -d test');
        $this->assertExitSuccess();
        $this->assertOutputContains('<warning>[WARN] SSL peer certificate does not validate.</warning>');
        $this->assertOutputContains('<warning>[WARN] Hostname does not match when validating certificates.</warning>');
        // Since the tests run with debug on, here will always be at least one error in the healthcheck.
        $this->assertOutputContains('error(s) found. Hang in there!');
    }

    public function testHealthcheckCommand_Environment_Unhappy_Path()
    {
        Configure::write(PhpVersionHealthcheck::PHP_MIN_VERSION_CONFIG, '40');
        Configure::write(NextMinPhpVersionHealthcheck::PHP_NEXT_MIN_VERSION_CONFIG, '50');
        $this->exec('passbolt healthcheck -d test --environment');

        $this->assertExitSuccess();

        $this->assertOutputContains('[FAIL] PHP version is too low, passbolt need PHP 40 or higher.');
        $this->assertOutputContains('[WARN] PHP version less than 50 will soon be not supported by passbolt, so consider upgrading your operating system or PHP environment.');
    }

    public function testHealthcheckCommand_Application_Happy_Path()
    {
        Configure::write('passbolt.version', '4.1.1');
        Configure::write('passbolt.remote.version', 'v4.1.0');
        Configure::write('passbolt.ssl.force', true);
        Configure::write('App.fullBaseUrl', 'https://passbolt.local');
        Configure::write('passbolt.selenium.active', false);
        Configure::write('passbolt.meta.robots', 'noindex');
        Configure::write(EmailValidationRule::MX_CHECK_KEY, true);
        Configure::write('passbolt.js.build', 'production');
        Configure::write('passbolt.email.send', '');
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);

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
        $this->assertOutputContains('No error found. Nice one, sparky!');
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
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
        $this->setSelfRegistrationSettingsData();
        Configure::write(EmailValidationRule::MX_CHECK_KEY, false);
        Configure::write('passbolt.js.build', 'test');
        Configure::write('passbolt.email.send.comment.add', false);

        $this->exec('passbolt healthcheck -d test --application');

        $this->assertExitSuccess();
        $this->assertOutputContains('This installation is not up to date. Currently using 1.0.0 and it should be 9.9.9.');
        $this->assertOutputContains('<info>[HELP]</info> See https://www.passbolt.com/help/tech/update');
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
        $this->assertOutputContains('<error>[FAIL] The application is not able to connect to the database.');
        $this->assertOutputContains('<error>[FAIL] No table found.</error>');
        $this->assertOutputContains('<error>[FAIL] No default content found.</error>');
        $this->assertOutputContains('3 error(s) found. Hang in there');
        /**
         * Clean up: Drop connection created for testing and reinstate default alias to 'test'.
         *
         * @see https://book.cakephp.org/4/en/development/testing.html#test-connections
         */
        ConnectionManager::alias('test', 'default');
        ConnectionManager::drop('invalid');
    }

    public function testHealthcheckCommand_Database_Happy_Path()
    {
        RoleFactory::make(RolesTable::ALLOWED_ROLE_NAMES)->persist();

        $this->exec('passbolt healthcheck --database');
        $this->assertExitSuccess();

        $this->assertOutputContains('<success>[PASS]</success> The application is able to connect to the database');
        $this->assertOutputContains(' tables found.');
        $this->assertOutputContains('<success>[PASS]</success> Some default content is present');
    }

    // Note: This will pass when OLD way is removed

    public function testHealthcheckCommand_Core_Happy_Path()
    {
        $this->mockClientGet(
            Router::url('/healthcheck/status.json', true),
            $this->newClientResponse(200, [], json_encode(['body' => 'OK']))
        );

        $this->exec('passbolt healthcheck --core');

        $this->assertExitSuccess();
        $this->assertOutputContains('<success>[PASS]</success> Cache is working.');
        $this->assertOutputContains('<error>[FAIL] Debug mode is on.</error>');
        $this->assertOutputContains('<info>[HELP]</info> Set debug to false in ' . CONFIG . 'passbolt.php');
        $this->assertOutputContains('<success>[PASS]</success> Unique value set for security.salt');
        $this->assertOutputContains('<success>[PASS]</success> Full base url is set to ' . Configure::read('App.fullBaseUrl'));
        $this->assertOutputContains('<success>[PASS]</success> App.fullBaseUrl validation OK.');
        $this->assertOutputContains('<success>[PASS]</success> /healthcheck/status is reachable.');
        $this->assertOutputContains('<error>[FAIL] 1 error(s) found. Hang in there!</error>');
    }

    public function testHealthcheckCommand_Core_Unhappy_Path()
    {
        $this->mockClientGet(
            Router::url('/healthcheck/status.json', true),
            $this->newClientResponse(404)
        );

        $this->exec('passbolt healthcheck --core');
        $this->assertExitSuccess();
        $this->assertOutputContains('<error>[FAIL] Could not reach the /healthcheck/status with the url specified in App.fullBaseUrl</error>');
        $this->assertOutputContains('<info>[HELP]</info> Check that the domain name is correct in ' . CONFIG . 'passbolt.php');
        $this->assertOutputContains('<info>[HELP]</info> Check the network settings');
        $this->assertOutputContains('<error>[FAIL] 2 error(s) found. Hang in there!</error>');
    }

    public function testHealthcheckCommand_Gpg_Happy_Path()
    {
        $gnupgHome = getenv('GNUPGHOME') ?: '/root/.gnupg';
        $this->exec('passbolt healthcheck --gpg');

        $this->assertExitSuccess();
        $this->assertOutputContains('<success>[PASS]</success> PHP GPG Module is installed and loaded.');
        $this->assertOutputContains('<success>[PASS]</success> The environment variable GNUPGHOME is set to ' . $gnupgHome);
        $this->assertOutputContains('<success>[PASS]</success> The directory ' . $gnupgHome . ' containing the keyring is writable by the webserver user.');
        $this->assertOutputContains('<error>[FAIL] Do not use the default OpenPGP key for the server.</error>');
        $this->assertOutputContains('<success>[PASS]</success> The public key file is defined in ' . CONFIG . 'passbolt.php and readable.');
        $this->assertOutputContains('<success>[PASS]</success> The private key file is defined in ' . CONFIG . 'passbolt.php and readable.');
        $this->assertOutputContains('<success>[PASS]</success> The server key fingerprint matches the one defined in ');
        $this->assertOutputContains('<success>[PASS]</success> The server public key defined in the ' . CONFIG . 'passbolt.php (or environment variables) is in the keyring.');
        $this->assertOutputContains('<success>[PASS]</success> There is a valid email id defined for the server key.');
        $this->assertOutputContains('<success>[PASS]</success> The public key can be used to encrypt a message.');
        $this->assertOutputContains('<success>[PASS]</success> The public key can be used to encrypt a message.');
        $this->assertOutputContains('<success>[PASS]</success> The private key can be used to sign a message.');
        $this->assertOutputContains('<success>[PASS]</success> The public and private keys can be used to encrypt and sign a message.');
        $this->assertOutputContains('<success>[PASS]</success> The private key can be used to decrypt a message.');
        $this->assertOutputContains('<success>[PASS]</success> The private key can be used to decrypt and verify a message.');
        $this->assertOutputContains('<success>[PASS]</success> The public key can be used to verify a signature.');
        $this->assertOutputContains('<success>[PASS]</success> The server public key format is Gopengpg compatible.');
        $this->assertOutputContains('<success>[PASS]</success> The server private key format is Gopengpg compatible.');
    }

    public function testHealthcheckCommand_Gpg_Failing_Path()
    {
        Configure::write('passbolt.gpg.serverKey.fingerprint', 'foo');
        $this->exec('passbolt healthcheck --gpg');

        $this->assertExitSuccess();
        $this->assertOutputContains('<error>[FAIL] The server key fingerprint doesn\'t match the one defined in ');
    }
}
