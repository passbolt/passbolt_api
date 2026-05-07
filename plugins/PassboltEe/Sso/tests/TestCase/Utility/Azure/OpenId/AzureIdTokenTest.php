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

use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use InvalidArgumentException;
use Passbolt\Sso\Test\Lib\AzureProviderTestTrait;
use Passbolt\Sso\Utility\Azure\OpenId\AzureIdToken;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;

/**
 * @see \Passbolt\Sso\Utility\Azure\OpenId\AzureIdToken
 */
class AzureIdTokenTest extends TestCase
{
    use AzureProviderTestTrait;

    private AzureProvider $azureProvider;

    private array $config = [];

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setup();

        $this->config = [
            'tenant' => UuidFactory::uuid(),
            'clientId' => 'client-id',
            'clientSecret' => 'super-strong-client-secret',
            'redirectUri' => Router::url('/sso/azure/redirect', true),
        ];
        $this->azureProvider = new AzureProvider($this->config);
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        unset($this->azureProvider);

        parent::tearDown();
    }

    public function testSsoAzureIdToken_ErrorMissingOptions(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $options = [];
        new AzureIdToken($options, $this->azureProvider);
    }

    public function testSsoAzureIdToken_ErrorMissinIdToken(): void
    {
        $this->expectException(BadRequestException::class);
        $options = [
            'access_token' => 'test',
            'expires_in' => 0,
        ];
        new AzureIdToken($options, $this->azureProvider);
    }

    public function testSsoAzureIdToken_ErrorInvalidIdToken(): void
    {
        $this->expectException(BadRequestException::class);
        $options = [
            'access_token' => 'test',
            'expires_in' => 0,
            'id_token' => [],
        ];
        new AzureIdToken($options, $this->azureProvider);
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
