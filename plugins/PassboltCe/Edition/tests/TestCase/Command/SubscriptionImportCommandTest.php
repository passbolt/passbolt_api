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
namespace Passbolt\Edition\Test\TestCase\Command;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\Core\Exception\CakeException;
use Cake\I18n\DateTime;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Model\Table\EditionOrganizationTable;
use Passbolt\Edition\Service\EditionManager;
use Passbolt\Edition\Test\Factory\EditionOrganizationSettingFactory;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionRecordNotFoundException;
use Passbolt\Subscription\Model\Entity\Subscription;
use Passbolt\Subscription\Service\Subscriptions\SubscriptionKeyGetService;
use Passbolt\Subscription\Test\DummySubscriptionTrait;

/**
 * @uses \Passbolt\Edition\Command\SubscriptionImportCommand
 */
class SubscriptionImportCommandTest extends AppTestCase
{
    use ConsoleIntegrationTestTrait;
    use DummySubscriptionTrait;
    use LocatorAwareTrait;

    /**
     * @var \Passbolt\Subscription\Model\Table\SubscriptionsTable
     */
    protected $Subscriptions;

    /**
     * @var \Passbolt\Edition\Model\Table\EditionOrganizationTable
     */
    protected EditionOrganizationTable $EditionOrganization;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpPathAndPublicSubscriptionKey();
        $this->Subscriptions = $this->fetchTable('Passbolt/Subscription.Subscriptions');
        /** @var \Passbolt\Edition\Model\Table\EditionOrganizationTable $editionTable */
        $editionTable = $this->fetchTable('Passbolt/Edition.EditionOrganization');
        $this->EditionOrganization = $editionTable;
    }

    public function testSubscriptionImportCommandHelp(): void
    {
        $this->exec('passbolt subscription_import -h');
        $this->assertExitSuccess();
        $this->assertOutputContains('Import a subscription key');
        $this->assertOutputContains('cake passbolt subscription_import');
    }

    public function testSubscriptionImportCommand_Success_On_Default_File(): void
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
        $this->assertEditionIs(EditionDto::EDITION_PRO);
    }

    public function testSubscriptionImportCommand_Success_On_Valid_File(): void
    {
        UserFactory::make()->admin()->persist();
        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported in the database.');

        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
        $this->assertEditionIs(EditionDto::EDITION_PRO);
    }

    public function testSubscriptionImportCommand_Success_On_Valid_File_With_Existing_Key(): void
    {
        $this->persistExpiredSubscription();

        UserFactory::make()->admin()->persist();
        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported in the database.');

        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
        $this->assertEditionIs(EditionDto::EDITION_PRO);
    }

    public function testSubscriptionImportCommand_Success_AlreadyPro(): void
    {
        UserFactory::make()->admin()->persist();
        EditionOrganizationSettingFactory::make()->setField('value', EditionDto::EDITION_PRO)->persist();

        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);

        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported in the database.');
        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
        $this->assertEditionIs(EditionDto::EDITION_PRO);
    }

    public function testSubscriptionImportCommand_Skips_WhenInstanceWasExplicitlyDowngraded(): void
    {
        // Reproduces Cedric's scenario: admin downgrades from the UI (which
        // leaves `value='ce'` AND advances `modified` past `created`), then
        // Docker restarts with the subscription key file still mounted. The
        // import must not silently re-upgrade the instance.
        //
        // Simulate EditionManager having booted on this row state — that is
        // what production sees in Configure after Application::bootstrap().
        Configure::write('passbolt.edition', EditionDto::EDITION_CE);
        Configure::write(
            EditionManager::CONFIGURE_KEY_LAST_CHANGE_DATETIME,
            new DateTime('2024-06-15 12:34:56'),
        );
        UserFactory::make()->admin()->persist();
        EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_CE)
            ->setField('created', new DateTime('2024-01-01 10:00:00'))
            ->setField('modified', new DateTime('2024-06-15 12:34:56'))
            ->persist();

        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);

        $this->assertExitSuccess();
        $this->assertErrorContains('instance was explicitly downgraded to CE');
        $this->assertEditionIs(EditionDto::EDITION_CE);
        // No subscription row was created — the mounted key was ignored.
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    public function testSubscriptionImportCommand_AllowsUpgrade_WhenCeRowIsInDefaultStateFromMigration(): void
    {
        // V5130PopulateEdition writes `value='ce'` on existing CE installs as
        // their initial state — `created == modified` so the change timestamp
        // is null. Mounting a key on such an instance is a legitimate upgrade
        // signal, NOT a "post-downgrade undo" attempt — the import must
        // proceed.
        Configure::write('passbolt.edition', EditionDto::EDITION_CE);
        UserFactory::make()->admin()->persist();
        $now = DateTime::now();
        EditionOrganizationSettingFactory::make()
            ->setField('value', EditionDto::EDITION_CE)
            ->setField('created', $now)
            ->setField('modified', $now)
            ->persist();

        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);

        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported in the database.');
        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
        $this->assertEditionIs(EditionDto::EDITION_PRO);
    }

    public function testSubscriptionImportCommand_Error_RollbackKeyOnEditionFailure(): void
    {
        UserFactory::make()->admin()->persist();

        $editionTable = TableRegistry::getTableLocator()->get('Passbolt/Edition.EditionOrganization');
        $editionTable->getEventManager()->on('Model.beforeSave', function (): void {
            throw new CakeException('Simulated setToPro failure.');
        });

        $file = $this->getValidSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);

        $this->assertExitError();
        $this->assertEditionRowAbsent();
        // assert subscription key didn't save
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    public function testSubscriptionImportCommand_Error_On_Non_Valid_Subscription_File(): void
    {
        UserFactory::make()->admin()->persist();
        $file = $this->getExpiredSubscriptionFileName();
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitError();
        $this->assertOutputContains('The subscription is expired.');

        $this->assertEditionRowAbsent();
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    public function testSubscriptionImportCommand_Error_On_Non_Existent_Subscription_File(): void
    {
        UserFactory::make()->admin()->persist();
        $file = 'blah';
        $this->exec('passbolt subscription_import -f ' . $file);
        $this->assertExitError();
        $this->assertOutputContains("The file {$file} could not be found.");

        $this->assertEditionRowAbsent();
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    public function testSubscriptionImportCommand_Success_On_Valid_Subscription_Text(): void
    {
        UserFactory::make()->admin()->persist();
        $text = $this->getValidSubscriptionKey();
        $this->exec('passbolt subscription_import -t ' . $text);
        $this->assertExitSuccess();
        $this->assertOutputContains('successfully imported');

        $this->assertInstanceOf(Subscription::class, $this->Subscriptions->getOrFail());
        $this->assertEditionIs(EditionDto::EDITION_PRO);
    }

    public function testSubscriptionImportCommand_Error_On_Non_Valid_Subscription_Text(): void
    {
        UserFactory::make()->admin()->persist();
        $text = $this->getExpiredSubscriptionKey();
        $this->exec('passbolt subscription_import -t ' . $text);
        $this->assertExitError();
        $this->assertOutputContains('The subscription is expired.');

        $this->assertEditionRowAbsent();
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    public function testSubscriptionImportCommand_Error_On_Non_Existent_Subscription_Text(): void
    {
        UserFactory::make()->admin()->persist();
        $text = '🔥';
        $this->exec('passbolt subscription_import -t ' . $text);
        $this->assertExitError();

        $this->assertEditionRowAbsent();
        $this->expectException(SubscriptionRecordNotFoundException::class);
        $this->Subscriptions->getOrFail();
    }

    private function assertEditionIs(string $expected): void
    {
        $row = $this->EditionOrganization->find()
            ->where(['property' => EditionOrganizationTable::PROPERTY_NAME])
            ->firstOrFail();
        $this->assertSame($expected, $row->get('value'));
    }

    private function assertEditionRowAbsent(): void
    {
        $row = $this->EditionOrganization->find()
            ->where(['property' => EditionOrganizationTable::PROPERTY_NAME])
            ->first();
        $this->assertNull($row, 'Expected no edition row to be persisted, but one was found.');
    }
}
