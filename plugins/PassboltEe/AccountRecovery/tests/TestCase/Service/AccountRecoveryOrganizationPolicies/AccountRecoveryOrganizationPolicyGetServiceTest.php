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
 * @since         3.6.0
 */

namespace Passbolt\AccountRecovery\Test\TestCase\Service\AccountRecoveryOrganizationPolicies;

use App\Test\Factory\UserFactory;
use Cake\Chronos\Chronos;
use Cake\Http\ServerRequest;
use Cake\Validation\Validation;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryOrganizationPolicyGetServiceTest extends AccountRecoveryTestCase
{
    public $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = new AccountRecoveryOrganizationPolicyGetService();
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->service);
    }

    /**
     * Get a disabled policy is returned if there is no record found
     */
    public function testAccountRecoveryOrganizationPolicyGetService_EmptySuccess()
    {
        $policy = $this->service->get();
        $this->assertEquals(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED, $policy->policy);
        $this->assertEmpty($policy->id);
        $this->assertEmpty($policy->created);
        $this->assertEmpty($policy->modified);
        $this->assertEmpty($policy->created_by);
        $this->assertEmpty($policy->modified_by);
        $this->assertEmpty($policy->account_recovery_organization_public_key);
        $this->assertEmpty($policy->creator);
    }

    /**
     * Get a disabled policy is returned if there is no record found
     */
    public function testAccountRecoveryOrganizationPolicyGetService_DefaultSuccess()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $policy = $this->service->get();
        $this->assertTrue(in_array($policy->policy, AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES));
        $this->assertTrue(Validation::uuid($policy->id));
        $this->assertTrue(Validation::datetime($policy->created));
        $this->assertTrue(Validation::datetime($policy->modified));
        $this->assertTrue(Validation::uuid($policy->created_by));
        $this->assertTrue(Validation::uuid($policy->modified_by));
        $this->assertNull($policy['creator'] ?? null);
    }

    /**
     * Get a disabled policy is returned if there is a disabled policy set
     */
    public function testAccountRecoveryOrganizationPolicyGetService_Disabled()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->persist();

        $policy = $this->service->get();
        $this->assertNotEmpty($policy->id);
    }

    /**
     * Do not get fallback policy if policy is valid
     */
    public function testAccountRecoveryOrganizationPolicyGetService_MandatorySuccess()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        $policy = $this->service->get();
        $this->assertTrue(Validation::uuid($policy->id));
        $this->assertEquals($policy->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY);
    }

    /**
     * Get fallback policy if policy is soft deleted
     */
    public function testAccountRecoveryOrganizationPolicyGetService_SoftDeletedFallback()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->setField('deleted', Chronos::now()->subDays(1))
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
                'deleted' => Chronos::now()->subDays(1),
            ]))
            ->persist();

        $policy = $this->service->get();
        $this->assertEmpty($policy->id);
        $this->assertEquals($policy->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED);
    }

    /**
     * Get fallback policy if policy has a soft deleted key
     */
    public function testAccountRecoveryOrganizationPolicyGetService_SoftDeletedKeyFallback()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
                'deleted' => Chronos::now()->subDays(1),
            ]))
            ->persist();

        $policy = $this->service->get();
        $this->assertEmpty($policy->id);
        $this->assertEquals($policy->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED);
    }

    /**
     * Contain the creator if in the query
     */
    public function testAccountRecoveryOrganizationPolicyGetService_ContainCreator()
    {
        $creator = AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->with('Creator', UserFactory::make()->withAvatar())
            ->persist()
            ->creator;

        $service = new AccountRecoveryOrganizationPolicyGetService(new ServerRequest([
            'url' => '/account-recovery/organization-policies.json?contain[creator]=1',
        ]));
        $policy = $service->get();
        $this->assertTrue(in_array($policy->policy, AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES));
        $this->assertTrue(Validation::uuid($policy->id));
        $this->assertTrue(Validation::datetime($policy->created));
        $this->assertTrue(Validation::datetime($policy->modified));
        $this->assertTrue(Validation::uuid($policy->created_by));
        $this->assertTrue(Validation::uuid($policy->modified_by));

        $this->assertSame($creator->username, $policy->creator->username);
        $this->assertFalse($creator->deleted);
        $this->assertSame($creator->profile->first_name, $policy->creator->profile->first_name);
        $this->assertSame($creator->profile->avatar->url, $policy->creator->profile->avatar->url);
    }

    /**
     * Contain the creator if in the query, even if it is deleted
     */
    public function testAccountRecoveryOrganizationPolicyGetService_ContainDeletedCreator()
    {
        $creator = AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->with('Creator', UserFactory::make()->deleted()->withAvatar())
            ->persist()
            ->creator;

        $service = new AccountRecoveryOrganizationPolicyGetService(new ServerRequest([
            'url' => '/account-recovery/organization-policies.json?contain[creator]=1',
        ]));
        $policy = $service->get();
        $this->assertTrue(in_array($policy->policy, AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES));
        $this->assertTrue(Validation::uuid($policy->id));
        $this->assertTrue(Validation::datetime($policy->created));
        $this->assertTrue(Validation::datetime($policy->modified));
        $this->assertTrue(Validation::uuid($policy->created_by));
        $this->assertTrue(Validation::uuid($policy->modified_by));

        $this->assertSame($creator->username, $policy->creator->username);
        $this->assertTrue($creator->deleted);
        $this->assertSame($creator->profile->first_name, $policy->creator->profile->first_name);
        $this->assertSame($creator->profile->avatar->url, $policy->creator->profile->avatar->url);
    }

    /**
     * Contain the creator and gpgkey if in the query
     */
    public function testAccountRecoveryOrganizationPolicyGetService_ContainCreatorAndGpgkey()
    {
        $creator = AccountRecoveryOrganizationPolicyFactory::make()
            ->withAccountRecoveryOrganizationPublicKey()
            ->with('Creator', UserFactory::make()->with('Gpgkeys')->withAvatar())
            ->persist()
            ->creator;

        $service = new AccountRecoveryOrganizationPolicyGetService(new ServerRequest([
            'url' => '/account-recovery/organization-policies.json?contain[creator.gpgkey]=1',
        ]));
        $policy = $service->get();
        $this->assertTrue(in_array($policy->policy, AccountRecoveryOrganizationPolicy::SUPPORTED_POLICIES));
        $this->assertTrue(Validation::uuid($policy->id));
        $this->assertTrue(Validation::datetime($policy->created));
        $this->assertTrue(Validation::datetime($policy->modified));
        $this->assertTrue(Validation::uuid($policy->created_by));
        $this->assertTrue(Validation::uuid($policy->modified_by));

        $this->assertSame($creator->username, $policy->creator->username);
        $this->assertFalse($creator->deleted);
        $this->assertSame($creator->profile->first_name, $policy->creator->profile->first_name);
        $this->assertSame($creator->profile->avatar->url, $policy->creator->profile->avatar->url);
        $this->assertSame($creator->gpgkey->fingerprint, $policy->creator->gpgkey->fingerprint);
        $this->assertSame($creator->gpgkey->armored_key, $policy->creator->gpgkey->armored_key);
    }
}
