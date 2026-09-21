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
namespace Passbolt\Edition\Test\TestCase\Controller;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\UserAccessControlTrait;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Edition\Model\Dto\EditionDto;
use Passbolt\Edition\Model\Table\EditionOrganizationTable;
use Passbolt\Edition\Notification\Email\EditionUpgradeEmailRedactor;
use Passbolt\Edition\Service\EditionSetService;
use Passbolt\Edition\Service\EditionUpgradeService;
use Passbolt\Edition\Test\Factory\EditionOrganizationSettingFactory;
use Passbolt\Subscription\Error\Exception\Subscriptions\SubscriptionSignatureException;
use Passbolt\Subscription\Test\DummySubscriptionTrait;
use Passbolt\Subscription\Test\SubscriptionFactory;

/**
 * @covers \Passbolt\Edition\Controller\EditionSubscriptionsCreateController
 */
class EditionSubscriptionsCreateControllerTest extends AppIntegrationTestCase
{
    use DummySubscriptionTrait;
    use EmailQueueTrait;
    use LocatorAwareTrait;
    use UserAccessControlTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->setUpPathAndPublicSubscriptionKey();
        $this->enableFeaturePlugin('Subscription');
        EventManager::instance()->setEventList(new EventList());
    }

    public function tearDown(): void
    {
        EventManager::instance()->getEventList()?->flush();
        parent::tearDown();
    }

    public function testEditionSubscriptionsCreateController_Success(): void
    {
        // Dummy subscription key allows 2 seats — keep the user count at 2 to
        // stay inside the limit while still proving another admin is notified.
        $operator = $this->logInAsAdmin();
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->active()->persist();
        $data = $this->getValidSubscriptionKey();

        $this->postJson('/edition/subscription/key.json', compact('data'));

        $this->assertResponseSuccess();
        $this->assertResponseContains('The subscription was created.');
        $this->assertSame(1, $this->subscriptionRowCount());
        $editionRow = EditionOrganizationSettingFactory::firstOrFail(
            ['property' => EditionOrganizationTable::PROPERTY_NAME],
        );
        $this->assertSame(EditionDto::EDITION_PRO, $editionRow->get('value'));
        $this->assertTrue(
            EventManager::instance()->getEventList()->hasEvent(EditionUpgradeService::EVENT_NAME),
        );
        $this->assertEmailQueueCount(1);
        $this->assertEmailIsInQueue([
            'email' => $otherAdmin->username,
            'template' => EditionUpgradeEmailRedactor::TEMPLATE,
        ]);
        $this->assertEmailWithRecipientIsInNotQueue($operator->username);
        $this->assertEmailSubject(
            $otherAdmin->username,
            $operator->profile->full_name . ' upgraded this Passbolt instance to Pro Edition',
        );
    }

    public function testEditionSubscriptionsCreateController_Error_Unauthenticated(): void
    {
        $this->postJson('/edition/subscription/key.json');
        $this->assertAuthenticationError();
    }

    public function testEditionSubscriptionsCreateController_Error_NotAdmin(): void
    {
        $this->logInAsUser();
        $this->postJson('/edition/subscription/key.json');
        $this->assertForbiddenError('Access restricted to administrators.');
    }

    public function testEditionSubscriptionsCreateController_Error_EmptyBody(): void
    {
        $this->logInAsAdmin();
        $this->postJson('/edition/subscription/key.json');
        $this->assertBadRequestError('Subscription key data is required.');
    }

    public function testEditionSubscriptionsCreateController_Error_AlreadyPro(): void
    {
        $this->logInAsAdmin();
        (new EditionSetService())->setToPro($this->mockAdminAccessControl());
        $data = $this->getValidSubscriptionKey();

        $this->postJson('/edition/subscription/key.json', compact('data'));

        $this->assertResponseCode(409);
        $this->assertResponseContains('The instance is already on PRO.');
        $this->assertFalse(
            EventManager::instance()->getEventList()->hasEvent(EditionUpgradeService::EVENT_NAME),
        );
    }

    public function testEditionSubscriptionsCreateController_Error_SubscriptionRowExists(): void
    {
        $this->logInAsAdmin();
        $this->persistValidSubscription();
        $data = $this->getValidSubscriptionKey();

        $this->postJson('/edition/subscription/key.json', compact('data'));

        $this->assertResponseCode(409);
        $this->assertResponseContains('A subscription key is already present.');
    }

    public function testEditionSubscriptionsCreateController_Error_ExpiredKey(): void
    {
        $this->logInAsAdmin();
        $data = $this->getExpiredSubscriptionKey();

        $this->postJson('/edition/subscription/key.json', compact('data'));

        $this->assertPaymentRequiredError('The subscription is expired.');
        $this->assertSame(0, $this->subscriptionRowCount());
    }

    public function testEditionSubscriptionsCreateController_Error_BadSignature(): void
    {
        Configure::delete('passbolt.plugins.edition.subscriptionKey.public');
        $this->logInAsAdmin();
        $data = $this->getValidSubscriptionKey();

        $this->postJson('/edition/subscription/key.json', compact('data'));

        $this->assertBadRequestError(SubscriptionSignatureException::MESSAGE);
        $this->assertSame(0, $this->subscriptionRowCount());
    }

    private function subscriptionRowCount(): int
    {
        /** @var \Passbolt\Subscription\Model\Table\SubscriptionsTable $subscriptionsTable */
        $subscriptionsTable = $this->fetchTable('Passbolt/Subscription.Subscriptions');

        return SubscriptionFactory::find()
            ->where(['property' => $subscriptionsTable->getProperty()])
            ->count();
    }
}
