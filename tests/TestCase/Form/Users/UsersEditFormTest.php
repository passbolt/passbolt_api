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
 * @since         5.14.0
 */

namespace App\Test\TestCase\Form\Users;

use App\Form\Users\UsersEditForm;
use App\Test\Lib\Model\AvatarsIntegrationTestTrait;
use App\Utility\UuidFactory;
use Cake\I18n\DateTime;
use Cake\TestSuite\TestCase;
use Laminas\Diactoros\UploadedFile;

class UsersEditFormTest extends TestCase
{
    use AvatarsIntegrationTestTrait;

    private UsersEditForm $form;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = new UsersEditForm();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    public static function usersEditFormDataProvider(): array
    {
        return [
            [
                [], //input data
                false, //expected result
            ],
            [
                [
                    'id' => 1,
                ],
                false,
            ],
            [
                [
                    'id' => UuidFactory::uuid(),
                ],
                true,
            ],
        ];
    }

    /**
     * @dataProvider usersEditFormDataProvider
     */
    public function testUsersEditForm_ValidateData(array $data, bool $expectedResult)
    {
        $this->assertSame($expectedResult, $this->form->validate($data));
    }

    public function testUsersEditForm_Success_SanitizeData(): void
    {
        $data = [
            'id' => UuidFactory::uuid(),
            'role_id' => UuidFactory::uuid(),
            'role' => 'admin',
            'gpgkey' => [],
            'groups_user' => [],
            'foo' => 'bar',
            'disabled' => DateTime::yesterday(),
            'profile' => [
                'first_name' => 'Ada',
                'last_name' => 'Jean',
                'foo' => 'bar',
                'avatar' => [
                    'file' => $this->createUploadFile(),
                    'foo' => 'bar',
                ],
            ],
        ];

        $this->assertSame(true, $this->form->execute($data));
        $this->assertNotSame($this->form->getData(), $data);
        $this->assertArrayNotHasKey('role', $this->form->getData());
        $this->assertArrayNotHasKey('gpgkey', $this->form->getData());
        $this->assertArrayNotHasKey('groups_user', $this->form->getData());
        $this->assertArrayNotHasKey('foo', $this->form->getData());
        $this->assertArrayHasKey('id', $this->form->getData());
        $this->assertEquals($data['id'], $this->form->getData()['id']);
        $this->assertArrayHasKey('role_id', $this->form->getData());
        $this->assertEquals($data['role_id'], $this->form->getData()['role_id']);
        $this->assertArrayHasKey('disabled', $this->form->getData());
        $this->assertEquals($data['disabled'], $this->form->getData()['disabled']);
        $this->assertArrayHasKey('profile', $this->form->getData());
        $this->assertArrayHasKey('first_name', $this->form->getData()['profile']);
        $this->assertArrayNotHasKey('foo', $this->form->getData()['profile']);
        $this->assertEquals($data['profile']['first_name'], $this->form->getData()['profile']['first_name']);
        $this->assertArrayHasKey('last_name', $this->form->getData()['profile']);
        $this->assertEquals($data['profile']['last_name'], $this->form->getData()['profile']['last_name']);
        $this->assertArrayHasKey('avatar', $this->form->getData()['profile']);
        $this->assertArrayHasKey('file', $this->form->getData()['profile']['avatar']);
        $this->assertArrayNotHasKey('foo', $this->form->getData()['profile']['avatar']);
        $this->assertNotEquals($data['profile']['avatar'], $this->form->getData()['profile']['avatar']);
        $this->assertEquals($data['profile']['avatar']['file'], $this->form->getData()['profile']['avatar']['file']);
        $this->assertInstanceOf(UploadedFile::class, $this->form->getData()['profile']['avatar']['file']);
    }

    public static function nonUploadedFileAvatarValueProvider(): array
    {
        return [
            'file scheme string' => ['file:///etc/hostname'],
            'ftp scheme string' => ['ftp://127.0.0.1:1/'],
            'phar scheme string' => ['phar://x.phar'],
            'plain absolute path' => ['/etc/passwd'],
            'legacy $_FILES array' => [[
                'tmp_name' => '/tmp/whatever',
                'name' => 'x.png',
                'type' => 'image/png',
                'error' => 0,
                'size' => 1,
            ]],
        ];
    }

    /**
     * @dataProvider nonUploadedFileAvatarValueProvider
     */
    public function testUsersEditForm_SanitizeData_StripsNonUploadedFileAvatar(mixed $value): void
    {
        $data = [
            'id' => UuidFactory::uuid(),
            'profile' => [
                'avatar' => [
                    'file' => $value,
                ],
            ],
        ];

        $this->assertTrue($this->form->execute($data));
        $sanitized = $this->form->getData();
        $this->assertArrayNotHasKey('file', $sanitized['profile']['avatar'] ?? []);
    }
}
