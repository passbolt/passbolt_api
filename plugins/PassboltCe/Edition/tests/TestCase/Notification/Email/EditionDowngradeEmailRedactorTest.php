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
namespace Passbolt\Edition\Test\TestCase\Notification\Email;

use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Utility\UserAccessControlTrait;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use InvalidArgumentException;
use Passbolt\Edition\Notification\Email\EditionDowngradeEmailRedactor;
use Passbolt\Edition\Service\EditionDowngradeService;

/**
 * @covers \Passbolt\Edition\Notification\Email\EditionDowngradeEmailRedactor
 */
class EditionDowngradeEmailRedactorTest extends AppTestCase
{
    use UserAccessControlTrait;

    private EditionDowngradeEmailRedactor $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new EditionDowngradeEmailRedactor();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testEditionDowngradeEmailRedactor_Success_OneEmailPerOtherAdmin(): void
    {
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->admin()->active()->withAvatar()->persist();
        /** @var \App\Model\Entity\User $otherAdmin1 */
        $otherAdmin1 = UserFactory::make()->admin()->active()->persist();
        /** @var \App\Model\Entity\User $otherAdmin2 */
        $otherAdmin2 = UserFactory::make()->admin()->active()->persist();
        UserFactory::make()->user()->active()->persist();

        $event = $this->buildEvent($operator);
        $emails = $this->sut->onSubscribedEvent($event)->getEmails();

        $this->assertCount(2, $emails);
        $recipientIds = array_map(
            fn($e) => $e->getData()['body']['recipient']->id,
            $emails,
        );
        $this->assertContains($otherAdmin1->id, $recipientIds);
        $this->assertContains($otherAdmin2->id, $recipientIds);
        $this->assertNotContains($operator->id, $recipientIds);

        foreach ($emails as $email) {
            $body = $email->getData()['body'];
            $this->assertInstanceOf(User::class, $body['operator']);
            $this->assertSame($operator->id, $body['operator']->id);
            $this->assertArrayHasKey('downgradedAt', $body);
            $this->assertNotNull($body['operator']->profile);
            $this->assertNotNull($body['operator']->profile->avatar);
            /** @var array $roundTripped */
            $roundTripped = json_decode((string)json_encode($body['operator']), true);
            $this->assertSame(
                $body['operator']->profile->avatar->id,
                $roundTripped['profile']['avatar']['id'] ?? null,
            );
        }
    }

    public function testEditionDowngradeEmailRedactor_Empty_WhenOnlyAdminIsOperator(): void
    {
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->admin()->active()->persist();
        $emails = $this->sut->onSubscribedEvent($this->buildEvent($operator))->getEmails();
        $this->assertEmpty($emails);
    }

    public function testEditionDowngradeEmailRedactor_Empty_WhenOperatorDoesNotExist(): void
    {
        $uac = $this->mockAdminAccessControl();
        $event = new Event(EditionDowngradeService::EVENT_EDITION_DOWNGRADED);
        $event->setData(['uac' => $uac]);
        $this->assertEmpty($this->sut->onSubscribedEvent($event)->getEmails());
    }

    public function testEditionDowngradeEmailRedactor_Empty_WhenOtherAdminIsDisabled(): void
    {
        /** @var \App\Model\Entity\User $operator */
        $operator = UserFactory::make()->admin()->active()->persist();
        UserFactory::make()->admin()->active()->disabled()->persist();

        $emails = $this->sut->onSubscribedEvent($this->buildEvent($operator))->getEmails();
        $this->assertEmpty($emails);
    }

    public function testEditionDowngradeEmailRedactor_Throws_WhenUacMissing(): void
    {
        $event = new Event(EditionDowngradeService::EVENT_EDITION_DOWNGRADED);
        $event->setData([]);

        $this->expectException(InvalidArgumentException::class);
        $this->sut->onSubscribedEvent($event);
    }

    private function buildEvent(User $operator): Event
    {
        $uac = new UserAccessControl(Role::ADMIN, $operator->id, $operator->username);
        $event = new Event(EditionDowngradeService::EVENT_EDITION_DOWNGRADED);
        $event->setData(['uac' => $uac]);

        return $event;
    }
}
