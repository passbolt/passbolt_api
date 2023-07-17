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

namespace Passbolt\Sso\Test\TestCase\Service\SsoAuthenticationTokens;

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\ExtendedUserAccessControl;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Exception\BadRequestException;
use CakephpFixtureFactories\Error\PersistenceException;
use Passbolt\Sso\Model\Entity\SsoAuthenticationToken;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoAuthenticationTokens\SsoAuthenticationTokenGetService;
use Passbolt\Sso\Test\Factory\SsoAuthenticationTokenFactory;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

class SsoAuthenticationTokenGetServiceTest extends SsoTestCase
{
    public function testSsoAuthenticationTokenGetService_ErrorToken(): void
    {
        $service = new SsoAuthenticationTokenGetService();
        $this->expectException(BadRequestException::class);
        $service->getOrFail('nope', SsoState::TYPE_SSO_SET_SETTINGS, UuidFactory::uuid());
    }

    public function testSsoAuthenticationTokenGetService_ErrorUserId(): void
    {
        $service = new SsoAuthenticationTokenGetService();
        $this->expectException(BadRequestException::class);
        $service->getOrFail(UuidFactory::uuid(), SsoState::TYPE_SSO_SET_SETTINGS, 'nope');
    }

    public function testSsoAuthenticationTokenGetService_Success(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        // Part 1 - Get or fail
        $authToken = $service->getOrFail($token->token, SsoState::TYPE_SSO_SET_SETTINGS, $user->id);
        $this->assertEquals($token->id, $authToken->id);
        $this->assertTrue($token->isActive());
        $this->assertFalse($token->isExpired());

        // Part 2 - Consume
        $service->assertAndConsume($authToken, $uac, $settings->id);
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updatedAuthToken */
        $updatedAuthToken = SsoAuthenticationTokenFactory::get($token->id);
        $this->assertFalse($updatedAuthToken->isActive());
    }

    public function testSsoAuthenticationTokenGetService_SuccessNoUserId(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId(UuidFactory::uuid())
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => '127.0.0.1',
                'user_agent' => 'phpunit',
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        $authToken = $service->getOrFail($token->token, SsoState::TYPE_SSO_SET_SETTINGS);
        $this->assertEquals($token->id, $authToken->id);
    }

    public function testSsoAuthenticationTokenGetService_Assert_SuccessUserIpDisabledCheck(): void
    {
        $originalSetting = Configure::read('passbolt.security.userIp');
        Configure::write('passbolt.security.userIp', false);

        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => '127.0.0.1',
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        $service->assertAndConsume($token, $uac, $settings->id);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updatedAuthToken */
        $updatedAuthToken = SsoAuthenticationTokenFactory::get($token->id);
        $this->assertFalse($updatedAuthToken->isActive());

        Configure::write('passbolt.security.userIp', $originalSetting);
    }

    public function testSsoAuthenticationTokenGetService_Assert_SuccessUserAgentDisabledCheck(): void
    {
        $originalSetting = Configure::read('passbolt.security.userAgent');
        Configure::write('passbolt.security.userAgent', false);

        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => 'same same',
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        $service->assertAndConsume($token, $uac, $settings->id);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updatedAuthToken */
        $updatedAuthToken = SsoAuthenticationTokenFactory::get($token->id);
        $this->assertFalse($updatedAuthToken->isActive());

        Configure::write('passbolt.security.userAgent', $originalSetting);
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorIsExpired(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '127.0.0.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->expired()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        // Check that Assertion fails
        $this->expectException(BadRequestException::class);
        $service->assertAndConsume($token, $uac, $settings->id);

        // Check tha the token is Consumed even if assert failed
        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $updatedAuthToken */
        $updatedAuthToken = SsoAuthenticationTokenFactory::get($token->id);
        $this->assertFalse($updatedAuthToken->isActive());
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorSettingMissing(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                //'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('Settings id is missing', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorUserIdMismatch(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);

        /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId(UuidFactory::uuid())
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('User id mismatch', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorUserIdNotUuid(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        try {
            /** @var \Passbolt\Sso\Model\Entity\SsoAuthenticationToken $token */
            $token = SsoAuthenticationTokenFactory::make()
                ->type(SsoState::TYPE_SSO_SET_SETTINGS)
                ->userId('nope')
                ->active()
                ->data([
                    'sso_setting_id' => $settings->id,
                    'ip' => $ip,
                    'user_agent' => $ua,
                ])
                ->persist();
        } catch (PersistenceException $exception) {
            // postgresql doesn't allow for this behavior since
            // user id is typed as UUID in DB -> skip
            $this->markTestSkipped();
        }

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('User id mismatch', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorUserIpMissing(): void
    {
        $originalSetting = Configure::read('passbolt.security.userIp');
        Configure::write('passbolt.security.userIp', true);

        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            Configure::write('passbolt.security.userIp', $originalSetting);
            $this->fail();
        } catch (BadRequestException $exception) {
            Configure::write('passbolt.security.userIp', $originalSetting);
            $this->assertTextContains('IP is missing', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorUserIpMismatch(): void
    {
        $originalSetting = Configure::read('passbolt.security.userIp');
        Configure::write('passbolt.security.userIp', true);

        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => '127.0.0.1',
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            Configure::write('passbolt.security.userIp', $originalSetting);
            $this->fail();
        } catch (BadRequestException $exception) {
            Configure::write('passbolt.security.userIp', $originalSetting);
            $this->assertTextContains('IP mismatch', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorUserAgentMissing(): void
    {
        $originalSetting = Configure::read('passbolt.security.userAgent');
        Configure::write('passbolt.security.userAgent', true);

        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                //'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            Configure::write('passbolt.security.userAgent', $originalSetting);
            $this->fail();
        } catch (BadRequestException $exception) {
            Configure::write('passbolt.security.userAgent', $originalSetting);
            $this->assertTextContains('User agent is missing', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorUserAgentMismatch(): void
    {
        $originalSetting = Configure::read('passbolt.security.userAgent');
        Configure::write('passbolt.security.userAgent', true);

        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => $settings->id,
                'ip' => $ip,
                'user_agent' => 'nope',
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            Configure::write('passbolt.security.userAgent', $originalSetting);
            $this->fail();
        } catch (BadRequestException $exception) {
            Configure::write('passbolt.security.userAgent', $originalSetting);
            $this->assertTextContains('User agent mismatch', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_Assert_ErrorSettingsIdMismatch(): void
    {
        $settings = SsoSettingsFactory::make()->azure()->persist();
        $user = UserFactory::make()->admin()->active()->persist();
        $ip = '192.168.1.1';
        $ua = 'phpunit';
        $uac = new ExtendedUserAccessControl(Role::ADMIN, $user->id, $user->username, $ip, $ua);
        $token = SsoAuthenticationTokenFactory::make()
            ->type(SsoState::TYPE_SSO_SET_SETTINGS)
            ->userId($user->id)
            ->active()
            ->data([
                'sso_setting_id' => UuidFactory::uuid(),
                'ip' => $ip,
                'user_agent' => $ua,
            ])
            ->persist();

        $service = new SsoAuthenticationTokenGetService();

        try {
            $service->assertAndConsume($token, $uac, $settings->id);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('Settings mismatch', $exception->getMessage());
        }
    }

    public function testSsoAuthenticationTokenGetService_GetActiveNotExpiredOrFail_ErrorTokenNotExist(): void
    {
        $service = new SsoAuthenticationTokenGetService();

        $this->expectException(RecordNotFoundException::class);

        $service->getActiveNotExpiredOrFail(UuidFactory::uuid(), SsoState::TYPE_SSO_RECOVER);
    }

    public function testSsoAuthenticationTokenGetService_GetActiveNotExpiredOrFail_ErrorTokenDeleted(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()->inactive()->persist();
        $service = new SsoAuthenticationTokenGetService();

        $this->expectException(RecordNotFoundException::class);

        $service->getActiveNotExpiredOrFail($authToken->token, SsoState::TYPE_SSO_RECOVER);
    }

    public function testSsoAuthenticationTokenGetService_GetActiveNotExpiredOrFail_ErrorTokenExpired(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()
            ->active()
            ->expired()
            ->type(SsoState::TYPE_SSO_RECOVER)
            ->persist();
        $service = new SsoAuthenticationTokenGetService();

        $this->expectException(CustomValidationException::class);

        $service->getActiveNotExpiredOrFail($authToken->token, SsoState::TYPE_SSO_RECOVER);
    }

    public function testSsoAuthenticationTokenGetService_GetActiveNotExpiredOrFail_Success(): void
    {
        $authToken = SsoAuthenticationTokenFactory::make()->active()->type(SsoState::TYPE_SSO_RECOVER)->persist();
        $service = new SsoAuthenticationTokenGetService();

        $result = $service->getActiveNotExpiredOrFail($authToken->token, SsoState::TYPE_SSO_RECOVER);

        $this->assertInstanceOf(SsoAuthenticationToken::class, $result);
        $this->assertEquals($authToken->token, $result->token);
    }
}
