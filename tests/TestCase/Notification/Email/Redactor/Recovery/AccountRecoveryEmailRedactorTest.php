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
 * @since         3.4.0
 */

namespace App\Test\TestCase\Notification\Email\Redactor\Recovery;

use App\Controller\Users\UsersRecoverController;
use App\Test\Factory\AuthenticationTokenFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\TableRegistry;
use Passbolt\EmailDigest\Test\Factory\EmailQueueFactory;

class AccountRecoveryEmailRedactorTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;

    public function setUp(): void
    {
        parent::setUp();
        Configure::write('passbolt.webInstaller.configured', true);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        Configure::delete('passbolt.webInstaller.configured');
    }

    public function testAccountRecoveryEmailRedactor()
    {
        $this->getJson('/auth/is-authenticated.json');

        $user = UserFactory::make()->withAvatar()->user()->persist();

        /** @var UsersTable $Users */
        $Users = TableRegistry::getTableLocator()->get('Users');
        /** @var User $user */
        $user = $Users->findRecover($user->username)->first();
        $token = AuthenticationTokenFactory::make()->persist();
        $event = new Event(UsersRecoverController::RECOVER_SUCCESS_EVENT_NAME, null, compact('user', 'token'));
        EventManager::instance()->dispatch($event);

        $this->assertSame(1, EmailQueueFactory::count());
        $this->assertEmailIsInQueue([
            'email' => $user->username,
            'subject' => "Your account recovery, {$user->profile->first_name}!",
            'template' => 'AN/user_recover',
        ]);
        $emailVars = EmailQueueFactory::find()->firstOrFail()->get('template_vars');
        $this->assertSame($user->username, $emailVars['body']['user']['username']);
        $this->assertSame($user->profile->first_name, $emailVars['body']['user']['profile']['first_name']);
        $this->assertSame($token->token, $emailVars['body']['token']['token']);
    }
}
