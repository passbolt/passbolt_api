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
 * @since         5.17.0
 */
namespace Passbolt\Scim\Test\TestCase\Notification\Email;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Event\Event;
use InvalidArgumentException;
use Passbolt\Scim\Authenticator\ScimTokenAuthenticator;
use Passbolt\Scim\Notification\Email\ScimTokenNearExpiryEmailRedactor;
use Passbolt\Scim\Service\Cache\ScimTokenNearExpiryNotificationCacheService;

/**
 * @covers \Passbolt\Scim\Notification\Email\ScimTokenNearExpiryEmailRedactor
 * @covers \Passbolt\Scim\Notification\Email\AbstractScimTokenExpiryEmailRedactor
 */
class ScimTokenNearExpiryEmailRedactorTest extends AppTestCase
{
    private ScimTokenNearExpiryEmailRedactor $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        // Start each test with an empty throttle window.
        ScimTokenNearExpiryNotificationCacheService::reset();
        $this->sut = new ScimTokenNearExpiryEmailRedactor();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        ScimTokenNearExpiryNotificationCacheService::reset();
        unset($this->sut);
        parent::tearDown();
    }

    public function testScimTokenNearExpiryEmailRedactor_Success(): void
    {
        /** @var \App\Model\Entity\User $admin */
        $admin = UserFactory::make()->admin()->active()->withAvatar()->persist();
        /** @var \App\Model\Entity\User $otherAdmin */
        $otherAdmin = UserFactory::make()->admin()->active()->persist();
        UserFactory::make()->user()->active()->persist(); // not an administrator
        UserFactory::make()->admin()->active()->disabled()->persist(); // disabled administrator

        $emails = $this->sut->onSubscribedEvent($this->buildEvent())->getEmails();

        $this->assertCount(2, $emails);
        $recipientIds = array_map(fn($email) => $email->getData()['body']['recipient']->id, $emails);
        $this->assertEqualsCanonicalizing([$admin->id, $otherAdmin->id], $recipientIds);
        foreach ($emails as $email) {
            $this->assertSame('The SCIM secret token expires soon', $email->getSubject());
            $this->assertSame('The SCIM secret token expires soon', $email->getData()['title']);
            $this->assertSame('2026-01-15', $email->getData()['body']['token_expiry_date']);
            $this->assertSame('Passbolt/Scim.AD/scim_token_near_expiry', $email->getTemplate());
        }
        // Assert cache is written
        $this->assertTrue((new ScimTokenNearExpiryNotificationCacheService())->isNotificationPerformed());
    }

    public function testScimTokenNearExpiryEmailRedactor_Empty_WhenCacheIsPresent(): void
    {
        UserFactory::make()->admin()->active()->persist();
        (new ScimTokenNearExpiryNotificationCacheService())->markNotificationPerformed();

        $emails = $this->sut->onSubscribedEvent($this->buildEvent())->getEmails();

        $this->assertEmpty($emails);
    }

    public function testScimTokenNearExpiryEmailRedactor_Empty_WhenNoActiveAdmins(): void
    {
        UserFactory::make()->user()->active()->persist();
        UserFactory::make()->admin()->active()->disabled()->persist();

        $emails = $this->sut->onSubscribedEvent($this->buildEvent())->getEmails();

        $this->assertEmpty($emails);
        // Assert no cache entry is present
        $this->assertFalse((new ScimTokenNearExpiryNotificationCacheService())->isNotificationPerformed());
    }

    public function testScimTokenNearExpiryEmailRedactor_Error_WhenExpiredIsMissing(): void
    {
        UserFactory::make()->admin()->active()->persist();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/expired/');

        $this->sut->onSubscribedEvent(new Event(ScimTokenAuthenticator::EVENT_TOKEN_NEAR_EXPIRY));
    }

    /** Helper methods */

    private function buildEvent(): Event
    {
        $event = new Event(ScimTokenAuthenticator::EVENT_TOKEN_NEAR_EXPIRY);
        $event->setData(['expired' => '2026-01-15']);

        return $event;
    }
}
