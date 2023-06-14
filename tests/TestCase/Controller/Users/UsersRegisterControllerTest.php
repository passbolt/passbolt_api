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
 * @since         2.0.0
 */
namespace App\Test\TestCase\Controller\Users;

use App\Controller\Users\UsersRecoverController;
use App\Model\Entity\Role;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\EmailQueueTrait;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Passbolt\Locale\Service\GetOrgLocaleService;
use Passbolt\Locale\Service\GetUserLocaleService;
use Passbolt\SelfRegistration\Test\Lib\SelfRegistrationTestTrait;

/**
 * @covers \App\Controller\Users\UsersRegisterController
 */
class UsersRegisterControllerTest extends AppIntegrationTestCase
{
    use EmailQueueTrait;
    use SelfRegistrationTestTrait;

    public $fixtures = [
        'app.Base/Users', 'app.Base/Gpgkeys', 'app.Base/Roles', 'app.Base/Profiles', 'app.Base/Permissions',
        'app.Base/GroupsUsers', 'app.Base/Groups', 'app.Base/Favorites', 'app.Base/Secrets',
    ];

    public function setUp(): void
    {
        parent::setUp();
        $this->setSelfRegistrationSettingsData();
    }

    public function testUsersRegisterController_Get_Success(): void
    {
        $this->get('/users/register');
        $this->assertResponseOk();
    }

    public function dataProviderForTestUsersRegisterController_Success(): array
    {
        return [
            ['chinese_name' => [
                'username' => 'ping.fu@passbolt.com',
                'profile' => [
                    'first_name' => '傅',
                    'last_name' => '苹',
                ],
            ]],
            ['slavic_name' => [
                'username' => 'borka@passbolt.com',
                'profile' => [
                    'first_name' => 'Borka',
                    'last_name' => 'Jerman Blažič',
                ],
            ]],
            ['french_name' => [
                'username' => 'aurore@passbolt.com',
                'profile' => [
                    'first_name' => 'Aurore',
                    'last_name' => 'Avarguès-Weber',
                ],
                'locale' => 'fr-FR',
            ]],
        ];
    }

    /**
     * @dataProvider dataProviderForTestUsersRegisterController_Success
     */
    public function testUsersRegisterController_Success(array $data): void
    {
        $this->postJson('/users/register.json', $data);
        $this->assertResponseSuccess();

        // Check user was saved
        $users = TableRegistry::getTableLocator()->get('Users');
        $query = $users->find()->where(['username' => $data['username']]);
        $this->assertEquals(1, $query->count());
        $user = $query->first();
        $this->assertFalse($user->active);
        $this->assertFalse($user->deleted);

        // Check profile exist
        $profiles = TableRegistry::getTableLocator()->get('Profiles');
        $query = $profiles->find()->where(['first_name' => $data['profile']['first_name']]);
        $this->assertEquals(1, $query->count());

        // Check role exist
        $roles = TableRegistry::getTableLocator()->get('Roles');
        $role = $roles->get($user->get('role_id'));
        $this->assertEquals(Role::USER, $role->name);

        // Check locale was stored
        GetOrgLocaleService::clearOrganisationLocale();
        $expectedLocale = $data['locale'] ?? GetOrgLocaleService::getLocale();
        $locale = (new GetUserLocaleService())->getLocale($user->username);
        $this->assertSame($expectedLocale, $locale);

        // Check that an email was sent
        $this->assertEmailIsInQueue([
            'email' => $data['username'],
            'subject' => "Welcome to passbolt, {$data['profile']['first_name']}!",
            'template' => 'AN/user_register_self',
        ]);
    }

    public function testUsersRegisterController_Success_CannotModifyNotAccessibleFields(): void
    {
        // Not allowed to edit: id, active, deleted, created, modified, role_id
        $roles = TableRegistry::getTableLocator()->get('Roles');
        $adminRoleId = $roles->getIdByName(Role::ADMIN);
        $userRoleId = $roles->getIdByName(Role::USER);
        $date = '1983-04-01 23:34:45';
        $userId = UuidFactory::uuid('user.id.aurore');

        $data = [
            'id' => $userId,
            'active' => 1,
            'deleted' => 1,
            'created' => $date,
            'modified' => $date,
            'username' => 'aurore@passbolt.com',
            'role_id' => $adminRoleId,
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
        ];

        $this->postJson('/users/register.json', $data);
        $this->assertResponseSuccess();

        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->find()->where(['username' => $data['username']])->first();

        $this->assertNotEquals($user->id, $userId);
        $this->assertFalse($user->active);
        $this->assertFalse($user->deleted);
        $this->assertEquals($user->role_id, $userRoleId);
        $this->assertTrue($user->created->gt(FrozenTime::parseDateTime($date, 'Y-M-d h:m:s')));
    }

    public function testUsersRegisterController_Error_FailValidation(): void
    {
        $fails = [
            'username is missing' => [
                'username' => '',
                'profile' => [
                    'first_name' => 'valid_first_name',
                    'last_name' => 'valid_last_name',
                ],
            ],
            'username is not an email' => [
                'username' => 'invalid@passbolt',
                'profile' => [
                    'first_name' => 'valid_first_name',
                    'last_name' => 'valid_last_name',
                ],
            ],
            'profile is missing' => [
                'username' => 'valid@passbolt.com',
            ],
            'last name is missing' => [
                'username' => 'valid@passbolt.com',
                'profile' => [
                    'first_name' => 'valid_first_name',
                ],
            ],
            'first name is missing' => [
                'username' => 'valid@passbolt.com',
                'profile' => [
                    'last_name' => 'valid_last_name',
                ],
            ],
            'first name is not a utf8 string' => [
                'username' => 'valid@passbolt.com',
                'profile' => [
                    'first_name' => '🙈🙉🙊',
                    'last_name' => 'valid_last_name',
                ],
            ],
        ];
        foreach ($fails as $case => $data) {
            $this->post('/users/register.json', $data);
            $result = json_decode($this->_getBodyAsString());
            $this->assertEquals('400', $result->header->code, 'Validation should fail when ' . $case);
            $this->assertResponseError();
        }
    }

    public function testUsersRegisterController_Error_MissingCsrfToken(): void
    {
        $this->disableCsrfToken();
        $this->post('/users/register');
        $this->assertResponseCode(403);
        $result = $this->_getBodyAsString();
        $this->assertStringContainsString('Missing or incorrect CSRF cookie type.', $result);
    }

    /**
     * Check that calling url without JSON extension throws a 404
     */
    public function testUsersRegisterController_Error_NotJson(): void
    {
        $data = [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
            'locale' => 'fr-FR',
        ];
        $this->post('/users/register', $data);
        $this->assertResponseCode(404);
    }

    /**
     * Check if security.preventUserEnumeration flag is set to true
     * that API pretends that user is created to prevent knowing that a user is already present
     */
    public function testUsersRegisterController_Error_PreventUserEnumeration(): void
    {
        Configure::write(UsersRecoverController::PREVENT_EMAIL_ENUMERATION_CONFIG_KEY, true);
        $data = [
            'username' => 'aurore@passbolt.com',
            'profile' => [
                'first_name' => 'Aurore',
                'last_name' => 'Avarguès-Weber',
            ],
            'locale' => 'fr-FR',
        ];

        $this->postJson('/users/register.json', $data);
        $this->assertResponseCode(404);
        $this->assertResponseContains('Registration is not opened to public. ');
        $this->assertResponseContains('This is due to a security setting. ');
        $this->assertResponseContains('Please contact your administrator.');
    }
}
