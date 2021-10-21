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
use App\Test\Lib\Model\AvatarsModelTrait;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;

class UsersEditAvatarControllerTest extends AppIntegrationTestCase
{
    use AvatarsModelTrait;

    public $localFileStorageListener = null;
    public $imageProcessingListener = null;

    /**
     * @var \App\Model\Table\AvatarsTable $Avatars
     */
    public $Avatars;

    public function setUp(): void
    {
        parent::setUp();
        $this->Avatars = TableRegistry::getTableLocator()->get('Avatars');
        $this->setTestLocalFilesystemAdapter();
        RoleFactory::make()->guest()->persist();
    }

    public function tearDown(): void
    {
        $this->Avatars->getFilesystem()->deleteDirectory('.');
        unset($this->Avatars);
        parent::tearDown();
    }

    public function testUsersEditAvatarSuccess()
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
        $avatar = $this->Avatars
            ->find()
            ->contain('Profiles.Users')
            ->where(['Users.id' => $user->id])
            ->firstOrFail();

        $this->assertAvatarCachedFilesExist($avatar);
    }

    public function testUsersEditAvatarMissingCsrfTokenError()
    {
        $user = UserFactory::make()->user()->persist();
        $this->logInAs($user);
        $this->disableCsrfToken();
        $userId = $user->id;
        $this->post("/users/$userId.json?api-version=v2");
        $this->assertResponseCode(403);
    }

    public function testUsersEditAvatarWrongFileFormat()
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
        $this->postJson('/users/' . $user->id . '.json?api-version=v2', $data);
        $this->assertError(400, 'Could not validate user data.');
        $this->assertNotEmpty($this->_responseJsonBody->profile->avatar->file->validExtension);
        $this->assertNotEmpty($this->_responseJsonBody->profile->avatar->file->validMimeType);
        $this->assertNotEmpty($this->_responseJsonBody->profile->avatar->file->validUploadedFile);

        $this->assertEquals(0, $this->Avatars->find()->count(), 'The number of avatars in db should be same before and after the test');
    }

    public function testUsersEditAvatarNoDataProvided()
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

    public function testUsersEditAvatarCantOverrideData()
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
            ->orderDesc('Avatars.created')
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
    }
}
