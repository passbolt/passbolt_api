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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\SsoKeys;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;
use Passbolt\Sso\Service\SsoKeys\SsoKeysDeleteService;
use Passbolt\Sso\Test\Factory\SsoKeysFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

class SsoKeysDeleteServiceTest extends SsoTestCase
{
    public function testSsoKeysDeleteServiceTest_Success(): void
    {
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $this->assertEquals(1, SsoKeysFactory::count());

        $service = new SsoKeysDeleteService();
        $service->delete($uac, $key->id);
        $this->assertEquals(0, SsoKeysFactory::count());
    }

    public function testSsoKeysDeleteServiceTest_Error_InvalidId(): void
    {
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $this->assertEquals(1, SsoKeysFactory::count());

        $service = new SsoKeysDeleteService();

        $this->expectException(BadRequestException::class);
        $service->delete($uac, 'nope');
    }

    public function testSsoKeysDeleteServiceTest_Error_IdNotFound(): void
    {
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $this->assertEquals(1, SsoKeysFactory::count());

        $service = new SsoKeysDeleteService();
        $this->expectException(NotFoundException::class);
        $service->delete($uac, UuidFactory::uuid());
    }

    public function testSsoKeysDeleteServiceTest_Error_UserNotFound(): void
    {
        $user = UserFactory::make()->active()->persist();
        $key = SsoKeysFactory::make()->userId($user->id)->persist();
        $uac = new UserAccessControl(Role::USER, UuidFactory::uuid(), $user->username);
        $this->assertEquals(1, SsoKeysFactory::count());

        $service = new SsoKeysDeleteService();
        $this->expectException(NotFoundException::class);
        $service->delete($uac, $key->id);
    }
}
