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
 * @since         5.8.0
 */

namespace App\Test\TestCase\Notification\Email\Redactor\User;

use App\Controller\Users\UsersEditController;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Notification\Email\Redactor\User\UserRoleUpdatedEmailRedactor;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Test\Lib\Utility\ExtendedUserAccessControlTestTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\I18n\DateTime;

/**
 * @covers \App\Notification\Email\Redactor\User\UserRoleUpdatedEmailRedactor
 */
class UserRoleUpdatedEmailRedactorTest extends AppTestCase
{
    use ExtendedUserAccessControlTestTrait;
    use EmailQueueTrait;

    /**
     * @var UserRoleUpdatedEmailRedactor|null
     */
    private ?UserRoleUpdatedEmailRedactor $sut;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.plugins.log.enabled', true);
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new UserRoleUpdatedEmailRedactor();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testUserRoleUpdatedEmailRedactor(): void
    {
        $roleAdmin = RoleFactory::make(['name' => Role::ADMIN])->persist();
        $roleUser = RoleFactory::make(['name' => Role::USER])->persist();
        $roleCustom = RoleFactory::make(['name' => 'sales'])->persist();
        $admin = UserFactory::make(['role_id' => $roleAdmin->id])->active()->persist();
        $user = UserFactory::make(['role_id' => $roleUser->id])->active()->persist();
        $uac = $this->makeExtendedUac($admin, '1.1.1.1', 'FF');

        $event = new Event(UsersEditController::EVENT_USER_AFTER_UPDATE);
        // Mark role as dirty to mimic role change
        $user->role_id = $roleCustom->id;
        $event->setData(['user' => $user, 'operator' => $uac]);
        $emailCollection = $this->sut->onSubscribedEvent($event);

        $this->assertCount(1, $emailCollection->getEmails());
        $email = $emailCollection->getEmails()[0];
        $body = $email->getData()['body'];
        $this->assertInstanceOf(User::class, $body['recipient']);
        $this->assertInstanceOf(User::class, $body['operator']);
    }

    public function testUserRoleUpdatedEmailRedactor_AdminRoleDowngradeToSomeOtherRole(): void
    {
        $roleAdmin = RoleFactory::make(['name' => Role::ADMIN])->persist();
        $roleUser = RoleFactory::make(['name' => Role::USER])->persist();
        $admin = UserFactory::make(['role_id' => $roleAdmin->id])->active()->persist();
        $user = UserFactory::make(['role_id' => $roleAdmin->id])->active()->persist();
        $uac = $this->makeExtendedUac($admin, '1.1.1.1', 'FF');

        $event = new Event(UsersEditController::EVENT_USER_AFTER_UPDATE);
        // Mark role as dirty to mimic role change
        $user->role_id = $roleUser->id;
        $event->setData(['user' => $user, 'operator' => $uac]);
        $emailCollection = $this->sut->onSubscribedEvent($event);

        $this->assertCount(1, $emailCollection->getEmails());
        $email = $emailCollection->getEmails()[0];
        $body = $email->getData()['body'];
        $this->assertInstanceOf(User::class, $body['recipient']);
        $this->assertInstanceOf(User::class, $body['operator']);
    }

    public function testUserRoleUpdatedEmailRedactor_RoleNotChanged(): void
    {
        $roleAdmin = RoleFactory::make(['name' => Role::ADMIN])->persist();
        $roleUser = RoleFactory::make(['name' => Role::USER])->persist();
        $admin = UserFactory::make(['role_id' => $roleAdmin->id])->active()->persist();
        $user = UserFactory::make(['role_id' => $roleUser->id])->user()->active()->persist();
        $uac = $this->makeExtendedUac($admin, '1.1.1.1', 'FF');

        $event = new Event(UsersEditController::EVENT_USER_AFTER_UPDATE);
        $user->disabled = DateTime::now();
        $event->setData(['user' => $user, 'operator' => $uac]);
        $emailCollection = $this->sut->onSubscribedEvent($event);

        $this->assertEmpty($emailCollection->getEmails());
    }
}
