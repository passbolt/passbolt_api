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

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Test\Factory\GpgkeyFactory;
use App\Test\Factory\UserFactory;
use App\Utility\OpenPGP\OpenPGPBackendFactory;
use App\Utility\UserAccessControl;
use App\Utility\UuidFactory;
use Cake\Chronos\Chronos;
use Cake\Core\Configure;
use Cake\Http\Exception\BadRequestException;
use Cake\ORM\TableRegistry;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicySetService;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPolicyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryOrganizationPublicKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryPrivateKeyPasswordFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryRequestFactory;
use Passbolt\AccountRecovery\Test\Factory\AccountRecoveryUserSettingFactory;
use Passbolt\AccountRecovery\Test\Lib\AccountRecoveryTestCase;

class AccountRecoveryOrganizationPolicySetServiceTest extends AccountRecoveryTestCase
{
    /**
     * Return valid user access control for the service
     *
     * @return UserAccessControl
     * @throws \Exception
     */
    private function getUac(): UserAccessControl
    {
        return new UserAccessControl(Role::ADMIN, UuidFactory::uuid('user.id.admin'));
    }

    // Invalid policy error scenarios

    /**
     * Service throws a validation error if not data is provided
     */
    public function testAccountRecoveryOrganizationPolicySetService_PolicyValidation_Error_Empty()
    {
        $cases = [[], ['policy' => null], ['policy' => '']];
        $service = new AccountRecoveryOrganizationPolicySetService();

        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
                $this->fail();
            } catch (ValidationException $exception) {
                $e = $exception->getErrors();
                $this->assertNotEmpty($e['policy']['_empty']);
            }
        }
    }

    /**
     * Service throws a validation error if invalid policy is provided
     */
    public function testAccountRecoveryOrganizationPolicySetService_PolicyValidation_Error_Inlist()
    {
        $cases = [['policy' => 'nope'], ['policy' => 'disabled️🔥']];
        $service = new AccountRecoveryOrganizationPolicySetService();
        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
                $this->fail();
            } catch (ValidationException $exception) {
                $e = $exception->getErrors();
                $this->assertNotEmpty($e['policy']['inList']);
            }
        }
    }

    // Bad request scenarios

    /**
     * Disabled (not set) => Disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange0()
    {
        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'disabled']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Disabled => Disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange1()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'disabled']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Optin => Optin
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange2()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'opt-in']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Opt-out => Opt-out
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange3()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->optout()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'opt-out']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Mandatory => Mandatory
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_NoChange4()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->mandatory()
            ->withAccountRecoveryOrganizationPublicKey()
            ->persist();

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), ['policy' => 'mandatory']);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('No policy change', $exception->getMessage());
        }
    }

    /**
     * Disabled => Enabled with wrong public key data
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyBadRequest_Format()
    {
        $cases = [[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => null,
        ],[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => '',
        ],[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => '🔥',
        ]];
        $service = new AccountRecoveryOrganizationPolicySetService();
        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
                $this->fail();
            } catch (BadRequestException $exception) {
                $this->assertTextContains('Invalid request', $exception->getMessage());
            }
        }
    }

    // Public key validation errors

    /**
     * Disabled => Enabled with required fingerprint and armored_key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Required()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['_required']);
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['_required']);
        }
    }

    /**
     * Disabled => Enabled with empty fingerprint and armored_key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Empty()
    {
        $cases = [[
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => null,
                'armored_key' => null,
            ],
        ], [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '',
                'armored_key' => '',
            ],
        ]];
        $service = new AccountRecoveryOrganizationPolicySetService();
        foreach ($cases as $case) {
            try {
                $service->set($this->getUac(), $case);
                $this->fail();
            } catch (CustomValidationException $exception) {
                $e = $exception->getErrors();
                $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['_empty']);
                $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['_empty']);
            }
        }
    }

    /**
     * Disabled => Enabled with required fingerprint and armored_key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Required2()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['_required']);
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['_required']);
        }
    }

    /**
     * Disabled => Enabled fingerprint
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Invalid()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '2D7CF2B7FD9643DEBF63633CF',
                    'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----',
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['invalidFingerprint']);
        }
    }

    /**
     * Disabled => Enabled invalid key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_Unparsable()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F',
                    'armored_key' => '-----BEGIN PGP PUBLIC KEY BLOCK-----

mQINBFXHTB8BEADAaRMUn++WVatrw3kQK7/6S6DvBauIYcBateuFjczhwEKXUD6T
hLm7nOv5/TKzCpnB5WkP+UZyfT/+jCC2x4+pSgog46jIOuigWBL6Y9F6KkedApFK
xnF6cydxsKxNf/V70Nwagh9ZD4W5ujy+RCB6wYVARDKOlYJnHKWqco7anGhWYj8K
KaDT+7yM7LGy+tCZ96HCw4AvcTb2nXF197Btu2RDWZ/0MhO+DFuLMITXbhxgQC/e
aA1CS6BNS7F91pty7s2hPQgYg3HUaDogTiIyth8R5Inn9DxlMs6WDXGc6IElSfhC
VvGT2KJDT85vR3oNbB0U5wlbKPa+bUl8CokEDjqrDmdZOOs/UO2mc45V3X5RNRtp
NZMBGPJsxOKQExEOZncOVsY7ZqLrecuR8UJBQnhPd1aoz3HCJppaPxL4Q==
=Kwft
-----END PGP PUBLIC KEY BLOCK-----',
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['invalidArmoredKey']);
        }
    }

    /**
     * Disabled => Enabled with non matching fingerprint
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_NoFingerprintMatch()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isMatchingKeyFingerprintRule']);
        }
    }

    /**
     * Disabled => Enabled with revoked key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_RevokedKey()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['isNotRevokedRule']);
        }
    }

    /**
     * Disabled => Enabled with key including revoked signature
     * (not actually revoked, only a 3rd party signature is revoked)
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Success_RevokedSigKey()
    {
        $service = new AccountRecoveryOrganizationPolicySetService();
        $service->set($this->getUac(), [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '170B1EF744092F6EBB0CDD517D1699F049F3E21B',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'revoked_sig_public.key'),
            ],
        ]);
        $this->assertTrue(true);
    }

    /**
     * Disabled => Enabled with rsa2048 key
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_WeakKey()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '26FD986838F4F9AB318FF56AE5DFCEE142949B78',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa2048_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['isValidKeySizeStrictRule']);
        }
    }

    /**
     * Disabled => Enabled with expired key / key with expiry date
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ExpiredKey()
    {
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => 'BD92B8DE3FCF8DD5D60A4DF91E5E3B142396F2C7',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_expired_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['isNotExpiredRule']);
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['armored_key']['hasNoExpiryDateRule']);
        }
    }

    /**
     * Disabled => Enabled with server key reuse
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ReuseServerKey()
    {
        $oldF = Configure::read('passbolt.gpg.serverKey.fingerprint');
        $oldP = Configure::read('passbolt.gpg.serverKey.public');

        Configure::write('passbolt.gpg.serverKey.fingerprint', '67BFFCB7B74AF4C85E81AB26508850525CD78BAA');
        Configure::write('passbolt.gpg.serverKey.armored_key', FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');

        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isNotServerKeyFingerprintRule']);
        }

        Configure::write('passbolt.gpg.serverKey.fingerprint', $oldF);
        Configure::write('passbolt.gpg.serverKey.fingerprint', $oldP);
    }

    /**
     * Disabled => Enabled with user key reuse
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ReuseUserKey()
    {
        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                    'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isNotUserKeyFingerprintRule']);
        }
    }

    /**
     * Disabled => Enabled with previous account recovery key reuse
     */
    public function testAccountRecoveryOrganizationPolicySetService_PublicKeyValidation_Error_ReusePreviousAccountRecoveryKey()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
                'deleted' => Chronos::now()->subDays(1),
            ]))
            ->persist();

        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), [
                'policy' => 'opt-in',
                'account_recovery_organization_public_key' => [
                    'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                    'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                ],
            ]);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertNotEmpty($e['account_recovery_organization_public_key']['fingerprint']['isNotAccountRecoveryOrganizationPublicKeyFingerprintRule']);
        }
    }

    // DISABLED => ENABLED
    // SUCCESS SCENARIOS

    /**
     * Disabled => Enabled (Opt-in), first policy ever
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_DisabledEnabled()
    {
        $service = new AccountRecoveryOrganizationPolicySetService();
        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            ],
        ];
        $policy = $service->set($this->getUac(), $data);

        $this->assertNotEmpty($policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($this->getUac()->getId(), $policy->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->modified_by);

        $this->assertNotEmpty($policy->account_recovery_organization_public_key);
        $this->assertEquals($data['account_recovery_organization_public_key']['fingerprint'], $policy->account_recovery_organization_public_key->fingerprint);
        $this->assertEquals($data['account_recovery_organization_public_key']['armored_key'], $policy->account_recovery_organization_public_key->armored_key);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->modified_by);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->created);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->modified);
        $this->assertNull($policy->account_recovery_organization_public_key->deleted);

        // Check data integrity
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $foundPolicies = $table->find()->all();
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys');
        $foundKeys = $table->find()->all();
        $this->assertEquals(1, count($foundPolicies));
        $this->assertEquals(1, count($foundKeys));
    }

    /**
     * Disabled => Enabled (Mandatory), previous policy set to disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_DisabledEnabled2()
    {
        AccountRecoveryOrganizationPolicyFactory::make()
            ->disabled()
            ->persist();

        $service = new AccountRecoveryOrganizationPolicySetService();
        $data = [
            'policy' => 'mandatory',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            ],
        ];
        $policy = $service->set($this->getUac(), $data);

        $this->assertEquals('mandatory', $policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($this->getUac()->getId(), $policy->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->modified_by);

        $this->assertNotEmpty($policy->account_recovery_organization_public_key);
        $this->assertEquals($data['account_recovery_organization_public_key']['fingerprint'], $policy->account_recovery_organization_public_key->fingerprint);
        $this->assertEquals($data['account_recovery_organization_public_key']['armored_key'], $policy->account_recovery_organization_public_key->armored_key);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->account_recovery_organization_public_key->modified_by);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->created);
        $this->assertNotEmpty($policy->account_recovery_organization_public_key->modified);
        $this->assertNull($policy->account_recovery_organization_public_key->deleted);

        // Check data integrity
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $this->assertEquals(1, $table->find()->where(['deleted IS' => null])->count());
        $this->assertEquals(1, $table->find()->where(['deleted IS NOT' => null])->count());

        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys');
        $this->assertEquals(1, $table->find()->all()->count());
    }

    // ENABLED => DISABLED
    // SUCCESS SCENARIOS

    /**
     * Enabled => Disabled, previous policy set to disabled
     */
    public function testAccountRecoveryOrganizationPolicySetService_Success_EnabledDisabled()
    {
        $this->startScenarioOptinWithBackupsAndRequests();

        $data = [
            'policy' => 'disabled',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        $policy = $service->set($this->getUac(), $data);

        $this->assertNotEmpty($policy);
        $this->assertEquals('disabled', $policy->policy);
        $this->assertNotEmpty($policy->created);
        $this->assertNotEmpty($policy->modified);
        $this->assertEquals($this->getUac()->getId(), $policy->created_by);
        $this->assertEquals($this->getUac()->getId(), $policy->modified_by);
        $this->assertEmpty($policy->account_recovery_organization_public_key);

        // There should be two policies and one deleted
        $this->assertSame(2, AccountRecoveryOrganizationPolicyFactory::count());
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $this->assertEquals(1, $table->find()->where(['deleted IS NOT' => null])->count());

        // There should be two public keys in DB both deleted
        $this->assertSame(2, AccountRecoveryOrganizationPublicKeyFactory::count());
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys');
        $this->assertEquals(2, $table->find()->where(['deleted IS NOT' => null])->count());

        // There should be one account recovery request as rejected
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryRequests');
        $this->assertEquals(1, $table->find()->where(['status' => 'rejected'])->count());
        $this->assertEquals(1, $table->find()->count());

        // Private key and passwords table and user settings should be truncated
        $this->assertSame(0, AccountRecoveryPrivateKeyFactory::count());
        $this->assertSame(0, AccountRecoveryPrivateKeyPasswordFactory::count());
        $this->assertSame(0, AccountRecoveryUserSettingFactory::count());
    }

    // ENABLED => ENABLED
    // SIMPLE SCENARIOS

    public function testAccountRecoveryOrganizationPolicySetService_Success_UpdateSimple()
    {
        $this->startScenarioOptinNoBackups();

        $service = new AccountRecoveryOrganizationPolicySetService();
        $policy = $service->set($this->getUac(), [
            'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY,
        ]);
        $this->assertEquals($policy->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY);

        sleep(1); // needed to avoid creation time to get mixed up because they become equal

        $policy = $service->set($this->getUac(), [
            'policy' => AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_OUT,
        ]);
        $this->assertEquals($policy->policy, AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_OUT);

        // There should be 3 policies, one not deleted
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        $this->assertEquals(3, $table->find()->count());
        $this->assertEquals(1, $table->find()->where(['deleted IS' => null])->count());

        // There should be one public key
        $table = TableRegistry::getTableLocator()->get('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys');
        $this->assertEquals(1, $table->find()->count());
        $this->assertEquals(1, $table->find()->where(['deleted IS' => null])->count());
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateSimple_InvalidPolicy()
    {
        $this->startScenarioOptinNoBackups();
        try {
            $service = new AccountRecoveryOrganizationPolicySetService();
            $service->set($this->getUac(), ['policy' => 'invalid']);
            $this->fail();
        } catch (ValidationException $exception) {
            $this->assertTrue(true);
        }
    }

    // SIMPLE ROTATION SCENARIOS

    public function testAccountRecoveryOrganizationPolicySetService_Success_UpdateWithKeyRotationSimple()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6 C30E 2413 24C9 0A44  A719 A86A 7EA3 7397 97F5',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'),
            ],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $policy = $service->set($this->getUac(), $data);
        } catch (ValidationException | CustomValidationException $exception) {
            $this->fail();
        }

        $this->assertEquals(AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_OPT_IN, $policy->policy);
        $this->assertEquals('23C6C30E241324C90A44A719A86A7EA3739797F5', $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_RevokedKeyMissing()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6C30E241324C90A44A719A86A7EA3739797F5',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'),
            ],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('Revoked key is required', $exception->getMessage());
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_RevokedKeyNotRevoked()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6C30E241324C90A44A719A86A7EA3739797F5',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key'),
            ],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $this->assertTextContains('Could not validate key revocation.', $exception->getMessage());
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_organization_revoked_key']['armored_key']['isRevokedRule']));
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_NewKeyReused()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . DS . 'Gpgkeys' . DS . 'ada_public.key'),
            ],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTextContains('Could not validate policy data.', $exception->getMessage());
            $this->assertTrue(isset($e['account_recovery_organization_public_key']['fingerprint']['isNotUserKeyFingerprintRule']));
        }
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithKeyRotationSimple_NewKeyMissing()
    {
        $this->startScenarioOptinNoBackups();

        $data = [
            'policy' => 'opt-in',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (BadRequestException $exception) {
            $this->assertTextContains('New key is required', $exception->getMessage());
        }
    }

    // FULL ROTATION SCENARIOS

    public function testAccountRecoveryOrganizationPolicySetService_Success_UpdateWithFullRotation()
    {
        $this->startScenarioOptinWithBackupsAndRequests();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23c6 c30e 2413 24c9 0a44  a719 a86a 7ea3 7397 97f5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BF FCB7 B74A F4C8 5E81  AB26 5088 5052 5CD7 8BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $policy = $service->set($this->getUac(), $data);
        } catch (CustomValidationException $exception) {
            $this->fail(json_encode($exception->getErrors()));
        }

        $this->assertEquals('opt-out', $policy->policy);
        $this->assertEquals('23C6C30E241324C90A44A719A86A7EA3739797F5', $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_PasswordsMissing()
    {
        $this->startScenarioOptinWithBackupsAndRequests();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.betty.id'), // betty not needed, ada missing
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords']['missingPasswordForPrivateKeyIds'][0]));
            $missingId = $e['account_recovery_private_key_passwords']['missingPasswordForPrivateKeyIds'][0];
            $this->assertEquals(UuidFactory::uuid('acr.private_key.ada.id'), $missingId);
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_PasswordsFormatInvalid_NotParsable()
    {
        $this->startScenarioOptinWithBackupsAndRequests();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6 C30E 2413 24C9 0A44  A719 A86A 7EA3 7397 97F5',
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => 'not a gpg message',
            ]],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $policy = $service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['data']['isValidOpenPGPMessage']));
        }

        // Check the policy has not changed
        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_PasswordsFormatInvalid_Symetric()
    {
        $this->startScenarioOptinWithBackupsAndRequests();

        $oldKeyRevokedArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $oldKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $oldKeyFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => $oldKeyFingerprint,
                'armored_key' => $oldKeyRevokedArmored,
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => file_get_contents(FIXTURES . DS . 'OpenPGP' . DS . 'Messages' . DS . 'symetric_secret_password.msg') ,
            ]],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['data']['hasAsymmetricPacketRule']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_RecipientInvalid()
    {
        $this->startScenarioOptinWithBackupsAndRequests();

        $oldKeyRevokedArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key');
        $oldKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key');
        $oldKeyFingerprint = '67BFFCB7B74AF4C85E81AB26508850525CD78BAA';

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        // Use old key instead of new one
        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($oldKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($oldKeyFingerprint);

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => $oldKeyFingerprint,
                'armored_key' => $oldKeyRevokedArmored,
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => $newKeyFingerprint,
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => $gpg->encrypt('cofveve'),
            ]],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords'][0]['wrongRecipient']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    public function testAccountRecoveryOrganizationPolicySetService_Error_UpdateWithFullRotation_RecipientTooMany()
    {
        $this->startScenarioOptinWithBackupsAndRequests();

        $newKeyArmored = file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_2_public.key');
        $newKeyFingerprint = '23C6C30E241324C90A44A719A86A7EA3739797F5';

        $gpg = OpenPGPBackendFactory::get();
        $gpg->importKeyIntoKeyring($newKeyArmored);
        $gpg->setEncryptKeyFromFingerprint($newKeyFingerprint);
        $data = $gpg->encrypt('cofveve');

        $data = [
            'policy' => 'opt-out',
            'account_recovery_organization_revoked_key' => [
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_revoked_public.key'),
            ],
            'account_recovery_organization_public_key' => [
                'fingerprint' => '23C6 C30E 2413 24C9 0A44  A719 A86A 7EA3 7397 97F5',
                'armored_key' => $newKeyArmored,
            ],
            'account_recovery_private_key_passwords' => [[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => $data,
            ],[
                'private_key_id' => UuidFactory::uuid('acr.private_key.ada.id'),
                'recipient_fingerprint' => $newKeyFingerprint,
                'recipient_foreign_model' => 'AccountRecoveryOrganizationKey',
                'data' => $data,
            ]],
        ];

        $service = new AccountRecoveryOrganizationPolicySetService();
        try {
            $service->set($this->getUac(), $data);
            $this->fail();
        } catch (CustomValidationException $exception) {
            $e = $exception->getErrors();
            $this->assertTrue(isset($e['account_recovery_private_key_passwords']['invalidPasswordCount']));
        }

        // Check the policy has not changed
        $policy = (new AccountRecoveryOrganizationPolicyGetService())->get();
        $this->assertEquals('opt-in', $policy->policy);
        $this->assertNotEquals($newKeyFingerprint, $policy->account_recovery_organization_public_key->fingerprint);
    }

    // SCENARIOS

    /**
     * Setup needed fixtures to have a basic scenario with
     *
     * @throws \Exception
     */
    private function startScenarioOptinWithBackupsAndRequests()
    {
        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'id' => UuidFactory::uuid('acr.org_public_key.id'),
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();

        AccountRecoveryOrganizationPublicKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.org_public_key_old.id'))
            ->setField('fingerprint', '2D7CF2B7FD9643DEBF63633CFC7F5D048541513F')
            ->setField('armored_key', file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'old_revoked_public.key'))
            ->setField('deleted', Chronos::now()->subDay())
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'approved')
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryUserSettingFactory::make()
            ->setField('status', 'rejected')
            ->setField('user_id', UuidFactory::uuid('user.id.betty'))
            ->persist();

        AccountRecoveryPrivateKeyFactory::make()
            ->setField('id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();

        AccountRecoveryPrivateKeyPasswordFactory::make()
            ->setField('private_key_id', UuidFactory::uuid('acr.private_key.ada.id'))
            ->setField('recipient_fingerprint', '03F60E958F4CB29723ACDF761353B5B15D9B054F')
            ->setField('recipient_foreign_model', 'AccountRecoveryOrganizationKey')
            ->persist();

        AccountRecoveryRequestFactory::make()
            ->setField('user_id', UuidFactory::uuid('user.id.ada'))
            ->persist();
    }

    /**
     * Setup needed fixtures to have a basic scenario with no passwords
     *
     * @throws \Exception
     */
    private function startScenarioOptinNoBackups()
    {
        UserFactory::make()
            ->user()
            ->with('Gpgkeys', GpgkeyFactory::make()->patchData([
                'fingerprint' => '03F60E958F4CB29723ACDF761353B5B15D9B054F',
                'armored_key' => file_get_contents(FIXTURES . 'Gpgkeys' . DS . 'ada_public.key'),
            ]))
            ->persist();

        AccountRecoveryOrganizationPolicyFactory::make()
            ->optin()
            ->with('AccountRecoveryOrganizationPublicKeys', AccountRecoveryOrganizationPublicKeyFactory::make()->patchData([
                'id' => UuidFactory::uuid('acr.org_public_key.id'),
                'fingerprint' => '67BFFCB7B74AF4C85E81AB26508850525CD78BAA',
                'armored_key' => file_get_contents(FIXTURES . 'OpenPGP' . DS . 'PublicKeys' . DS . 'rsa4096_public.key'),
                'deleted' => null,
            ]))
            ->persist();
    }
}
