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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Service\OpenPGP\PublicKeyCanEncryptCheckService;
use App\Service\OpenPGP\PublicKeyRevocationCheckService;
use App\Service\OpenPGP\PublicKeyValidationService;
use App\Utility\UserAccessControl;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Utility\Hash;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey;

/**
 * AbstractAccountRecoveryOrganizationPolicySetService Class
 */
class AbstractAccountRecoveryOrganizationPolicySetService
{
    use LocatorAwareTrait;

    public const AFTER_ENABLE_POLICY_EVENT = 'accountRecovery.policy.enable';
    public const AFTER_DISABLE_POLICY_EVENT = 'accountRecovery.policy.disable';
    public const AFTER_UPDATE_POLICY_EVENT = 'accountRecovery.policy.update';

    /**
     * @var array $data user provider data
     */
    protected $data = [];

    /**
     * @var \Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetServiceInterface $getService
     */
    protected $getService;

    /**
     * @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy|null $currentPolicy
     */
    protected $currentPolicy = null;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPoliciesTable
     */
    protected $AccountRecoveryOrganizationPolicies;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryOrganizationPublicKeysTable
     */
    protected $AccountRecoveryOrganizationPublicKeys;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable
     */
    protected $AccountRecoveryPrivateKeys;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable
     */
    protected $AccountRecoveryPrivateKeyPasswords;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryRequestsTable
     */
    protected $AccountRecoveryRequests;

    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable
     */
    protected $AccountRecoveryUserSettings;

    /**
     * AbstractCompleteService constructor
     */
    public function __construct()
    {
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryOrganizationPolicies = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryOrganizationPolicies');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryOrganizationPublicKeys = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryOrganizationPublicKeys');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryPrivateKeys = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeys');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryPrivateKeyPasswords = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryPrivateKeyPasswords');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryRequests = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryRequests');
        /** @phpstan-ignore-next-line */
        $this->AccountRecoveryUserSettings = $this
            ->fetchTable('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        $this->getService = new AccountRecoveryOrganizationPolicyGetService();
    }

    // METHODS USED TO GET/SET USER REQUEST DATA

    /**
     * @param bool $useCache default true, will not re-fetch policy from DB
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    protected function getCurrentPolicyEntity(bool $useCache = true): AccountRecoveryOrganizationPolicy
    {
        if (!isset($this->currentPolicy) || !$useCache) {
            return $this->getService->get();
        }

        return $this->currentPolicy;
    }

    /**
     * @return string current policy name
     */
    protected function getCurrentPolicyName(): string
    {
        return $this->getCurrentPolicyEntity()->policy;
    }

    /**
     * @param array $data user provided data
     * @return void
     */
    protected function setData(array $data): void
    {
        $this->data = $data;
    }

    /**
     * Accessor for request data in a ServerRequest style
     *
     * @param string|null $name Dot separated name of the value to read. Or null to read all data.
     * @return mixed The value being read.
     */
    protected function getData(?string $name = null)
    {
        if ($name === null) {
            return $this->data;
        }

        return Hash::get($this->data, $name);
    }

    // METHODS USED TO UNDERSTAND USER REQUEST

    /**
     * @return bool true if policy from request is different than current one
     */
    public function isPolicyChange(): bool
    {
        return $this->getCurrentPolicyName() != $this->getData('policy');
    }

    /**
     * @return bool true if currently disabled and going towards enabled state
     */
    public function isEnabling(): bool
    {
        $currentlyDisabled = $this->getCurrentPolicyName() === AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED; // phpcs:ignore
        $goingToEnable = $this->getData('policy') !== AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED; // phpcs:ignore

        return $currentlyDisabled && $goingToEnable;
    }

    /**
     * @return bool true if currently enabled and going towards disabled state
     */
    public function isDisabling(): bool
    {
        $currentlyEnabled = $this->getCurrentPolicyName() !== AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED; // phpcs:ignore
        $goingToDisabled = $this->getData('policy') === AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED; // phpcs:ignore

        return $currentlyEnabled && $goingToDisabled;
    }

    /**
     * @return bool if the account_recovery_organization_public_key data is set return true
     */
    public function isPublicKeyProvided(): bool
    {
        $publicKey = $this->getData('account_recovery_organization_public_key');

        return isset($publicKey) && is_array($publicKey);
    }

    /**
     * @return bool if the account_recovery_organization_revoked_key data is set return true
     */
    public function isRevokedKeyProvided(): bool
    {
        $revokedKey = $this->getData('account_recovery_organization_revoked_key');

        return isset($revokedKey) && is_array($revokedKey);
    }

    /**
     * @return bool if the account_recovery_private_key_passwords data is set return true
     */
    public function isPrivateKeyPasswordsProvided(): bool
    {
        $passwords = $this->getData('account_recovery_private_key_passwords');

        return isset($passwords) && is_array($passwords);
    }

    // METHODS USED TO ASSERT REQUEST DATA AND BUILD ENTITIES

    /**
     * Assert if the policy in the provided data is correct
     * e.g. it is not empty, it is part of the supported policy, etc.
     * Return a valid AccountRecoveryOrganizationPolicy if correct
     *
     * @throw ValidationException if policy field does not validate
     * @param \App\Utility\UserAccessControl $uac The user at the origin of the operation
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    protected function buildAndValidatePolicyEntityFromData(UserAccessControl $uac): AccountRecoveryOrganizationPolicy
    {
        $policy = $this->getData('policy');
        if (!isset($policy) || !is_string($policy)) {
            $policy = '';
        }

        $publicKeyId = $this->getData('public_key_id');

        return $this->AccountRecoveryOrganizationPolicies->buildAndValidateEntity($uac, $policy, $publicKeyId);
    }

    /**
     * Assert public key data and build entity
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey
     */
    public function buildPublicKeyEntityFromDataOrFail(UserAccessControl $uac): AccountRecoveryOrganizationPublicKey
    {
        try {
            $data = $this->getData('account_recovery_organization_public_key');
            $entity = $this->AccountRecoveryOrganizationPublicKeys->buildAndValidateEntity($uac, $data);

            // Check key can be parsed
            PublicKeyValidationService::parseAndValidatePublicKey(
                $entity->armored_key,
                PublicKeyValidationService::getStrictRules()
            );

            // Prevent key reuse
            $this->assertPublicKeyModelRules($entity);

            // Make sure key can be used to encrypt - ref. PBL-07-002
            if (!PublicKeyCanEncryptCheckService::check($entity->armored_key, $entity->fingerprint)) {
                $msg = __('The OpenPGP key can not be used to encrypt.');
                throw new CustomValidationException($msg, ['armored_key' => ['canEncrypt' => $msg]]);
            }
        } catch (ValidationException | CustomValidationException $exception) {
            throw new CustomValidationException(__('Could not validate policy data.'), [
                'account_recovery_organization_public_key' => $exception->getErrors(),
            ]);
        } catch (\Exception $exception) {
            throw new CustomValidationException(__('Could not validate policy data.'), [
                'account_recovery_organization_public_key' => [
                    'armored_key' => [
                        'invalidArmoredKey' => $exception->getMessage(),
                    ],
                ],
            ]);
        }

        return $entity;
    }

    /**
     * Assert public key revocation
     * Check user provided valid valid account_recovery_organization_revoked_key
     * Return patched entity corresponding to the key to revoke (e.g. to update in DB)
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @throws \App\Error\Exception\CustomValidationException if any of the check on fingerprint or armored key data fails
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey currently in use key patched with new revoked armored_key
     */
    public function buildRevokedKeyEntityFromDataOrFail(UserAccessControl $uac): AccountRecoveryOrganizationPublicKey
    {
        try {
            $data = $this->getData('account_recovery_organization_revoked_key');
            $entity = $this->AccountRecoveryOrganizationPublicKeys->buildAndValidateEntity($uac, $data);
            $oldEntity = $this->findActiveKeyByFingerprintOrFail($entity->fingerprint);
            PublicKeyValidationService::parseAndValidatePublicKey(
                $entity->armored_key,
                PublicKeyValidationService::getRevokedKeyRules()
            );
        } catch (ValidationException | CustomValidationException $exception) {
            throw new CustomValidationException(__('Could not validate key revocation.'), [
                'account_recovery_organization_revoked_key' => $exception->getErrors(),
            ]);
        } catch (\Exception $exception) {
            throw new CustomValidationException(__('Could not validate key revocation.'), [
                'account_recovery_organization_revoked_key' => [
                    'armored_key' => [
                        'invalidArmoredKey' => $exception->getMessage(),
                    ],
                ],
            ]);
        }

        // Check revocation cryptographically
        // parseAndValidatePublicKey only do superficial signature check
        if (!(new PublicKeyRevocationCheckService())->check($entity->armored_key)) {
            throw new CustomValidationException(__('Could not validate key revocation.'), [
                'account_recovery_organization_revoked_key' => __('Could not validate key revocation.'),
            ]);
        }

        // Patch old key with new revoked value
        $patchedEntity = $this->AccountRecoveryOrganizationPublicKeys
            ->patchAndValidateEntityForRevocation($uac, $oldEntity, $data['armored_key']);
        if ($patchedEntity->getErrors()) {
            throw new CustomValidationException(__('Could not validate key revocation.'), [
                'account_recovery_organization_revoked_key' => $patchedEntity->getErrors(),
            ]);
        }

        return $patchedEntity;
    }

    /**
     * Run public key model rules and throw an exception in case of errors
     *
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $publicKey entity
     * @throws \App\Error\Exception\ValidationException if model rules fail
     * @return void
     */
    private function assertPublicKeyModelRules(AccountRecoveryOrganizationPublicKey $publicKey): void
    {
        $table = $this->AccountRecoveryOrganizationPublicKeys;
        if (!$table->checkRules($publicKey) && $publicKey->getErrors()) {
            throw new ValidationException(__('Could not validate public key data.'), $publicKey, $table);
        }
    }

    /**
     * Assert an organization recovery public key exists and is not deleted for given fingerprint
     *
     * @param string $fingerprint user provided data
     * @throws \App\Error\Exception\CustomValidationException if the provided fingerprint does not match the one
     * from the currently active key
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey existing key
     */
    private function findActiveKeyByFingerprintOrFail(string $fingerprint): AccountRecoveryOrganizationPublicKey
    {
        try {
            /** @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $key */
            $key = $this->AccountRecoveryOrganizationPublicKeys->find()->where([
                'fingerprint' => $fingerprint,
                'deleted IS' => null,
            ])->firstOrFail();
        } catch (RecordNotFoundException $exception) {
            throw new CustomValidationException(__('Could not validate policy data.'), [
                'fingerprint' => [
                    '_exists' => __('The fingerprint should match the one from the currently active key.'),
                ],
            ]);
        }

        return $key;
    }

    /**
     * @return bool
     */
    public function backupsExists(): bool
    {
        return $this->AccountRecoveryPrivateKeyPasswords->find()->count() > 0;
    }
}
