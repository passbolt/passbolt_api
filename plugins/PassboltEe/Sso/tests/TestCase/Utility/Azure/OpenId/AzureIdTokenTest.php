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

namespace Passbolt\Sso\Test\TestCase\Utility\Azure\OpenId;

use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Passbolt\Sso\Test\Lib\AzureProviderTestTrait;
use Passbolt\Sso\Utility\Azure\OpenId\AzureIdToken;

/**
 * @see \Passbolt\Sso\Utility\Azure\OpenId\AzureIdToken
 */
class AzureIdTokenTest extends TestCase
{
    use AzureProviderTestTrait;

    public function testSsoAzureIdToken_ErrorMissingOptions(): void
    {
        $provider = $this->getDummyAzureProvider();
        $this->expectException(InvalidArgumentException::class);
        $options = [];
        new AzureIdToken($options, $provider);
    }

    public function testSsoAzureIdToken_ErrorMissinIdToken(): void
    {
        $provider = $this->getDummyAzureProvider();
        $this->expectException(BadRequestException::class);
        $options = [
            'access_token' => 'test',
            'expires_in' => 0,
        ];
        new AzureIdToken($options, $provider);
    }

    public function testSsoAzureIdToken_ErrorInvalidIdToken(): void
    {
        $provider = $this->getDummyAzureProvider();
        $this->expectException(BadRequestException::class);
        $options = [
            'access_token' => 'test',
            'expires_in' => 0,
            'id_token' => [],
        ];
        new AzureIdToken($options, $provider);
    }

    public function testSsoAzureIdToken_ErrorVerificationKeys(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_ErrorJwtDecodeFails(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_ErrorJwtInvalidKey(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_ErrorJwtExpired(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_ErrorJwtIssMissing(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_ErrorJwtIssInvalid(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_ErrorJwtEmailMissing(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_ErrorJwtEmailInvalid(): void
    {
        $this->markTestIncomplete();
    }

    public function testSsoAzureIdToken_Success(): void
    {
        $this->markTestIncomplete();
    }
}
