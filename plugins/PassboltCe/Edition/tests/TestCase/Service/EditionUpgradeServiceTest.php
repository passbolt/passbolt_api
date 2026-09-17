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
 * @since         5.13.0
 */
namespace Passbolt\Edition\Test\TestCase\Service;

use App\Test\Lib\AppTestCaseV5;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\ForbiddenException;
use Cake\ORM\TableRegistry;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Model\Table\EditionOrganizationTable;
use Passbolt\Edition\Service\EditionUpgradeService;
use Passbolt\Edition\Test\Factory\EditionOrganizationSettingFactory;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionException;
use Passbolt\Subscription\Model\Dto\SubscriptionKeyDto;
use Passbolt\Subscription\Test\DummySubscriptionTrait;
use Passbolt\Subscription\Test\SubscriptionFactory;
use RuntimeException;

/**
 * @covers \Passbolt\Edition\Service\EditionUpgradeService
 */
class EditionUpgradeServiceTest extends AppTestCaseV5
{
    use DummySubscriptionTrait;

    private EditionUpgradeService $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpPathAndPublicSubscriptionKey();
        $this->sut = new EditionUpgradeService();
        EventManager::instance()->setEventList(new EventList());
    }

    public function tearDown(): void
    {
        EventManager::instance()->getEventList()?->flush();
        unset($this->sut);
        parent::tearDown();
    }

    public function testEditionUpgradeService_Success_PersistsKeySetsEditionAndDispatchesEvent(): void
    {
        $uac = $this->mockAdminAccessControl();

        $result = $this->sut->upgrade($this->getValidSubscriptionKey(), $uac);

        $this->assertInstanceOf(SubscriptionKeyDto::class, $result);
        $editionRow = EditionOrganizationSettingFactory::firstOrFail(
            ['property' => EditionOrganizationTable::PROPERTY_NAME],
        );
        $this->assertSame(EditionDto::EDITION_PRO, $editionRow->get('value'));
        $this->assertSame(1, $this->countSubscriptionRows());
        $this->assertEventFiredWith(EditionUpgradeService::EVENT_NAME, 'subscriptionKey', $result);
        $this->assertEventFiredWith(EditionUpgradeService::EVENT_NAME, 'uac', $uac);
    }

    public function testEditionUpgradeService_Forbidden_NonAdminUac(): void
    {
        $uac = $this->mockUserAccessControl();
        $this->expectException(ForbiddenException::class);
        $this->sut->upgrade($this->getValidSubscriptionKey(), $uac);
    }

    public function testEditionUpgradeService_InvalidKey_LeavesInstanceOnCe(): void
    {
        $uac = $this->mockAdminAccessControl();

        try {
            $this->sut->upgrade('not-a-valid-key', $uac);
            $this->fail('Expected SubscriptionException to be thrown.');
        } catch (SubscriptionException $_) {
            // expected
        }

        $this->assertSame(0, EditionOrganizationSettingFactory::find()
            ->where(['property' => EditionOrganizationTable::PROPERTY_NAME])
            ->count());
        $this->assertSame(0, $this->countSubscriptionRows());
        $this->assertFalse(EventManager::instance()->getEventList()->hasEvent(EditionUpgradeService::EVENT_NAME));
    }

    public function testEditionUpgradeService_Rollback_InCaseOfFailure(): void
    {
        $uac = $this->mockAdminAccessControl();
        $editionTable = TableRegistry::getTableLocator()->get('Passbolt/Edition.EditionOrganization');
        $editionTable->getEventManager()->on('Model.beforeSave', function (): void {
            throw new RuntimeException('Simulated setToPro failure.');
        });

        try {
            $this->sut->upgrade($this->getValidSubscriptionKey(), $uac);
            $this->fail('Expected RuntimeException to be thrown.');
        } catch (RuntimeException $e) {
            // expected
        }

        $this->assertSame(0, $this->countSubscriptionRows());
        $this->assertSame(0, EditionOrganizationSettingFactory::find()
            ->where(['property' => EditionOrganizationTable::PROPERTY_NAME])
            ->count());
        $this->assertFalse(EventManager::instance()->getEventList()->hasEvent(EditionUpgradeService::EVENT_NAME));
    }

    public function testEditionUpgradeService_CE_PublicKeyConfigPublishedByEditionPlugin(): void
    {
        // Check Edition plugin loaded in CE and PRO both.
        // Must publish the subscription public key path so the upgrade-to-PRO flow can verify signatures.
        Configure::delete('passbolt.plugins.edition.subscriptionKey.public');
        Configure::load('Passbolt/Edition.config', 'default', true);

        $publicKeyPath = Configure::read('passbolt.plugins.edition.subscriptionKey.public');

        $this->assertIsString($publicKeyPath);
        $this->assertNotSame('', $publicKeyPath);
        $this->assertFileExists($publicKeyPath);
    }

    private function countSubscriptionRows(): int
    {
        /** @var \Passbolt\Subscription\Model\Table\SubscriptionsTable $subscriptionsTable */
        $subscriptionsTable = TableRegistry::getTableLocator()->get('Passbolt/Subscription.Subscriptions');

        return SubscriptionFactory::find()
            ->where(['property' => $subscriptionsTable->getProperty()])
            ->count();
    }
}
