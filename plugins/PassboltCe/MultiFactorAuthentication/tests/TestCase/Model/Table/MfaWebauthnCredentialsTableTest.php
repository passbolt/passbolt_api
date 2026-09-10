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
namespace Passbolt\MultiFactorAuthentication\Test\TestCase\Model\Table;

use App\Test\Factory\UserFactory;
use App\Utility\UuidFactory;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use CakephpTestSuiteLight\Fixture\TruncateDirtyTables;
use Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable;
use Passbolt\MultiFactorAuthentication\Test\Factory\MfaWebauthnCredentialFactory;
use ReflectionProperty;
use Webauthn\CredentialRecord;

/**
 * @covers \App\Model\Entity\WebauthnCredential
 * @covers \App\Model\Table\WebauthnCredentialsTable
 * @covers \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable
 */
class MfaWebauthnCredentialsTableTest extends TestCase
{
    use TruncateDirtyTables;

    private MfaWebauthnCredentialsTable $Table;

    public function setUp(): void
    {
        parent::setUp();
        /** @var \Passbolt\MultiFactorAuthentication\Model\Table\MfaWebauthnCredentialsTable $table */
        $table = TableRegistry::getTableLocator()
            ->get('Passbolt/MultiFactorAuthentication.MfaWebauthnCredentials');
        $this->Table = $table;
    }

    public function tearDown(): void
    {
        unset($this->Table);
        parent::tearDown();
    }

    private function recordWithCounter(string $credentialId, int $counter): CredentialRecord
    {
        $record = MfaWebauthnCredentialFactory::makeCredentialRecord($credentialId);
        (new ReflectionProperty($record, 'counter'))->setValue($record, $counter);

        return $record;
    }

    public function testMfaWebauthnCredentialsTable_Save_Rehydrates_CredentialRecord_ThroughDbType(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        $credentialId = 'cred_' . bin2hex(random_bytes(4));

        $entity = $this->Table->newEntity(
            [
                'id' => UuidFactory::uuid(),
                'user_id' => $user->id,
                'type' => 'mfa',
                'credential_id' => $credentialId,
                'data' => MfaWebauthnCredentialFactory::makeCredentialRecord($credentialId),
                'sign_count' => 0,
                'name' => 'My Key',
            ],
            ['accessibleFields' => [
                'id' => true, 'user_id' => true, 'type' => true, 'credential_id' => true,
                'data' => true, 'sign_count' => true, 'name' => true,
            ]],
        );
        $saved = $this->Table->save($entity);
        $this->assertNotFalse($saved);

        $found = $this->Table->findOneByCredentialId($credentialId);
        $this->assertNotNull($found);
        $this->assertInstanceOf(CredentialRecord::class, $found->data);
        $this->assertSame($credentialId, $found->data->publicKeyCredentialId);
    }

    public function testMfaWebauthnCredentialsTable_Rule_UniqueCredentialId(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('shared-id')->persist();

        $entity = $this->Table->newEntity(
            [
                'id' => UuidFactory::uuid(),
                'user_id' => $user->id,
                'type' => 'mfa',
                'credential_id' => 'shared-id',
                'data' => MfaWebauthnCredentialFactory::makeCredentialRecord('shared-id'),
                'sign_count' => 0,
                'name' => 'Dup',
            ],
            ['accessibleFields' => [
                'id' => true, 'user_id' => true, 'type' => true, 'credential_id' => true,
                'data' => true, 'sign_count' => true, 'name' => true,
            ]],
        );
        $this->assertFalse($this->Table->save($entity));
        $this->assertArrayHasKey('unique_credential_id', $entity->getError('credential_id'));
    }

    public function testMfaWebauthnCredentialsTable_Rule_UniqueUserAndName(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->name('Yubikey Nano')->persist();

        $entity = $this->Table->newEntity(
            [
                'id' => UuidFactory::uuid(),
                'user_id' => $user->id,
                'type' => 'mfa',
                'credential_id' => 'other-id',
                'data' => MfaWebauthnCredentialFactory::makeCredentialRecord('other-id'),
                'sign_count' => 0,
                'name' => 'Yubikey Nano',
            ],
            ['accessibleFields' => [
                'id' => true, 'user_id' => true, 'type' => true, 'credential_id' => true,
                'data' => true, 'sign_count' => true, 'name' => true,
            ]],
        );
        $this->assertFalse($this->Table->save($entity));
        $this->assertArrayHasKey('unique_user_name', $entity->getError('user_id'));
    }

    public function testMfaWebauthnCredentialsTable_Rule_AllowsSameNameForDifferentUsers(): void
    {
        /** @var \App\Model\Entity\User $userA */
        $userA = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $userB */
        $userB = UserFactory::make()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($userA)->name('Yubikey Nano')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($userB)->name('Yubikey Nano')->persist();

        $this->assertSame(1, $this->Table->countForUser($userA->id));
        $this->assertSame(1, $this->Table->countForUser($userB->id));
    }

    public function testMfaWebauthnCredentialsTable_Rule_AllowsNullNameCollision(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->name('')->patchData(['name' => null])->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->name('')->patchData(['name' => null])->persist();

        $this->assertSame(2, $this->Table->countForUser($user->id));
    }

    public function testMfaWebauthnCredentialsTable_UpdateAfterAssertion_ReplayWithSameCounter_IsRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->signCount(7)->persist();

        $fresh = $this->recordWithCounter($credential->credential_id, 8);
        $this->assertTrue($this->Table->updateAfterAssertion($credential, $fresh));

        $replay = $this->recordWithCounter($credential->credential_id, 8);
        $this->assertFalse($this->Table->updateAfterAssertion($credential, $replay));
        $this->assertSame(8, $this->Table->get($credential->id)->sign_count);
    }

    public function testMfaWebauthnCredentialsTable_UpdateAfterAssertion_LowerCounter_IsRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->signCount(50)->persist();

        $regressed = $this->recordWithCounter($credential->credential_id, 49);
        $this->assertFalse($this->Table->updateAfterAssertion($credential, $regressed));
        $this->assertSame(50, $this->Table->get($credential->id)->sign_count);
    }

    public function testMfaWebauthnCredentialsTable_UpdateAfterAssertion_ZeroCounterCounterlessAuthenticator_IsAccepted(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->signCount(0)->persist();

        $zero = $this->recordWithCounter($credential->credential_id, 0);
        $this->assertTrue($this->Table->updateAfterAssertion($credential, $zero));
        $reloaded = $this->Table->get($credential->id);
        $this->assertSame(0, $reloaded->sign_count);
        $this->assertNotNull($reloaded->last_used);
    }

    public function testMfaWebauthnCredentialsTable_UpdateAfterAssertion_ZeroCounterAfterNonzero_IsRejected(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->signCount(7)->persist();

        $zero = $this->recordWithCounter($credential->credential_id, 0);
        $this->assertFalse($this->Table->updateAfterAssertion($credential, $zero));
    }

    public function testMfaWebauthnCredentialsTable_UpdateAfterAssertion_TwoWritesAgainstSameBaseline_OnlyOneWins(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()->withUser($user)->signCount(10)->persist();

        $first = $this->recordWithCounter($credential->credential_id, 11);
        $this->assertTrue($this->Table->updateAfterAssertion($credential, $first));

        $second = $this->recordWithCounter($credential->credential_id, 11);
        $this->assertFalse($this->Table->updateAfterAssertion($credential, $second));
    }

    public function testMfaWebauthnCredentialsTable_MassAssignment_IsBlockedForAuthoritativeFields(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $credential */
        $credential = MfaWebauthnCredentialFactory::make()
            ->withUser($user)
            ->credentialId('original')
            ->signCount(3)
            ->persist();

        $attackerPayload = [
            'user_id' => UuidFactory::uuid(),
            'credential_id' => 'attacker-forged',
            'sign_count' => 0,
            'data' => MfaWebauthnCredentialFactory::makeCredentialRecord('attacker-forged'),
        ];
        $patched = $this->Table->patchEntity($credential, $attackerPayload);
        $this->Table->save($patched);

        $reloaded = $this->Table->get($credential->id);
        $this->assertSame($user->id, $reloaded->user_id);
        $this->assertSame('original', $reloaded->credential_id);
        $this->assertSame(3, $reloaded->sign_count);
        $this->assertSame('original', $reloaded->data->publicKeyCredentialId);
    }

    /**
     * A `newEntity` insert whose `accessibleFields` whitelist deliberately omits `id`
     * must still reject the attacker-supplied `id` value.
     */
    public function testMfaWebauthnCredentialsTable_NewEntity_IgnoresNonAccessibleId(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->user()->persist();
        $forgedId = UuidFactory::uuid();

        $entity = $this->Table->newEntity(
            [
                'id' => $forgedId,
                'user_id' => $user->id,
                'type' => 'mfa',
                'credential_id' => 'legit',
                'data' => MfaWebauthnCredentialFactory::makeCredentialRecord('legit'),
                'sign_count' => 0,
            ],
            ['accessibleFields' => [
                'user_id' => true,
                'type' => true,
                'credential_id' => true,
                'data' => true,
                'sign_count' => true,
                'name' => true,
            ]],
        );
        $saved = $this->Table->save($entity);
        $this->assertNotFalse($saved);
        $this->assertNotSame($forgedId, $saved->id);
    }

    public function testMfaWebauthnCredentialsTable_DescriptorsForUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('a')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('b')->persist();

        $descriptors = $this->Table->descriptorsForUser($user->id);
        $this->assertCount(2, $descriptors);
        $ids = array_map(fn($d) => $d->id, $descriptors);
        $this->assertContains('a', $ids);
        $this->assertContains('b', $ids);
    }

    public function testMfaWebauthnCredentialsTable_DeleteOne_And_DeleteAllForUser(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        /** @var \App\Model\Entity\User $otherUser */
        $otherUser = UserFactory::make()->persist();
        /** @var \App\Model\Entity\WebauthnCredential $a */
        $a = MfaWebauthnCredentialFactory::make()->withUser($user)->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->persist();
        MfaWebauthnCredentialFactory::make()->withUser($otherUser)->persist();

        // deleteOne only removes rows owned by the caller.
        $this->assertFalse($this->Table->deleteOne($otherUser->id, $a->id));
        $this->assertTrue($this->Table->deleteOne($user->id, $a->id));
        $this->assertSame(1, $this->Table->countForUser($user->id));

        // deleteAllForUser leaves the other user's row untouched.
        $this->Table->deleteAllForUser($user->id);
        $this->assertSame(0, $this->Table->countForUser($user->id));
        $this->assertSame(1, $this->Table->countForUser($otherUser->id));
    }

    public function testMfaWebauthnCredentialsTable_BeforeFind_ScopesByType(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('mine')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('other')
            ->patchData(['type' => 'sso'])->persist();

        $this->assertSame(1, $this->Table->countForUser($user->id));
        $this->assertNull($this->Table->findOneByCredentialId('other'));
        $this->assertNotNull($this->Table->findOneByCredentialId('mine'));
    }

    public function testMfaWebauthnCredentialsTable_Validation_RejectsWrongType(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();

        $entity = $this->Table->newEntity(
            [
                'id' => UuidFactory::uuid(),
                'user_id' => $user->id,
                'type' => 'sso',
                'credential_id' => 'bad-type',
                'data' => MfaWebauthnCredentialFactory::makeCredentialRecord('bad-type'),
                'sign_count' => 0,
                'name' => 'X',
            ],
            ['accessibleFields' => [
                'id' => true, 'user_id' => true, 'type' => true, 'credential_id' => true,
                'data' => true, 'sign_count' => true, 'name' => true,
            ]],
        );
        $this->assertArrayHasKey('matchesProviderType', $entity->getError('type'));
    }

    public function testMfaWebauthnCredentialsTable_DeleteAllForUser_ScopesByType(): void
    {
        /** @var \App\Model\Entity\User $user */
        $user = UserFactory::make()->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('mfa-1')->persist();
        MfaWebauthnCredentialFactory::make()->withUser($user)->credentialId('sso-1')
            ->patchData(['type' => 'sso'])->persist();

        $this->Table->deleteAllForUser($user->id);

        $this->assertSame(0, MfaWebauthnCredentialFactory::find()
            ->where(['user_id' => $user->id, 'type' => 'mfa'])->count());
        $this->assertSame(1, MfaWebauthnCredentialFactory::find()
            ->where(['user_id' => $user->id, 'type' => 'sso'])->count());
    }
}
