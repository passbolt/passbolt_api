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
 * @since         3.2.0
 */
namespace Passbolt\Subscription\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\ORM\Locator\LocatorAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException;
use Passbolt\Subscription\Model\Entity\Subscription;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;
use Passbolt\Subscription\Test\DummySubscriptionTrait;

/**
 * @uses \Passbolt\Subscription\Command\SubscriptionCheckCommand
 */
class SubscriptionImportCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use DummySubscriptionTrait;
    use LocatorAwareTrait;
    use TruncateDirtyTables;

    /**
     * @var \Passbolt\Subscription\Model\Table\SubscriptionsTable
     */
    protected $Subscriptions;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->setUpPathAndPublicSubscriptionKey();
        $this->Subscriptions = $this->fetchTable('Passbolt/Subscription.Subscriptions');
        $this->enableFeaturePlugin('Subscription');
    }

    /**
     * Basic help test
     */
    public function testSubscriptionImportCommandHelp()
    {
        $this->exec('passbolt subscription_import -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Import a subscription key');
        $this->assertOutputContains('cake passbolt subscription_import');
    }

    /**
     * Basic test on existing legacy subscription file
     */
    public function testSubscriptionImportCommand_Success_On_Default_File()
    {
        UserFactory::make()->admin()->persist();

        $this->makeExistingKeyBackup();

        copy($this->getValidSubscriptionFileName(), SubscriptionKeyGetService::SUBSCRIPTION_FILE);

        $this->exec('passbolt subscription_import');

        unlink(SubscriptionKeyGetService::SUBSCRIPTION_FILE);

        $this->restoreExistingKeyBackup();

        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported in the database.');
        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
    }

    /**
     * Basic test on valid subscription file
     */
    public function testSubscriptionImportCommand_Success_On_Valid_File()
    {
        UserFactory::make()->admin()->persist();
        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported in the database.');

        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
    }

    /**
     * Basic test on valid subscription file with existing key
     */
    public function testSubscriptionImportCommand_Success_On_Valid_File_With_Existing_Key()
    {
        $this->persistExpiredSubscription();

        UserFactory::make()->admin()->persist();
        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported in the database.');

        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
    }

    /**
     * Basic test on non valid subscription file
     */
    public function testSubscriptionImportCommand_Error_On_Non_Valid_Subscription_File()
    {
        UserFactory::make()->admin()->persist();
        $file = $this->getExpiredSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitError();
        $this->assertOutputContains('The subscription is expired.');

        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    /**
     * Basic test on non existing subscription file
     */
    public function testSubscriptionImportCommand_Error_On_Non_Existent_Subscription_File()
    {
        UserFactory::make()->admin()->persist();
        $file = 'blah';
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitError();
        $this->assertOutputContains("The file {$file} could not be found.");

        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    /**
     * Basic test on expired subscription
     */
    public function testSubscriptionImportCommand_Success_On_Valid_Subscription_Text()
    {
        UserFactory::make()->admin()->persist();
        $text = $this->getValidSubscriptionKey();
        $this->exec('passbolt subscription_import -t ' . $text);
        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported');

        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
    }

    /**
     * Basic test on expired subscription
     */
    public function testSubscriptionImportCommand_Error_On_Non_Valid_Subscription_Text()
    {
        UserFactory::make()->admin()->persist();
        $text = $this->getExpiredSubscriptionKey();
        $this->exec('passbolt subscription_import -t ' . $text);
        $this->assertExitError();
        $this->assertOutputContains('The subscription is expired.');

        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    /**
     * Basic test on invalid subscription
     */
    public function testSubscriptionImportCommand_Error_On_Non_Existent_Subscription_Text()
    {
        UserFactory::make()->admin()->persist();
        $text = '🔥';
        $this->exec('passbolt subscription_import -t ' . $text);
        $this->assertExitError();

        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }
}
