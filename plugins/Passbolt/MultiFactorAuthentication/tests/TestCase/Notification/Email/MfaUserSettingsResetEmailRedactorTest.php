<?php
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
 * @since         2.12.0
 */

namespace Passbolt\MultiFactorAuthentication\Notification\Email;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Event\Event;
use Passbolt\MultiFactorAuthentication\Controller\UserSettings\MfaUserSettingsDeleteController;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MfaUserSettingsResetEmailRedactorTest extends TestCase
{
    /**
     * @var MfaUserSettingsResetEmailRedactor
     */
    private $sut;

    /**
     * @var UsersTable|MockObject
     */
    private $usersTableMock;

    public function setUp()
    {
        $this->usersTableMock = $this->createMock(UsersTable::class);

        $this->sut = new MfaUserSettingsResetEmailRedactor($this->usersTableMock);

        parent::setUp();
    }

    public function testThatEmailIsSubscribedToEvent()
    {
        $this->assertTrue(
            in_array(
                MfaUserSettingsDeleteController::MFA_USER_ACCOUNT_SETTINGS_DELETE_EVENT,
                $this->sut->getSubscribedEvents()
            )
        );
    }

    public function testThatEmailUseAdminDeleteTemplateWhenUserIsAdmin()
    {
        $adminUser = new User();
        $user = new User();
        $user->username = 'admin';
        $uac = new UserAccessControl('admin', UuidFactory::uuid(), 'ada@passbolt.com');
        $event = new Event(MfaUserSettingsDeleteController::MFA_USER_ACCOUNT_SETTINGS_DELETE_EVENT);
        $event->setData([
            'target' => $user,
            'uac' => $uac,
        ]);

        $this->usersTableMock->expects($this->once())
            ->method('findFirstForEmail')
            ->willReturn($adminUser);

        $emailCollection = $this->sut->onSubscribedEvent($event);
        $email = $emailCollection->getEmails()[0];

        $this->assertCount(1, $emailCollection->getEmails());
        $this->assertEquals(__('Your multi-factor authentication settings were reset by an administrator.'), $email->getSubject());
        $this->assertEquals(MfaUserSettingsResetEmailRedactor::TEMPLATE_ADMIN, $email->getTemplate());
        $this->assertEquals([
            'title' => __('Multi-factor authentication settings were reset.'),
            'body' => ['user' => $adminUser],
        ], $email->getData());
    }

    public function testThatEmailUseSelfDeleteTemplateWhenUserIsHimself()
    {
        $userId = UuidFactory::uuid();
        $user = new User();
        $user->username = 'ada@passbolt.com';
        $user->id = $userId;
        $uac = new UserAccessControl('admin', $userId, 'ada@passbolt.com');
        $event = new Event(MfaUserSettingsDeleteController::MFA_USER_ACCOUNT_SETTINGS_DELETE_EVENT);
        $event->setData([
            'target' => $user,
            'uac' => $uac,
        ]);

        $emailCollection = $this->sut->onSubscribedEvent($event);
        $email = $emailCollection->getEmails()[0];

        $this->assertCount(1, $emailCollection->getEmails());
        $this->assertEquals(__('Your multi-factor authentication settings were reset by you.'), $email->getSubject());
        $this->assertEquals(MfaUserSettingsResetEmailRedactor::TEMPLATE_SELF, $email->getTemplate());
        $this->assertEquals([
            'title' => __('Multi-factor authentication settings were reset.'),
            'body' => ['user' => $user],
        ], $email->getData());
    }
}
