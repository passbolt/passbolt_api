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

namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Notification\Email;

use App\Model\Entity\Role;
use App\Test\Factory\UserFactory;
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Notification\Email\MfaWebauthnCredentialRemovedEmailRedactor;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnDisableService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Notification\Email\MfaWebauthnCredentialRemovedEmailRedactor
 */
class MfaWebauthnCredentialRemovedEmailRedactorTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnCredentialRemovedEmailRedactor $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new MfaWebauthnCredentialRemovedEmailRedactor();
    }

    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testMfaWebauthnCredentialRemovedEmailRedactor_SubscribesToRemovedEvent(): void
    {
        $this->assertContains(
            MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT,
            $this->sut->getSubscribedEvents(),
        );
    }

    public function testMfaWebauthnCredentialRemovedEmailRedactor_BuildsEmailForActiveUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()
            ->withUser($user)
            ->credentialId('yk-01')
            ->name('Yubikey 5 NFC')
            ->persist();

        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $event = new Event(MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(1, $emails->getEmails());
        $email = $emails->getEmails()[0];
        $this->assertSame($user->username, $email->getRecipient());
        $this->assertSame(
            __('An MFA passkey was removed from your account.'),
            $email->getSubject(),
        );
        $this->assertSame(MfaWebauthnCredentialRemovedEmailRedactor::TEMPLATE, $email->getTemplate());
        $emailData = $email->getData();
        $this->assertSame(__('MFA passkey removed.'), $emailData['title']);
        $this->assertSame($user->id, $emailData['body']['user']['id']);
        $this->assertSame($credential->id, $emailData['body']['credential']['id']);
        $this->assertSame('Yubikey 5 NFC', $emailData['body']['credential']['name']);
    }

    public function testMfaWebauthnCredentialRemovedEmailRedactor_SkipsDisabledUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->disabled()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('c1')->persist();

        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $event = new Event(MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(0, $emails->getEmails());
    }

    public function testMfaWebauthnCredentialRemovedEmailRedactor_SkipsInactiveUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->inactive()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('c1')->persist();

        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $event = new Event(MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(0, $emails->getEmails());
    }

    public function testMfaWebauthnCredentialRemovedEmailRedactor_SkipsDeletedUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->patchData(['deleted' => true])->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('c1')->persist();

        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $event = new Event(MfaWebauthnDisableService::MFA_WEBAUTHN_CREDENTIAL_REMOVED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(0, $emails->getEmails());
    }
}
