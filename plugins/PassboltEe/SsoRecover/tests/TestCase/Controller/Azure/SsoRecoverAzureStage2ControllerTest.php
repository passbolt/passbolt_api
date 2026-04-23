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
 * @since         5.3.0
 */

namespace Passbolt\SsoRecover\Test\TestCase\Controller\Azure;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use League\OAuth2\Client\Token\AccessToken;
use Passbolt\Sso\Controller\AbstractSso2Stage2Controller;
use Passbolt\Sso\Error\Exception\AzureException;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoProviderTestTrait;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;
use Passbolt\Sso\Utility\Provider\SsoProviderFactory;
use Passbolt\SsoRecover\Test\Lib\SsoRecoverIntegrationTestCase;

/**
 * @covers \Passbolt\Sso\Controller\AbstractSso2Stage2Controller
 */
class SsoRecoverAzureStage2ControllerTest extends SsoRecoverIntegrationTestCase
{
    use SsoProviderTestTrait;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        EventManager::instance()->setEventList(new EventList());
    }

    public function testSsoRecoverAzureStage2Controller_ErrorSecretExpired(): void
    {
        Configure::write('passbolt.security.userIp', false);
        Configure::write('passbolt.security.userAgent', false);
        $user = UserFactory::make()->active()->persist();
        $settings = SsoSettingsFactory::make()->azure()->active()->persist();
        /** @var \Passbolt\Sso\Model\Entity\SsoState $ssoState */
        $ssoState = SsoStateFactory::make()
            ->withTypeSsoRecover()
            ->userId($user->get('id'))
            ->ssoSettingsId($settings->get('id'))
            ->persist();
        $this->cookie('passbolt_sso_state', $ssoState->state);
        // Mock provider
        $mockAzureProvider = $this->getProviderMockForStage2(AzureProvider::class);
        $mockAzureProvider->method('getAccessToken')->willReturn(new AccessToken(['access_token' => 'foo']));
        $exception = new AzureException('invalid_client', 'The provided client secret keys for app \'foo\' are expired.');
        $mockAzureProvider->method('getResourceOwner')->willThrowException($exception);
        // Swap actual implementation
        SsoProviderFactory::set($mockAzureProvider);

        $this->get('/sso/azure/redirect?state=' . $ssoState->state . '&code=' . UuidFactory::uuid());

        $this->assertResponseCode(400);
        $this->assertEventFiredWith(AbstractSso2Stage2Controller::EVENT_PROVIDER_ERROR_RESOURCE_OWNER, 'exception', $exception);
    }
}
