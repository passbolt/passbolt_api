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
 * @since         5.17.0
 */
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Service\Webauthn;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\MfaWebauthnPolicyInterface;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use ReflectionProperty;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnableService
 */
class MfaWebauthnEnableServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnEnableService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MfaWebauthnEnableService(new DefaultMfaWebauthnPolicyService());
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaWebauthnEnableService_Enable_PersistsCredential_UnderDefaultPolicy(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-default');
        $saved = $this->service->enable($uac, $record, 'Yubikey');

        $this->assertSame($uac->getId(), $saved->user_id);
        $this->assertSame('mfa', $saved->type);
        $this->assertSame('Yubikey', $saved->name);
    }

    public function testMfaWebauthnEnableService_Enable_RejectsBackupEligible_WhenPolicySaysSo(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-synced');
        (new ReflectionProperty($record, 'backupEligible'))->setValue($record, true);

        $policy = $this->createMock(MfaWebauthnPolicyInterface::class);
        $policy->method('rejectBackupEligible')->willReturn(true);
        $policy->method('getMaxCredentialsPerUser')->willReturn(10);

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('backup-eligible');
        (new MfaWebauthnEnableService($policy))->enable($uac, $record, null);
    }

    public function testMfaWebauthnEnableService_Enable_AcceptsBackupEligible_WhenPolicyAllows(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-synced-ok');
        (new ReflectionProperty($record, 'backupEligible'))->setValue($record, true);

        $saved = $this->service->enable($uac, $record, null);
        $this->assertSame($uac->getId(), $saved->user_id);
    }

    public function testMfaWebauthnEnableService_Enable_DispatchesEnabledEvent(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        EventManager::instance()->setEventList(new EventList());
        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-event');
        $saved = $this->service->enable($uac, $record, 'Yubikey');

        $this->assertEventFired(
            MfaWebauthnEnableService::MFA_WEBAUTHN_CREDENTIAL_ENABLED_EVENT,
            EventManager::instance(),
        );
        $this->assertEventFiredWith(
            MfaWebauthnEnableService::MFA_WEBAUTHN_CREDENTIAL_ENABLED_EVENT,
            'credential',
            $saved,
            EventManager::instance(),
        );
        $this->assertEventFiredWith(
            MfaWebauthnEnableService::MFA_WEBAUTHN_CREDENTIAL_ENABLED_EVENT,
            'uac',
            $uac,
            EventManager::instance(),
        );
    }

    public function testMfaWebauthnEnableService_Enable_RejectsWhenMaxCredentialsReached(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        MfaWebauthnCredentialFactory::make(10)->withUser($user)->persist();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-eleventh');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('maximum');
        $this->service->enable(new UserAccessControl(Role::USER, $user->id, $user->username), $record, null);
    }
}
