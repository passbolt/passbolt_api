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
use App\Utility\UuidFactory;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\EventList;
use Cake\Event\EventManager;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpFixtureFactories\Scenario\ScenarioAwareTrait;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnDisableService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use Passbolt\MultiFactorAuthentication\Test\Scenario\Webauthn\MfaWebauthnScenario;
use Passbolt\MultiFactorAuthentication\Utility\MfaAccountSettings;
use Passbolt\MultiFactorAuthentication\Utility\MfaSettings;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnDisableService
 */
class MfaWebauthnDisableServiceTest extends TestCase
{
    use ScenarioAwareTrait;
    use TruncateDirtyTables;

    private MfaWebauthnDisableService $service;

    public function setUp(): void
    {
        parent::setUp();
        $this->service = new MfaWebauthnDisableService();
    }

    public function tearDown(): void
    {
        unset($this->service);
        parent::tearDown();
    }

    private function credentialsTable(): MfaWebauthnCredentialsTable
    {
        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $table */
        $table = TableRegistry::getTableLocator()
            ->get('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');

        return $table;
    }

    public function testMfaWebauthnDisableService_Disable_HappyPath_DeletesCredential(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->persist();

        $this->service->disable($uac, $credential->id);
        $this->assertSame(0, $this->credentialsTable()->countForUser($user->id));
    }

    public function testMfaWebauthnDisableService_Disable_LastCredential_DisablesProviderOnAccountSettings(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        [, $credential] = $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);

        $this->service->disable($uac, $credential->id);

        MfaSettings::clear();
        try {
            $settings = MfaAccountSettings::get($uac);
            $this->assertNotContains(MfaSettings::PROVIDER_WEBAUTHN, $settings->getEnabledProviders());
        } catch (RecordNotFoundException) {
            $this->assertTrue(true);
        }
    }

    public function testMfaWebauthnDisableService_Disable_LastCredential_NoAccountSettings_DoesNotThrow(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->persist();

        $this->service->disable($uac, $credential->id);
        $this->assertSame(0, $this->credentialsTable()->countForUser($user->id));
    }

    public function testMfaWebauthnDisableService_Disable_RemainingCredentials_KeepsProviderEnabled(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        [, $first] = $this->loadFixtureScenario(MfaWebauthnScenario::class, $user);
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('extra')->persist();

        $this->service->disable($uac, $first->id);

        MfaSettings::clear();
        $settings = MfaAccountSettings::get($uac);
        $this->assertContains(MfaSettings::PROVIDER_WEBAUTHN, $settings->getEnabledProviders());
    }

    public function testMfaWebauthnDisableService_Disable_CredentialNotOwned_Throws(): void
    {
        /** @var \App\Model\Entity\User $me */
        $me = UserFactory::make()->user()->persist();
        $meUac = new UserAccessControl(Role::USER, $me->id, $me->username);
        /** @var \App\Model\Entity\User $other */
        $other = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $theirs */
        $theirs = MfaWebauthnCredentialFactory::make()->withUser($other)->persist();

        try {
            $this->service->disable($meUac, $theirs->id);
            $this->fail('Expected BadRequestException');
        } catch (BadRequestException $e) {
            $this->assertStringContainsString('does not exist', $e->getMessage());
        }
        $this->assertSame(1, $this->credentialsTable()->countForUser($other->id));
    }

    public function testMfaWebauthnDisableService_Disable_UnknownCredential_Throws(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        $this->expectException(BadRequestException::class);
        $this->expectExceptionMessage('does not exist');
        $this->service->disable($uac, UuidFactory::uuid());
    }

    public function testMfaWebauthnDisableService_Disable_DispatchesRemovedEvent_OnSuccess(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->persist();

        EventManager::instance()->setEventList(new EventList());
        $this->service->disable($uac, $credential->id);

        $this->assertEventFired(
            MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT,
            EventManager::instance(),
        );
        $this->assertEventFiredWith(
            MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT,
            'uac',
            $uac,
            EventManager::instance(),
        );
        $fired = EventManager::instance()->getEventList();
        $this->assertNotNull($fired);
        $carried = null;
        foreach ($fired as $event) {
            if ($event->getName() === MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT) {
                $carried = $event->getData('credential');
            }
        }
        $this->assertNotNull($carried);
        $this->assertSame($credential->id, $carried->id);
    }

    public function testMfaWebauthnDisableService_Disable_DoesNotDispatchEvent_OnFailure(): void
    {
        $uac = UserFactory::make()->user()->persistedUAC();

        EventManager::instance()->setEventList(new EventList());
        try {
            $this->service->disable($uac, UuidFactory::uuid());
            $this->fail('Expected BadRequestException');
        } catch (BadRequestException) {
        }

        $fired = EventManager::instance()->getEventList();
        $this->assertNotNull($fired);
        foreach ($fired as $event) {
            $this->assertNotSame(
                MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT,
                $event->getName(),
            );
        }
    }
}
