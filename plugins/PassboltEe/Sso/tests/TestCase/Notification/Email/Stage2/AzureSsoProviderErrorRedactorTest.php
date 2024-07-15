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
 * @since         4.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Notification\Email\Stage2;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use Cake\Cache\Cache;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Controller\AbstractSso2Stage2Controller;
use Passbolt\Sso\Error\Exception\AzureException;
use Passbolt\Sso\Notification\Email\Stage2\AzureSsoProviderErrorRedactor;
use Passbolt\Sso\Service\Cache\SsoProviderErrorCacheService;

/**
 * @covers \Passbolt\Sso\Notification\Email\Stage2\AzureSsoProviderErrorRedactor
 */
class AzureSsoProviderErrorRedactorTest extends AppTestCase
{
    /**
     * @var \Passbolt\Sso\Notification\Email\Stage2\AzureSsoProviderErrorRedactor
     */
    private $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->sut = new AzureSsoProviderErrorRedactor();
        $this->loadPlugins(['Passbolt/Locale' => []]);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);

        parent::tearDown();
    }

    public function testAzureSsoProviderErrorRedactor_EmailIsSubscribedToEvent()
    {
        $this->assertTrue(
            in_array(
                AbstractSso2Stage2Controller::EVENT_PROVIDER_ERROR_RESOURCE_OWNER,
                $this->sut->getSubscribedEvents()
            )
        );
    }

    public function testAzureSsoProviderErrorRedactor_Success()
    {
        UserFactory::make(2)->admin()->active()->persist();

        /**
         * First attempt (mail is sent to all admins)
         */
        $event = $this->getDummyEventData();
        $emailCollection = $this->sut->onSubscribedEvent($event);

        // Assert event result contains bad request exception (to map it further)
        $this->assertArrayHasKey('customException', $event->getResult());
        $exception = $event->getResult()['customException'];
        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertSame(400, $exception->getCode());
        $this->assertTextContains('Single sign-on failed. Provider error:', $exception->getMessage());
        // Check email collection
        $this->assertCount(2, $emailCollection->getEmails());

        /**
         * Second attempt (no email sent, to reduce spams)
         */
        $event = $this->getDummyEventData();
        $emailCollection = $this->sut->onSubscribedEvent($event);

        $this->assertCount(0, $emailCollection->getEmails());
        $this->assertArrayHasKey('customException', $event->getResult());
        $exception = $event->getResult()['customException'];
        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertSame(400, $exception->getCode());
        $this->assertTextContains('Single sign-on failed. Provider error:', $exception->getMessage());

        /**
         * Clear cache state
         */
        Cache::clear(SsoProviderErrorCacheService::getConfigKey());
        SsoProviderErrorCacheService::reset();
    }

    public function testAzureSsoProviderErrorRedactor_Success_NoRecipients()
    {
        $event = $this->getDummyEventData();

        $emailCollection = $this->sut->onSubscribedEvent($event);

        // Assert event result contains bad request exception (to map it further)
        $this->assertArrayHasKey('customException', $event->getResult());
        $exception = $event->getResult()['customException'];
        $this->assertInstanceOf(BadRequestException::class, $exception);
        $this->assertSame(400, $exception->getCode());
        $this->assertTextContains('Single sign-on failed. Provider error:', $exception->getMessage());
        // Check email collection
        $this->assertCount(0, $emailCollection->getEmails());

        /**
         * Clear cache state
         */
        Cache::clear(SsoProviderErrorCacheService::getConfigKey());
        SsoProviderErrorCacheService::reset();
    }

    public function testAzureSsoProviderErrorRedactor_Success_NotAllowedErrorCode()
    {
        UserFactory::make(2)->admin()->active()->persist();
        // Prepare event
        $exception = new AzureException('foo_bar', 'Something went wrong');
        $event = new Event(AbstractSso2Stage2Controller::EVENT_PROVIDER_ERROR_RESOURCE_OWNER);
        $event->setData(['exception' => $exception]);

        $emailCollection = $this->sut->onSubscribedEvent($event);

        // Assert exception is not set in the result when error code not related to secret expiry
        $this->assertNull($event->getResult());
        $this->assertEmpty($emailCollection->getEmails());
    }

    public function testAzureSsoProviderErrorRedactor_Error_InvalidExceptionSetInTheEventData()
    {
        $event = new Event(AbstractSso2Stage2Controller::EVENT_PROVIDER_ERROR_RESOURCE_OWNER);
        $event->setData(['exception' => new \stdClass()]);

        $this->expectException(\InvalidArgumentException::class);

        $this->sut->onSubscribedEvent($event);
    }

    /**
     * Helper methods
     */

    private function getDummyEventData(): Event
    {
        $exception = new AzureException(
            'invalid_client',
            'AADSTS7000222: The provided client secret keys for app \'foo-bar\' are expired.'
        );
        $event = new Event(AbstractSso2Stage2Controller::EVENT_PROVIDER_ERROR_RESOURCE_OWNER);
        $event->setData(['exception' => $exception]);

        return $event;
    }
}
