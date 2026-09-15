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

use App\Error\Exception\CustomValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Service\MfaPolicies\DefaultMfaWebauthnPolicyService;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnrollCredentialService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;
use ReflectionProperty;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnrollCredentialService
 */
class MfaWebauthnEnrollCredentialServiceTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnEnrollCredentialService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MfaWebauthnEnrollCredentialService(new DefaultMfaWebauthnPolicyService());
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_PersistsCredential_UnderDefaultPolicy(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-default');
        $saved = $this->service->enroll($uac, $record, 'Yubikey');

        $this->assertSame($uac->getId(), $saved->user_id);
        $this->assertSame('mfa', $saved->type);
        $this->assertSame('Yubikey', $saved->name);
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_AcceptsBackupEligibleCredential_UnderDefaultPolicy(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-synced-ok');
        (new ReflectionProperty($record, 'backupEligible'))->setValue($record, true);

        $saved = $this->service->enroll($uac, $record, null);
        $this->assertSame($uac->getId(), $saved->user_id);
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_DispatchesEnrolledEvent(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        EventManager::instance()->setEventList(new EventList());
        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-event');
        $saved = $this->service->enroll($uac, $record, 'Yubikey');

        $this->assertEventFired(
            MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT,
            EventManager::instance(),
        );
        $this->assertEventFiredWith(
            MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT,
            'credential',
            $saved,
            EventManager::instance(),
        );
        $this->assertEventFiredWith(
            MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT,
            'uac',
            $uac,
            EventManager::instance(),
        );
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_RejectsWhenMaxCredentialsReached(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        MfaWebauthnCredentialFactory::make(10)->withUser($user)->persist();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-eleventh');

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('maximum');
        $this->service->enroll(new UserAccessControl(Role::USER, $user->id, $user->username), $record, null);
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_MapsPersistenceFailureToCustomValidation(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        MfaWebauthnCredentialFactory::make()
            ->patchData(['user_id' => $uac->getId()])
            ->name('Yubikey 5C')
            ->persist();

        $record = MfaWebauthnCredentialFactory::makeCredentialRecord('cred-new');

        $this->expectException(CustomValidationException::class);
        $this->expectExceptionMessage('The WebAuthn credential could not be saved.');
        $this->service->enroll($uac, $record, 'Yubikey 5C');
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_FirstCredential_EnablesProviderInAccountSettings(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $this->service->enroll($uac, MfaWebauthnCredentialFactory::makeCredentialRecord('cred-first'), 'Key A');

        $providers = MfaAccountSettings::get($uac)->getEnabledProviders();
        $this->assertContains(MfaSettings::PROVIDER_WEBAUTHN, $providers);
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_SecondCredential_DoesNotDuplicateProviderInAccountSettings(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_WEBAUTHN, []);
        MfaWebauthnCredentialFactory::make()
            ->patchData(['user_id' => $uac->getId()])
            ->name('Key A')
            ->persist();

        $this->service->enroll($uac, MfaWebauthnCredentialFactory::makeCredentialRecord('cred-second'), 'Key B');

        $providers = MfaAccountSettings::get($uac)->getEnabledProviders();
        $webauthnOccurrences = count(array_filter(
            $providers,
            fn(string $p) => $p === MfaSettings::PROVIDER_WEBAUTHN,
        ));
        $this->assertSame(1, $webauthnOccurrences);

        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $credentials */
        $credentials = TableRegistry::getTableLocator()
            ->get('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');
        $this->assertSame(2, $credentials->countForUser($uac->getId()));
    }

    public function testMfaWebauthnEnrollCredentialService_Enroll_SecondCredential_StillDispatchesEnrolledEvent(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();
        MfaAccountSettings::enableProvider($uac, MfaSettings::PROVIDER_WEBAUTHN, []);
        MfaWebauthnCredentialFactory::make()
            ->patchData(['user_id' => $uac->getId()])
            ->name('Key A')
            ->persist();

        EventManager::instance()->setEventList(new EventList());
        $saved = $this->service->enroll(
            $uac,
            MfaWebauthnCredentialFactory::makeCredentialRecord('cred-second'),
            'Key B',
        );

        $this->assertEventFired(
            MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT,
            EventManager::instance(),
        );
        $this->assertEventFiredWith(
            MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT,
            'credential',
            $saved,
            EventManager::instance(),
        );
    }
}
