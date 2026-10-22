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
use Passbolt\MultiFactorAuthentication\Notification\Email\MfaWebauthnCredentialEnrolledEmailRedactor;
use Passbolt\MultiFactorAuthentication\Service\Webauthn\MfaWebauthnEnrollCredentialService;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;

/**
 * @covers \Passbolt\MultiFactorAuthentication\Notification\Email\MfaWebauthnCredentialEnrolledEmailRedactor
 */
class MfaWebauthnCredentialEnrolledEmailRedactorTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnCredentialEnrolledEmailRedactor $sut;

    public function setUp(): void
    {
        parent::setUp();
        $this->loadPlugins(['Passbolt/Locale' => []]);
        $this->sut = new MfaWebauthnCredentialEnrolledEmailRedactor();
    }

    public function tearDown(): void
    {
        unset($this->sut);
        parent::tearDown();
    }

    public function testMfaWebauthnCredentialEnrolledEmailRedactor_SubscribesToEnrolledEvent(): void
    {
        $this->assertContains(
            MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT,
            $this->sut->getSubscribedEvents(),
        );
    }

    public function testMfaWebauthnCredentialEnrolledEmailRedactor_BuildsEmailForActiveUser(): void
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
        $event = new Event(MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(1, $emails->getEmails());
        $email = $emails->getEmails()[0];
        $this->assertSame(
            __('A new MFA passkey was added to your account.'),
            $email->getSubject(),
        );
        $this->assertSame(MfaWebauthnCredentialEnrolledEmailRedactor::TEMPLATE, $email->getTemplate());
        $emailData = $email->getData();
        $this->assertSame(__('New MFA passkey enrolled.'), $emailData['title']);
        $this->assertSame($user->id, $emailData['body']['user']['id']);
        $this->assertSame($credential->id, $emailData['body']['credential']['id']);
    }

    public function testMfaWebauthnCredentialEnrolledEmailRedactor_SkipsDisabledUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->disabled()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('c1')->persist();

        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $event = new Event(MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(0, $emails->getEmails());
    }

    public function testMfaWebauthnCredentialEnrolledEmailRedactor_SkipsInactiveUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->inactive()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('c1')->persist();

        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $event = new Event(MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(0, $emails->getEmails());
    }

    public function testMfaWebauthnCredentialEnrolledEmailRedactor_SkipsDeletedUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->patchData(['deleted' => true])->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('c1')->persist();

        $uac = new UserAccessControl(Role::USER, $user->id, $user->username);
        $event = new Event(MfaWebauthnEnrollCredentialService::MFA_WEBAUTHN_CREDENTIAL_ENROLLED_EVENT);
        $event->setData(['uac' => $uac, 'credential' => $credential]);

        $emails = $this->sut->onSubscribedEvent($event);

        $this->assertCount(0, $emails->getEmails());
    }
}
