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

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Notification\Email;

use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Event\Event;
use Passbolt\MultiFactorAuthentication\Notification\Email\MfaRateLimitUserDisabledEmailRedactor;
use Passbolt\MultiFactorAuthentication\Service\MfaRateLimitDisableUserService;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Notification\Email\MfaRateLimitUserDisabledEmailRedactor
 */
class MfaRateLimitUserDisabledEmailRedactorTest extends AppTestCase
{
    private MfaRateLimitUserDisabledEmailRedactor $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new MfaRateLimitUserDisabledEmailRedactor();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testMfaRateLimitUserDisabledEmailRedactor_Success()
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->disabled()->withAvatar()->persist();

        $emails = $this->sut->onSubscribedEvent($this->buildEvent($user))->getEmails();

        $this->assertCount(1, $emails);
        $email = $emails[0];
        $this->assertSame($user->username, $email->getRecipient());
        $this->assertSame('Your account has been suspended', $email->getSubject());
        $this->assertSame(MfaRateLimitUserDisabledEmailRedactor::TEMPLATE, $email->getTemplate());
        $data = $email->getData();
        $this->assertSame('Your account has been suspended', $data['title']);
        $this->assertSame($user->id, $data['body']['user']['id']);
        $this->assertSame(
            [MfaRateLimitDisableUserService::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT],
            $this->sut->getSubscribedEvents(),
        );
    }

    public function testMfaRateLimitUserDisabledEmailRedactor_Empty_UserNotFound()
    {
        // The row was removed between the dispatch of the event and this redactor.
        $user = UserFactory::make()->user()->getEntity();
        $user->set('id', UuidFactory::uuid());

        $emails = $this->sut->onSubscribedEvent($this->buildEvent($user))->getEmails();

        $this->assertEmpty($emails);
    }

    /**
     * @param \App\Model\Entity\User $user User whose account was disabled.
     * @return \Cake\Event\Event
     */
    private function buildEvent($user): Event
    {
        return new Event(
            MfaRateLimitDisableUserService::EVENT_USER_WAS_DISABLED_ON_MFA_RATE_LIMIT,
            $this,
            ['user' => $user],
        );
    }
}
