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
 * @since         3.7.0
 */

namespace App\Test\TestCase\Service\Groups;

use App\Service\Groups\GroupGetService;
use App\Test\Factory\GroupFactory;
use App\Test\Lib\AppTestCase;
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\NotFoundException;

/**
 * Class GroupGetServiceTest
 *
 * @package App\Test\TestCase\Service\Groups
 */
class GroupGetServiceTest extends AppTestCase
{
    public function testGroupGetService_GetNotDeletedOrFail_Success(): void
    {
        $groupFixture = GroupFactory::make()->persist();
        $group = (new GroupGetService())->getNotDeletedOrFail($groupFixture->id);
        $this->assertNotEmpty($group);
        $this->assertEquals($groupFixture->id, $group->id);
        $this->assertEquals($groupFixture->name, $group->name);
    }

    public function testGroupGetService_GetNotDeletedOrFail_Error_InvalidGroupId(): void
    {
        $this->expectException(BadRequestException::class);
        (new GroupGetService())->getNotDeletedOrFail('not-uuid');
    }

    public function testGroupGetService_GetNotDeletedOrFail_Error_NotFound(): void
    {
        $this->expectException(NotFoundException::class);
        (new GroupGetService())->getNotDeletedOrFail(UuidFactory::uuid());
    }

    public function testGroupGetService_GetNotDeletedOrFail_Error_Deleted(): void
    {
        $groupFixture = GroupFactory::make()->deleted()->persist();
        $this->expectException(NotFoundException::class);
        (new GroupGetService())->getNotDeletedOrFail($groupFixture->id);
    }
}
