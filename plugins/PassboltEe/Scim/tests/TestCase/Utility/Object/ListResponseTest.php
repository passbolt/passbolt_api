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
 * @since         5.12.0
 */

namespace Passbolt\Scim\Test\TestCase\Utility\Object;

use App\Test\Lib\AppTestCase;
use Passbolt\Scim\Exception\BadRequestException;
use Passbolt\Scim\Utility\Object\ListResponse;

/**
 * @covers \Passbolt\Scim\Utility\Object\ListResponse
 */
class ListResponseTest extends AppTestCase
{
    /**
     * Regression for PB-51541: `fetchResources()` used to throw a generic `ScimException`
     * (HTTP 500) when the resource type was not supported. It must now throw a
     * `BadRequestException` so the controller maps it to HTTP 400.
     *
     * @dataProvider providerInvalidResourceTypes
     */
    public function testListResponse_FetchResources_InvalidResourceType_ThrowsBadRequest(
        string $invalidType
    ): void {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage(sprintf('The resource type `%s` is not valid', $invalidType));

        (new ListResponse())->fetchResources($invalidType);
    }

    public static function providerInvalidResourceTypes(): array
    {
        return [
            'unknown type' => ['InvalidResourceType'],
            'declared but not implemented (Groups)' => ['Groups'],
            'empty string' => [''],
            'wrong case' => ['users'],
        ];
    }
}
