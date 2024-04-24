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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration\Test\TestCase\Controller\Users;

use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use Cake\ORM\TableRegistry;
use Passbolt\SelfRegistration\SelfRegistrationPlugin;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \App\Controller\Users\UsersRegisterController
 */
class SelfRegistrationUsersRegisterControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        $this->enableFeaturePlugin(SelfRegistrationPlugin::class);
    }

    public function testSelfRegistrationUsersRegisterController_SelfRegistrationClosed()
    {
        $this->get('/users/register');
        $this->assertResponseCode(404);

        $this->postJson('/users/register.json');
        $this->assertResponseCode(404);
    }

    public function testSelfRegistrationUsersRegisterController_SelfRegistrationOpen()
    {
        $this->setSelfRegistrationSettingsData();

        $this->get('/users/register');
        $this->assertResponseOk();

        $this->postJson('/users/register.json');
        $this->assertBadRequestError('The self registration data could not be validated.');
    }

    public function testSelfRegistrationUsersRegisterController_SelfRegistrationOpen_DataValid()
    {
        $nAdmins = 2;
        $admins = UserFactory::make($nAdmins)->admin()->persist();
        RoleFactory::make()->user()->persist();
        $username = 'johndoe@passbolt.com';
        $this->setSelfRegistrationSettingsData();
        $firstName = 'John';
        $lastName = 'Doe';
        $data = [
            'username' => $username,
            'profile' => [
                'first_name' => $firstName,
                'last_name' => $lastName,
            ],
        ];
        $this->postJson('/users/register.json', $data);
        $this->assertResponseOk();
        $this->assertSame(1, UserFactory::find()->where(compact('username'))->count());

        $this->assertEmailQueueCount($nAdmins + 1);
        $emailSubject = "$firstName just created an account on passbolt!";
        $emailContent = "$firstName $lastName used the self registration feature to create an account on passbolt.";
        foreach ($admins as $otherAdmin) {
            $this->assertEmailInBatchContains($emailSubject, $otherAdmin->username);
            $this->assertEmailInBatchContains($emailContent, $otherAdmin->username);
        }
        $this->assertEmailInBatchContains('You just created your account on passbolt!', $username);
    }

    public function testSelfRegistrationUsersRegisterController_ExistingDeletedUserWithSameUsername()
    {
        $username = 'john@passbolt.com';
        $existingUser = UserFactory::make(compact('username'))->user()->persist();
        $this->setSelfRegistrationSettingsData();

        // 1) Try to create a user with same username as an existing one.
        $data = [
            'username' => strtoupper($username),
            'profile' => [
                'first_name' => 'Ping',
                'last_name' => 'Duplicate',
            ],
        ];

        $this->postJson('/users/register.json', $data);
        $this->assertForbiddenError('The email is already registered.');

        // 2) Soft delete the existing user.
        $users = TableRegistry::getTableLocator()->get('Users');
        $users->softDelete($existingUser, ['checkRules' => false]);

        // 3) Try again with same data, it should be successful.
        $this->postJson('/users/register.json', $data);
        $this->assertResponseOk();
        $createdUserId = $this->_responseJson->body->id;

        // 4) Soft delete the non deleted existing user again.
        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->get($createdUserId);
        $users->softDelete($user, ['checkRules' => false]);

        // 5) Try again with same data, it should be successful again.
        $this->postJson('/users/register.json', $data);
        $this->assertResponseOk();
    }
}
