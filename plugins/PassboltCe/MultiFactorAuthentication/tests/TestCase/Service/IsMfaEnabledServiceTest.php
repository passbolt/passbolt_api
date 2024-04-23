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
 * @since         2.14.0
 */

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service;

use App\Model\Entity\User;
use Exception;
use Passbolt\MultiFactorAuthentication\Service\GetMfaAccountSettingsService;
use Passbolt\MultiFactorAuthentication\Service\IsMfaEnabledService;
use Passbolt\MultiFactorAuthentication\Service\MfaOrgSettings\MfaOrgSettingsGetService;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaOrgSettings;
use PHPUnit\Framework\TestCase;

class IsMfaEnabledServiceTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|MfaOrgSettings
     */
    private $mfaOrgSettingsMock;

    /**
     * @var IsMfaEnabledService
     */
    private $sut;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|GetMfaAccountSettingsService
     */
    private $getMfaAccountSettingsServiceMock;

    /**
     * @var \PHPUnit\Framework\MockObject\MockObject|MfaOrgSettingsGetService
     */
    private $mfaOrgSettingsGetServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->mfaOrgSettingsMock = $this->createMock(MfaOrgSettings::class);
        $this->getMfaAccountSettingsServiceMock = $this->createMock(GetMfaAccountSettingsService::class);
        $this->mfaOrgSettingsGetServiceMock = $this->createMock(MfaOrgSettingsGetService::class);

        $this->sut = new IsMfaEnabledService(
            $this->mfaOrgSettingsGetServiceMock,
            $this->getMfaAccountSettingsServiceMock
        );
    }

    public function testThatIsEnabledForUserReturnFalseWhenOrgIsNotEnabled()
    {
        $this->mfaOrgSettingsGetServiceMock->expects($this->once())
            ->method('get')
            ->willReturn($this->mfaOrgSettingsMock);

        $this->mfaOrgSettingsMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(false);

        $this->assertFalse($this->sut->isEnabledForUser(new User()));
    }

    public function testThatIsEnabledForUserReturnFalseWhenFailedToRetrieveMfaSettings()
    {
        $this->mfaOrgSettingsGetServiceMock->expects($this->once())
            ->method('get')
            ->willReturn($this->mfaOrgSettingsMock);

        $this->mfaOrgSettingsMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->mfaOrgSettingsMock->expects($this->once())
            ->method('getEnabledProviders')
            ->willReturn([]);

        $this->getMfaAccountSettingsServiceMock->expects($this->once())
            ->method('getSettingsForUser')
            ->willThrowException(new Exception());

        $this->assertFalse($this->sut->isEnabledForUser(new User()));
    }

    /**
     * @dataProvider provideProvidersForDisabledMfa
     */
    public function testThatIsEnabledForUserReturnFalseWhenOrgAndUserHaveNoCommonEnabledProviders(
        array $orgEnabledProviders,
        array $accountEnabledProviders
    ) {
        $this->mfaOrgSettingsGetServiceMock->expects($this->once())
            ->method('get')
            ->willReturn($this->mfaOrgSettingsMock);

        $this->mfaOrgSettingsMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->mfaOrgSettingsMock->expects($this->once())
            ->method('getEnabledProviders')
            ->willReturn($orgEnabledProviders);

        $mfaAccountSettingsMock = $this->createMock(MfaAccountSettings::class);
        $mfaAccountSettingsMock
            ->expects($this->once())
            ->method('getEnabledProviders')
            ->willReturn($accountEnabledProviders);

        $this->getMfaAccountSettingsServiceMock->expects($this->once())
            ->method('getSettingsForUser')
            ->willReturn($mfaAccountSettingsMock);

        $this->assertFalse($this->sut->isEnabledForUser(new User()));
    }

    /**
     * @dataProvider provideProvidersForEnabledMfa
     */
    public function testThatIsEnabledForUserReturnTrueWhenOrgAndUserHaveCommonEnabledProviders(
        array $orgEnabledProviders,
        array $accountEnabledProviders
    ) {
        $this->mfaOrgSettingsGetServiceMock->expects($this->once())
            ->method('get')
            ->willReturn($this->mfaOrgSettingsMock);

        $this->mfaOrgSettingsMock->expects($this->once())
            ->method('isEnabled')
            ->willReturn(true);

        $this->mfaOrgSettingsMock->expects($this->once())
            ->method('getEnabledProviders')
            ->willReturn($orgEnabledProviders);

        $mfaAccountSettingsMock = $this->createMock(MfaAccountSettings::class);
        $mfaAccountSettingsMock
            ->expects($this->once())
            ->method('getEnabledProviders')
            ->willReturn($accountEnabledProviders);

        $this->getMfaAccountSettingsServiceMock->expects($this->once())
            ->method('getSettingsForUser')
            ->willReturn($mfaAccountSettingsMock);

        $this->assertTrue($this->sut->isEnabledForUser(new User()));
    }

    /**
     * @return array
     */
    public function provideProvidersForDisabledMfa()
    {
        return [
            [
                ['totp'], ['yubikey'],
            ],
            [
                ['totp'], [],
            ],
            [
                [], ['totp'],
            ],
            [
                [], [],
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideProvidersForEnabledMfa()
    {
        return [
            [
                ['totp'], ['totp'],
            ],
            [
                ['totp'], ['totp', 'yubikey'],
            ],
            [
                ['totp', 'yubikey'], ['totp'],
            ],
        ];
    }
}
