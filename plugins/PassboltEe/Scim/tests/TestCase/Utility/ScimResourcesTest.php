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

namespace Passbolt\Scim\Test\TestCase\Utility;

use App\Test\Lib\AppTestCase;
use Passbolt\Scim\Exception\BadRequestException;
use Passbolt\Scim\Utility\Resource\UserScimResource;
use Passbolt\Scim\Utility\ScimResources;

/**
 * @covers \Passbolt\Scim\Utility\ScimResources
 */
class ScimResourcesTest extends AppTestCase
{
    public function testScimResources_Build_Success_Users(): void
    {
        $this->assertInstanceOf(UserScimResource::class, ScimResources::build(ScimResources::USERS));
    }

    /**
     * Regression for PB-51541: previously, an unsupported resource type bubbled up as a
     * generic `ScimException` (HTTP 500). It must now be a `BadRequestException` (HTTP 400).
     *
     * @dataProvider providerInvalidResourceTypes
     */
    public function testScimResources_Build_InvalidType_ThrowsBadRequest(string $invalidType): void
    {
        $this->expectException(BadRequestException::class);
        $this->expectExceptionCode(400);
        $this->expectExceptionMessage(sprintf('Invalid Resource type `%s`', $invalidType));

        ScimResources::build($invalidType);
    }

    public static function providerInvalidResourceTypes(): array
    {
        return [
            'unknown type' => ['InvalidResourceType'],
            'declared but not implemented (Groups)' => [ScimResources::GROUPS],
            'empty string' => [''],
            'wrong case' => ['users'],
        ];
    }

    public function testScimResources_IsValid(): void
    {
        $this->assertTrue(ScimResources::isValid(ScimResources::USERS));
        $this->assertFalse(ScimResources::isValid(ScimResources::GROUPS));
        $this->assertFalse(ScimResources::isValid('InvalidResourceType'));
    }
}
