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
use App\Utility\UuidFactory;
use Cake\Http\Exception\BadRequestException;
use Passbolt\Sso\Service\SsoStates\SsoStatesAssertService;
use Passbolt\Sso\Test\Factory\SsoSettingsFactory;
use Passbolt\Sso\Test\Factory\SsoStateFactory;
use Passbolt\Sso\Test\Lib\SsoTestCase;

/**
 * @see \Passbolt\Sso\Service\SsoStates\SsoStatesAssertService
 */
class SsoStatesAssertServiceTest extends SsoTestCase
{
    /**
     * @var \Passbolt\Sso\Service\SsoStates\SsoStatesAssertService
     */
    private $service;

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->service = new SsoStatesAssertService();
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
    {
        unset($this->service);

        parent::tearDown();
    }

    public function testSsoStatesAssertService_ErrorInvalidState(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make(['state' => 'foo'])
            ->ssoSettingsId($ssoSettingId)
            ->userId($user->id)
            ->persist();
        $uac = new ExtendedUserAccessControl(
            $user->role->id,
            $user->id,
            $user->username,
            $ssoState->ip,
            $ssoState->user_agent
        );

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('The SSO state is invalid.');

        $this->service->assertAndConsume($ssoState, $ssoSettingId, $uac);
    }

    public function testSsoStatesAssertService_ErrorStateExpired(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make()
            ->ssoSettingsId($ssoSettingId)
            ->userId($user->id)
            ->deleted()
            ->persist();
        $uac = new ExtendedUserAccessControl(
            $user->role->id,
            $user->id,
            $user->username,
            $ssoState->ip,
            $ssoState->user_agent
        );

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('The SSO state is expired.');

        $this->service->assertAndConsume($ssoState, $ssoSettingId, $uac);
    }

    public function testSsoStatesAssertService_ErrorInvalidUac(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make()
            ->ssoSettingsId($ssoSettingId)
            ->userId($user->id)
            ->persist();
        // Set different user data in UAC than SSO state
        $uac = new ExtendedUserAccessControl(
            Role::ADMIN,
            UuidFactory::uuid(),
            'foo@test.test',
            '127.0.0.1',
            'Foo user agent'
        );

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('User id mismatch.');

        $this->service->assertAndConsume($ssoState, $ssoSettingId, $uac);
    }

    public function testSsoStatesAssertService_ErrorInvalidIp(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make()
            ->ssoSettingsId($ssoSettingId)
            ->userId($user->id)
            ->persist();
        $uac = new ExtendedUserAccessControl(
            $user->role->name,
            $user->id,
            $user->username,
            '127.0.0.1', // Different IP
            $ssoState->user_agent
        );

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('User IP mismatch.');

        $this->service->assertAndConsume($ssoState, $ssoSettingId, $uac);
    }

    public function testSsoStatesAssertService_ErrorInvalidUserAgent(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make()
            ->ssoSettingsId($ssoSettingId)
            ->userId($user->id)
            ->persist();
        $uac = new ExtendedUserAccessControl(
            $user->role->name,
            $user->id,
            $user->username,
            $ssoState->ip,
            'foo agent' // Different User Agent
        );

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('User agent mismatch.');

        $this->service->assertAndConsume($ssoState, $ssoSettingId, $uac);
    }

    public function testSsoStatesAssertService_ErrorSettingsIdMismatch(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make()
            ->ssoSettingsId($ssoSettingId)
            ->userId($user->id)
            ->persist();
        $uac = new ExtendedUserAccessControl(
            $user->role->name,
            $user->id,
            $user->username,
            $ssoState->ip,
            $ssoState->user_agent
        );

        $this->expectException(BadRequestException::class);
        $this->expectErrorMessage('Settings mismatch.');

        // Different SSO settings ID
        $this->service->assertAndConsume($ssoState, UuidFactory::uuid(), $uac);
    }

    public function testSsoStatesAssertService_Success(): void
    {
        $user = UserFactory::make()->admin()->persist();
        $ssoSettingId = SsoSettingsFactory::make()->persist()->get('id');
        $ssoState = SsoStateFactory::make()
            ->ssoSettingsId($ssoSettingId)
            ->userId($user->id)
            ->persist();
        $uac = new ExtendedUserAccessControl(
            $user->role->id,
            $user->id,
            $user->username,
            $ssoState->ip,
            $ssoState->user_agent
        );
        // Make sure state is active
        $this->assertTrue($ssoState->deleted->isFuture());

        $this->service->assertAndConsume($ssoState, $ssoSettingId, $uac);

        /** @var \Passbolt\Sso\Model\Entity\SsoState $result */
        $result = SsoStateFactory::find()->where(['state' => $ssoState->state])->firstOrFail();
        // Assert state consumed/deleted
        $this->assertTrue($result->deleted->isPast());
    }
}
