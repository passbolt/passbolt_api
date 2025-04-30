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
namespace Passbolt\Sso\Test\Lib;

use App\Test\Lib\AppIntegrationTestCase;
use Cake\Core\Configure;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Cache\SsoProviderErrorCacheService;
use Passbolt\Sso\SsoPlugin;
use Passbolt\Sso\Utility\Provider\AbstractOauth2Provider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;
use PHPUnit\Framework\MockObject\MockObject;

class SsoIntegrationTestCase extends AppIntegrationTestCase
{
    use MockAzureResourceOwnerTrait;

    public const IP_ADDRESS = '127.0.0.1';
    public const USER_AGENT = 'phpunit';

    private array $disabledSsoProviders = [
        SsoSetting::PROVIDER_OAUTH2,
        SsoSetting::PROVIDER_ADFS,
    ];

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->enableCsrfToken();

        // setup IP address and user agent for requests
        $this->configRequest(['environment' => [
            'REMOTE_ADDR' => self::IP_ADDRESS,
            'HTTP_USER_AGENT' => self::USER_AGENT,
        ]]);

        // Enable disabled SSO providers for testing
        foreach ($this->disabledSsoProviders as $provider) {
            Configure::write("passbolt.plugins.sso.providers.{$provider}", true);
        }
        $this->enableFeaturePlugin(SsoPlugin::class);
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        SsoProviderFactory::clear();
        SsoProviderErrorCacheService::reset();

        parent::tearDown();
    }

    /**
     * @param string $providerClass
     * @return \PHPUnit\Framework\MockObject\MockObject|AbstractOauth2Provider
     */
    protected function getProviderMockForStage1(string $providerClass): AbstractOauth2Provider|MockObject
    {
        return $this
            ->getMockBuilder($providerClass)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAuthorizationUrl', 'getState'])
            ->getMock();
    }

    /**
     * @param string $providerClass
     * @return \PHPUnit\Framework\MockObject\MockObject|AbstractOauth2Provider
     */
    protected function getProviderMockForStage2(string $providerClass): AbstractOauth2Provider|MockObject
    {
        return $this
            ->getMockBuilder($providerClass)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAccessToken', 'getResourceOwner'])
            ->getMock();
    }
}
