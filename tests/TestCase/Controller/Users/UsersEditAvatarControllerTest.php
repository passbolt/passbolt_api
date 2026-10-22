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

use App\Test\Factory\AvatarFactory;
use App\Test\Factory\RoleFactory;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppIntegrationTestCase;
use App\Test\Lib\Model\AvatarsIntegrationTestTrait;
use App\Utility\UuidFactory;

class UsersEditAvatarControllerTest extends AppIntegrationTestCase
{
    use AvatarsIntegrationTestTrait;

    public function setUp(): void
    {
        parent::setUp();
        RoleFactory::make()->guest()->persist();
        // Mock user agent and IP
        $this->mockUserAgent('PHPUnit');
        $this->mockUserIp();
    }

    public function testUsersEditAvatarController_Success(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $data = [
            'id' => $user->id,
            'profile' => [
                'avatar' => [
                    'file' => $this->createUploadFile(),
                ],
            ],
        ];

        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertSuccess();

        /** @var \App\Model\Entity\Avatar $avatar */
        $avatar = AvatarFactory::find()
            ->contain('Profiles.Users')
            ->where(['Users.id' => $user->id])
            ->firstOrFail();

        $this->assertAvatarCachedFilesExist($avatar);
    }

    public function testUsersEditAvatarController_Error_MissingCsrfToken(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $this->disableCsrfToken();
        $userId = $user->id;
        $this->post("/users/$userId.json");
        $this->assertResponseCode(403);
    }

    public function testUsersEditAvatarController_Error_WrongFileFormat(): void
    {
        $filesDirectory = TESTS . 'Fixtures' . DS . 'Avatar';
        $pdfFile = $filesDirectory . DS . 'minimal.pdf';

        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $data = [
            'id' => $user->id,
            'profile' => [
                'avatar' => [
                    'file' => [
                        'tmp_file' => $pdfFile,
                        'name' => 'minimal.pdf',
                    ],
                ],
            ],
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertError(400, 'Could not validate user data.');
        $this->assertNotEmpty($this->_responseJsonBody->profile->avatar->file->_required);

        $this->assertEquals(0, AvatarFactory::count(), 'The number of avatars in db should be same before and after the test');
    }

    public function testUsersEditAvatarController_Error_NoDataProvided(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $data = [
            'id' => $user->id,
            'profile' => [
                'avatar' => [],
            ],
        ];
        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertError(400, 'Could not validate user data.');
        $this->assertNotEmpty($this->_responseJsonBody->profile->avatar->file->_required);
    }

    public function testUsersEditAvatarController_Success_CantOverrideData(): void
    {
        $irene = UserFactory::make()->user()->persist();

        $this->logInAs($irene);
        $data = [
            'id' => $irene->id,
            'profile' => [
                'avatar' => [
                    'file' => $this->createUploadFile(),
                    'user_id' => UuidFactory::uuid('user.id.whatever'),
                    'foreign_key' => UuidFactory::uuid('profile.id.whatever'),
                    'model' => 'Test',
                    'filename' => 'test.jpg',
                    'filesize' => '10024',
                    'mime_type' => 'pdf',
                    'extension' => 'jpg',
                    'hash' => '12345',
                    'path' => '/test/test1',
                    'adapter' => 'TestAdapter',
                ],
            ],
        ];
        $this->postJson('/users/' . $irene->id . '.json', $data);
        $this->assertSuccess();

        /** @var \App\Model\Entity\Avatar $ireneAvatar */
        $ireneAvatar = AvatarFactory::find()
            ->contain('Profiles')
            ->orderByDesc('Avatars.created')
            ->firstOrFail();

        $data = $data['profile']['avatar'];

        $this->assertNotEquals($data['user_id'], $ireneAvatar->profile->user_id);
        $this->assertNotEquals($data['foreign_key'], $ireneAvatar->foreign_key);
        $this->assertNotEquals($data['model'], $ireneAvatar->model);
        $this->assertNotEquals($data['filename'], $ireneAvatar->filename);
        $this->assertNotEquals($data['filesize'], $ireneAvatar->filesize);
        $this->assertNotEquals($data['mime_type'], $ireneAvatar->mime_type);
        $this->assertNotEquals($data['extension'], $ireneAvatar->extension);
        $this->assertNotEquals($data['hash'], $ireneAvatar->hash);
        $this->assertNotEquals($data['path'], $ireneAvatar->path);
        $this->assertNotEquals($data['adapter'], $ireneAvatar->adapter);
        $this->assertSame(1, AvatarFactory::count());
        $this->assertAvatarCachedFilesExist($ireneAvatar);
    }

    public function testUsersEditAvatarController_Error_SsrfPayloadIsRejected(): void
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);

        $data = [
            'id' => $user->id,
            'profile' => [
                'avatar' => [
                    'file' => 'file:///etc/hostname',
                ],
            ],
        ];

        $this->postJson('/users/' . $user->id . '.json', $data);
        $this->assertResponseCode(400);
        $fileErrors = $this->_responseJsonBody->profile->avatar->file ?? null;
        $this->assertNotNull($fileErrors);
        $this->assertObjectNotHasProperty('validMimeType', $fileErrors);
        $this->assertSame(0, AvatarFactory::count());
    }

    public function testUsersEditAvatarController_Bug_Change_Other_User_Avatar(): void
    {
        $userA = UserFactory::make()->user()->withAvatar()->persist();
        $userB = UserFactory::make()->user()->withAvatar()->persist();
        $this->logInAs($userA);
        $data = [
            'id' => $userA->id,
            'profile' => [
                'avatar' => [
                    'file' => $this->createUploadFile(),
                    'profile_id' => $userB['profile']->id,
                ],
            ],
        ];
        $this->postJson('/users/' . $userA->id . '.json', $data);
        $this->assertSuccess();
        $this->assertNotEquals($userB['profile']->id, $this->_responseJsonBody->profile->avatar->profile_id);

        /** @var \App\Model\Entity\Avatar $avatarUserA */
        $avatarUserA = AvatarFactory::find()
            ->contain('Profiles.Users')
            ->where(['Users.id' => $userA->id])
            ->firstOrFail();

        /** @var \App\Model\Entity\Avatar $avatarUserB */
        $avatarUserB = AvatarFactory::find()
            ->contain('Profiles.Users')
            ->where(['Users.id' => $userB->id])
            ->firstOrFail();

        $this->assertNotEquals($avatarUserA->profile_id, $avatarUserB->profile_id);
    }
}
