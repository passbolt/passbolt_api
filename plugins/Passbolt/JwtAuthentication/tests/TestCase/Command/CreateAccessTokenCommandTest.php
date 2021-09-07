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
 * @since         3.3.0
 */
namespace Passbolt\JwtAuthentication\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use App\Test\Lib\Utility\PassboltCommandTestTrait;
use App\Utility\Application\FeaturePluginAwareTrait;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\SkipTablesTruncation;
use Passbolt\JwtAuthentication\Service\AccessToken\JwtKeyPairService;

/**
 * @uses \Passbolt\JwtAuthentication\Command\CreateAccessTokenCommand
 */
class CreateAccessTokenCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use FeaturePluginAwareTrait;
    use PassboltCommandTestTrait;
    use SkipTablesTruncation;

    public static $userId;
    public static $username;

    public static function setUpBeforeClass(): void
    {
        $user = TableRegistry::getTableLocator()->get('Users')->find()->first() ?? UserFactory::make()->user()->persist();
        self::$userId = $user->id;
        self::$username = $user->username;
    }

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        (new JwtKeyPairService())->createKeyPair();
        $this->useCommandRunner();
        $this->enableFeaturePlugin('JwtAuthentication');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $this->disableFeaturePlugin('JwtAuthentication');
    }

    /**
     * Basic help test
     */
    public function testCreateAccessTokenCommandHelp()
    {
        $this->exec('passbolt create_access_token -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Create a JSON Web Token.');
        $this->assertOutputContains('cake passbolt create_access_token');
    }

    public function testCreateAccessTokenCommandWithUsername()
    {
        $this->exec('passbolt create_access_token -u ' . self::$username);
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . self::$username . ' valid 5 minutes:');
    }

    public function testCreateAccessTokenCommandWithUserId()
    {
        $this->exec('passbolt create_access_token -i ' . self::$userId);
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . self::$username . ' valid 5 minutes:');
    }

    public function testCreateAccessTokenCommandWithExpiry()
    {
        $expiry = 10;
        $this->exec('passbolt create_access_token -i ' . self::$userId . ' -e ' . $expiry);
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . self::$username . " valid {$expiry} minutes:");
    }

    public function testCreateAccessTokenCommandWithExpiryInWordFormat()
    {
        $expiry = '5 seconds';
        $this->exec('passbolt create_access_token -i ' . self::$userId . ' -e "' . $expiry . '"');
        $this->assertExitSuccess();
        $this->assertOutputContains('Access token for ' . self::$username . " valid {$expiry}:");
    }

    public function testCreateAccessTokenCommandWithNoUserParams()
    {
        $this->exec('passbolt create_access_token');
        $this->assertExitError();
    }

    public function testCreateAccessTokenCommandWithWrongUsername()
    {
        $this->exec('passbolt create_access_token -u foo');
        $this->assertExitError();
    }

    public function testCreateAccessTokenCommandInProdMod()
    {
        $debug = Configure::read('debug');
        Configure::write('debug', false);
        $this->exec('passbolt create_access_token -i ' . self::$userId);
        $this->assertExitError();
        Configure::write('debug', $debug);
    }
}
