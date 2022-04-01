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
 * @since         3.6.0
 */

namespace App\Test\TestCase\Service\Users;

use App\Service\Users\UserGetService;
use App\Test\Factory\UserFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

/**
 * Class UserGetServiceTest
 *
 * @package App\Test\TestCase\Service\Users
 */
class UserGetServiceTest extends AppTestCase
{
    public function testUserGetService_Success(): void
    {
        $userFixture = UserFactory::make()->user()->active()->persist();
        $user = (new UserGetService())->getActiveNotDeletedOrFail($userFixture->id);
        $this->assertNotEmpty($user);
        $this->assertEquals($userFixture->id, $user->id);
        $this->assertEquals($userFixture->username, $user->username);
        $this->assertEquals($userFixture->role_id, $user->role_id);
    }

    public function testUserGetService_Error_InvalidID(): void
    {
        $this->expectException(BadRequestException::class);
        (new UserGetService())->getActiveNotDeletedOrFail('🔥');
    }

    public function testUserGetService_Error_NotFoundID(): void
    {
        $this->expectException(NotFoundException::class);
        (new UserGetService())->getActiveNotDeletedOrFail(UuidFactory::uuid());
    }

    public function testUserGetService_Error_NotActive(): void
    {
        $userFixture = UserFactory::make()->user()->inactive()->persist();
        $this->expectException(BadRequestException::class);
        (new UserGetService())->getActiveNotDeletedOrFail($userFixture->id);
    }

    public function testUserGetService_Error_Deleted(): void
    {
        $userFixture = UserFactory::make()->user()->deleted()->persist();
        $this->expectException(BadRequestException::class);
        (new UserGetService())->getActiveNotDeletedOrFail($userFixture->id);
    }
}
