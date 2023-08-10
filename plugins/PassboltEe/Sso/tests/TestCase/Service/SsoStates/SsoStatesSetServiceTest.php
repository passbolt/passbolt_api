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
 * @since         3.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Service\SsoStates;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\ExtendedUserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\Exception\InternalErrorException;
use Passbolt\Sso\Model\Entity\SsoState;
use Passbolt\Sso\Service\SsoStates\SsoStatesSetService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

class SsoStatesSetServiceTest extends SsoTestCase
{
    /**
     * @var \Passbolt\Sso\Service\SsoStates\SsoStatesSetService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SsoStatesSetService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testSsoStatesSetService_Success_TypeSsoSetSettings(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $nonce = SsoState::generate();
        $state = SsoState::generate();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            '127.0.0.1',
            'PHPUnit User Agent'
        );

        $result = $this->service->create(
            $nonce,
            $state,
            SsoState::TYPE_SSO_SET_SETTINGS,
            $ssoSettingId,
            $uac
        );

        $this->assertInstanceOf(SsoState::class, $result);
        $this->assertEquals($state, $result->state);
        $this->assertEquals(SsoState::TYPE_SSO_SET_SETTINGS, $result->type);
        $this->assertEquals($ssoSettingId, $result->sso_settings_id);
        $this->assertEquals($uac->getId(), $result->user_id);
        $this->assertEquals($uac->getUserIp(), $result->ip);
        $this->assertEquals($uac->getUserAgent(), $result->user_agent);
        $this->assertTrue($result->deleted->isFuture());
    }

    public function testSsoStatesSetService_Success_TypeSsoGetKey(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $nonce = SsoState::generate();
        $state = SsoState::generate();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            '127.0.0.1',
            'PHPUnit User Agent'
        );

        $result = $this->service->create(
            $nonce,
            $state,
            SsoState::TYPE_SSO_GET_KEY,
            $ssoSettingId,
            $uac
        );

        $this->assertInstanceOf(SsoState::class, $result);
        $this->assertEquals($state, $result->state);
        $this->assertEquals(SsoState::TYPE_SSO_GET_KEY, $result->type);
        $this->assertEquals($ssoSettingId, $result->sso_settings_id);
        $this->assertEquals($uac->getId(), $result->user_id);
        $this->assertEquals($uac->getUserIp(), $result->ip);
        $this->assertEquals($uac->getUserAgent(), $result->user_agent);
        $this->assertTrue($result->deleted->isFuture());
    }

    public function testSsoStatesSetService_Error_InvalidState(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $nonce = SsoState::generate();
        $state = 'some-random-value';
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            '127.0.0.1',
            'PHPUnit User Agent'
        );

        $this->expectException(InternalErrorException::class);
        $this->expectErrorMessage('Could not save the SSO state, please try again later.');

        $this->service->create($nonce, $state, SsoState::TYPE_SSO_GET_KEY, $ssoSettingId, $uac);
    }

    public function testSsoStatesSetService_Error_InvalidNonce(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $nonce = 'some-random-value';
        $state = SsoState::generate();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            '127.0.0.1',
            'PHPUnit User Agent'
        );

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('invalid nonce');

        $this->service->create($nonce, $state, SsoState::TYPE_SSO_GET_KEY, $ssoSettingId, $uac);
    }

    public function testSsoStatesSetService_Error_UniqueNonce(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $nonce = SsoState::generate();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            '127.0.0.1',
            'PHPUnit User Agent'
        );

        $this->service->create(
            $nonce,
            SsoState::generate(),
            SsoState::TYPE_SSO_SET_SETTINGS,
            $ssoSettingId,
            $uac
        );

        $this->expectException(InternalErrorException::class);
        $this->expectErrorMessage('Could not save the SSO state');

        // Storing state with the nonce value that is already present should throw error
        $this->service->create(
            $nonce,
            SsoState::generate(),
            SsoState::TYPE_SSO_SET_SETTINGS,
            $ssoSettingId,
            $uac
        );
    }

    public function testSsoStatesSetService_Error_UniqueState(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $state = SsoState::generate();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            $user->id,
            $user->username,
            '127.0.0.1',
            'PHPUnit User Agent'
        );

        $this->service->create(
            SsoState::generate(),
            $state,
            SsoState::TYPE_SSO_SET_SETTINGS,
            $ssoSettingId,
            $uac
        );

        $this->expectException(InternalErrorException::class);
        $this->expectErrorMessage('Could not save the SSO state');

        // Storing state with the state value that is already present should throw error
        $this->service->create(
            SsoState::generate(),
            $state,
            SsoState::TYPE_SSO_SET_SETTINGS,
            $ssoSettingId,
            $uac
        );
    }
}
