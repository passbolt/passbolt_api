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
namespace Passbolt\Ee\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;
use Passbolt\Ee\Service\SubscriptionKeyGetService;
use Passbolt\Ee\Test\Lib\DummySubscriptionTrait;

/**
 * @uses \Passbolt\Ee\Command\SubscriptionCheckCommand
 */
class SubscriptionCheckCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use DummySubscriptionTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->useCommandRunner();
        $this->setUpPathAndPublicSubscriptionKey();
    }

    /**
     * Basic help test
     */
    public function testSubscriptionCheckCommandHelp()
    {
        $this->exec('passbolt subscription_check -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Check the subscription.');
        $this->assertOutputContains('cake passbolt subscription_check');
    }

    /**
     * Check that the license_check command is aliased for backward compatibility
     */
    public function testLicenseCheckCommandHelp()
    {
        $this->exec('passbolt license_check -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Check the subscription.');
        $this->assertOutputContains('cake passbolt license_check');
    }

    /**
     * Basic test on existing legacy subscription file
     */
    public function testSubscriptionCheckCommand_Success_On_Legacy_File()
    {
        $subscriptionFileExists = file_exists(SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
        $subscriptionFileIsWritable = is_writable(SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
        $tempFile = TMP . 'temp_subscription_file';
        $makeBackup = false;
        if ($subscriptionFileExists) {
            if (!$subscriptionFileIsWritable) {
                $this->fail('The following file is not writable ' . SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
            }
            $makeBackup = true;
            rename(SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE, $tempFile);
        }

        copy($this->getValidSubscriptionFileName(), SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);

        $this->exec('passbolt subscription_check');
        $this->assertExitSuccess();
        $this->assertOutputContains('Below are your subscription key details');
        if ($makeBackup) {
            rename($tempFile, SubscriptionKeyGetService::LEGACY_SUBSCRIPTION_FILE);
        }
    }

    /**
     * Basic test on valid subscription entity
     */
    public function testSubscriptionCheckCommand_Success_On_Valid_Persisted_Subscription()
    {
        $this->persistValidSubscription();
        $this->exec('passbolt subscription_check');
        $this->assertExitSuccess();
        $this->assertOutputContains('Below are your subscription key details');
    }

    /**
     * Basic test on non valid subscription entity
     */
    public function testSubscriptionCheckCommand_Success_On_Non_Valid_Persisted_Subscription()
    {
        $this->persistInvalidSubscription();
        $this->exec('passbolt subscription_check');
        $this->assertExitError();
        $this->assertOutputContains('Subscription key signature error.');
    }

    /**
     * Basic test on expired subscription entity
     */
    public function testSubscriptionCheckCommand_Success_On_Expired_Persisted_Subscription()
    {
        $this->persistExpiredSubscription();
        $this->exec('passbolt subscription_check');
        $this->assertExitError();
        $this->assertOutputContains('The subscription is expired.');
    }

    /**
     * Basic test on legacy subscription file for too few users
     */
    public function testSubscriptionCheckCommand_Success_On_Users_Limited_Persisted_Subscription()
    {
        UserFactory::make(3)->user()->persist();
        $this->persistValidSubscription();
        $this->exec('passbolt subscription_check');
        $this->assertExitError();
        $this->assertOutputContains('The users limit is exceeded.');
    }
}
