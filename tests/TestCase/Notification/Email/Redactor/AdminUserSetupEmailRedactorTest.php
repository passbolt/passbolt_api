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
 * @since         2.13.0
 */

namespace App\Test\TestCase\Notification\Email\Redactor;

use App\Controller\Setup\SetupCompleteController;
use App\Model\Entity\Profile;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Notification\Email\Email;
use App\Notification\Email\EmailCollection;
use App\Notification\Email\Redactor\AdminUserSetupCompleteEmailRedactor;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\FrozenTime;
use Cake\TestSuite\TestCase;
use Passbolt\Log\Model\Entity\ActionLog;
use Passbolt\Log\Model\Entity\EntityHistory;
use PHPUnit\Framework\MockObject\MockObject;

class AdminUserSetupCompleteEmailRedactorTest extends TestCase
{
    /**
     * @var AdminUserSetupCompleteEmailRedactor
     */
    private $sut;

    /**
     * @var MockObject|UsersTable
     */
    private $usersTableMock;

    public function setUp()
    {
        $this->usersTableMock = $this->createMock(UsersTable::class);

        Configure::write('passbolt.plugins.log.enabled', true);

        $this->sut = new AdminUserSetupCompleteEmailRedactor($this->usersTableMock);

        parent::setUp();
    }

    public function testThatRedactorIsSubscribedToEvents()
    {
        $this->assertSame(
            [
                SetupCompleteController::COMPLETE_SUCCESS_EVENT_NAME,
            ],
            $this->sut->getSubscribedEvents()
        );
    }

    public function createUserMock(string $username, string $firstName, string $lastName)
    {
        $user = new User();
        $user->id = UuidFactory::uuid();
        $user->username = $username;

        $profile = new Profile();
        $profile->first_name = $firstName;
        $profile->last_name = $lastName;

        $user->profile = $profile;

        return $user;
    }

    public function testThatEmailsAreCreatedForAllAdmins()
    {
        $expectedInvitedWhen = new FrozenTime();
        $expectedInvitedBy = $this->createUserMock('carol@passbolt.com', 'Carol', 'Noway');
        $expectedAdmins = [$this->createUserMock('ada@passbolt.com', 'Ada', 'Lovelace')];

        $userCompletedSetup = $this->createUserMock('betty@passbolt.com', 'Betty', 'What');

        $userCompletedSetup->entities_history = [
            $this->createEntityHistoryForUser($expectedInvitedBy, $expectedInvitedWhen),
        ];

        $this->assertThatUsersTableReturnSpecifiedAdminList($expectedAdmins);
        $this->assertThatUsersTableReturnUserWhoCompletedSetupWithEntityHIstory($userCompletedSetup);

        $result = $this->sut->onSubscribedEvent(
            new Event('some_event', null, [
                'user' => $userCompletedSetup,
            ])
        );

        $emailRecipients = [];
        /** @var Email $email */
        foreach ($result->getEmails() as $email) {
            $emailRecipients[] = $email->getRecipient();
        }

        $this->assertThatAllAdminHaveAnEmailCreated($emailRecipients, $expectedAdmins);

        $expectedSubject = sprintf('%s have just activated their account on passbolt', $userCompletedSetup->profile->first_name);

        $email = $result->getEmails()[0];
        $this->assertEmailSubject($expectedSubject, $email, $userCompletedSetup, $expectedAdmins[0]);
        $this->assertEmailData(
            $email,
            $userCompletedSetup,
            $expectedAdmins[0],
            $expectedSubject,
            $expectedInvitedBy,
            $expectedInvitedWhen
        );
    }

    public function assertEmailData(
        Email $email,
        User $userCompletedSetup,
        User $admin,
        string $expectedSubject,
        User $invitedBy,
        FrozenTime $expectedInvitedWhen
    ) {
        $emailData = $email->getData();

        $expectedEmailData = [
            'title' => $expectedSubject,
            'body' => [
                'user' => $userCompletedSetup,
                'admin' => $admin,
                'invitedBy' => $invitedBy,
                'invitedWhen' => $expectedInvitedWhen->timeAgoInWords(['accuracy' => 'day']),
                'invitedByYou' => $invitedBy->id === $admin->id,
            ],
        ];

        $this->assertSame($expectedEmailData, $emailData);
    }

    public function assertEmailSubject(string $expectedSubject, Email $email, User $userCompletedSetup, User $admin)
    {
        $this->assertSame(
            $expectedSubject,
            $email->getSubject()
        );
    }

    private function assertThatAllAdminHaveAnEmailCreated(array $emailRecipients, array $admins)
    {
        foreach ($admins as $admin) {
            $this->assertContains(
                $admin->username,
                $emailRecipients,
                sprintf("`%s` is an admin and should have an email created", $admin->username)
            );
        }
    }

    private function createEntityHistoryForUser(User $invitedBy, FrozenTime $invitedWhen)
    {
        $actionLog = new ActionLog();
        $actionLog->created = $invitedWhen;
        $actionLog->user = $invitedBy;

        $entityHistory = new EntityHistory();
        $entityHistory->action_log = $actionLog;

        return $entityHistory;
    }

    private function assertThatUsersTableReturnSpecifiedAdminList(array $admins)
    {
        $this->usersTableMock->expects($this->once())
            ->method('findAdmins')
            ->willReturn($admins);
    }

    private function assertThatUsersTableReturnUserWhoCompletedSetupWithEntityHIstory(User $userWhoCompletedSetup)
    {
        $this->usersTableMock->expects($this->once())
            ->method('loadInto')
            ->willReturn($userWhoCompletedSetup);
    }
}
