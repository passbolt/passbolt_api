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
use App\Utility\UserAccessControl;
use Cake\Event\Event;
use Cake\Http\Exception\BadRequestException;
use Cake\Utility\Hash;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey;
use Passbolt\AccountRecovery\Service\AccountRecoveryPrivateKeyPasswords\AccountRecoveryPrivateKeyPasswordsValidationService; // phpcs:ignore
use Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings\AccountRecoveryUserSettingsDeleteService;

class AccountRecoveryOrganizationPolicySetService extends AbstractAccountRecoveryOrganizationPolicySetService implements AccountRecoveryOrganizationPolicySetServiceInterface // phpcs:ignore
{
    /**
     * Set - create new policy and delete the old one
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param array $data user provided data
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function set(UserAccessControl $uac, array $data): AccountRecoveryOrganizationPolicy
    {
        $this->setData($data);

        // assert policy is provided as it should in any case
        $newPolicy = $this->buildAndValidatePolicyEntityFromData($uac);

        // Check request composition to understand user goal
        $isPolicyChange = $this->isPolicyChange();
        $isNewKeyProvided = $this->isPublicKeyProvided();
        $isRevokedKeyProvided = $this->isRevokedKeyProvided();
        $isPrivateKeyPasswordsProvided = $this->isPrivateKeyPasswordsProvided();

        // if policy has not changed and (new key not provided or revoked key not provided)
        if (!$isPolicyChange && !$isNewKeyProvided && !$isRevokedKeyProvided) {
            throw new BadRequestException(__('Invalid request. No policy change.'));
        }
        if (!$isPolicyChange && $isNewKeyProvided && !$isRevokedKeyProvided) {
            throw new BadRequestException(__('Invalid request. Revoked key is required for key rotation.'));
        }
        /** @psalm-suppress RedundantCondition */
        if (!$isPolicyChange && !$isNewKeyProvided && $isRevokedKeyProvided) {
            throw new BadRequestException(__('Invalid request. New key is required for key rotation.'));
        }

        // if disabled => enabled
        if ($this->isEnabling()) {
            // if public key is not provided
            if (!$isNewKeyProvided) {
                throw new BadRequestException(__('Invalid request. An organization recovery public key is required.'));
            }
            // if key revocation or passwords provided
            if ($isRevokedKeyProvided || $isPrivateKeyPasswordsProvided) {
                throw new BadRequestException(__('Invalid request. Revoked key or passwords are not required.'));
            }

            return $this->enablePolicy($uac, $newPolicy);
        }

        // if enabled => disabled
        if ($this->isDisabling()) {
            // if new key or passwords provided
            if ($isNewKeyProvided || $isPrivateKeyPasswordsProvided) {
                throw new BadRequestException(__('Invalid request. New key or passwords are not required.'));
            }

            // save new disabled policy, disable previous key and delete backups if any
            return $this->disablePolicy($uac);
        }

        // if enabled => enabled
        // e.g it's policy change like mandatory => opt-in
        // and/or a possible key rotation
        if (($isNewKeyProvided && !$isRevokedKeyProvided) || (!$isNewKeyProvided && $isRevokedKeyProvided)) {
            throw new BadRequestException(__('Invalid request. Keys are required for this change.'));
        }

        // if key provided or revocation provided
        $newKey = null;
        $oldKey = null;
        $passwords = null;
        /** @psalm-suppress RedundantCondition */
        if ($isNewKeyProvided && $isRevokedKeyProvided) {
            // assert old and new key$newKey
            $newKey = $this->buildPublicKeyEntityFromDataOrFail($uac);
            $oldKey = $this->buildRevokedKeyEntityFromDataOrFail($uac);

            // If some existing backups are present
            // assert new backups are provided
            if ($this->backupsExists()) {
                if (!$isPrivateKeyPasswordsProvided) {
                    throw new BadRequestException(__('Invalid request. Passwords are required for this change.'));
                }
                // assert passwords backups format and numbers
                $passwords = $this->buildPasswordEntitiesFromDataOrFail($uac, $newKey);
            }
            $newPolicy->account_recovery_organization_public_key = $newKey;
        } else {
            // If key is not changing reuse the old one
            if (!isset($newPolicy->public_key_id)) {
                throw new CustomValidationException(__('Could not validate public key data.'), [
                    'public_key_id' => [
                        '_required' => __('An organization public key is required.'),
                    ],
                ]);
            } else {
                if ($newPolicy->public_key_id !== $this->getCurrentPolicyEntity()->public_key_id) {
                    throw new CustomValidationException(__('Could not validate public key data.'), [
                        'public_key_id' => [
                            'notCurrentPublicKeyId' => __('The public_key_id must match current policy public_key_id.'),
                        ],
                    ]);
                }
            }
        }

        // save new key and disable previous key and backups if any
        return $this->updatePolicy($uac, $newPolicy, $oldKey, $newKey, $passwords);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user acccess control
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $newPolicy new policy
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey|null $oldKey if key rotation
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey|null $newKey if key rotation
     * @param iterable|null $passwords if key rotation and backups exist
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    public function updatePolicy(
        UserAccessControl $uac,
        AccountRecoveryOrganizationPolicy $newPolicy,
        ?AccountRecoveryOrganizationPublicKey $oldKey = null,
        ?AccountRecoveryOrganizationPublicKey $newKey = null,
        ?iterable $passwords = null
    ): AccountRecoveryOrganizationPolicy {
        $oldPolicy = $this->getCurrentPolicyEntity();
        $this->AccountRecoveryOrganizationPolicies->getConnection()->transactional(
            function () use (&$newPolicy, $oldKey, $newKey, $passwords, $uac) {
                $saveOptions = ['atomic' => false];

                $newPolicy = $this->AccountRecoveryOrganizationPolicies->replace($uac, $newPolicy);

                if (isset($newKey)) {
                    // Needs to happen after replace or current policy will become invalid during replace
                    $this->AccountRecoveryOrganizationPublicKeys->saveOrFail($oldKey, $saveOptions);
                }

                if (isset($passwords)) {
                    $this->assertPasswordsCount();
                    $this->AccountRecoveryPrivateKeyPasswords->truncate();
                    $this->AccountRecoveryPrivateKeyPasswords->saveManyOrFail($passwords, $saveOptions);
                }
            }
        );

        // Cleanup user settings with rejected status since it's not a valid option anymore
        if (!$oldPolicy->isDisabled() && $newPolicy->isMandatory()) {
            (new AccountRecoveryUserSettingsDeleteService())->deleteAllRejected();
        }

        $event = new Event(self::AFTER_UPDATE_POLICY_EVENT, $this, ['uac' => $uac, 'policy' => $newPolicy]);
        $this->AccountRecoveryOrganizationPolicies->getEventManager()->dispatch($event);

        return $this->getCurrentPolicyEntity(false);
    }

    /**
     * Save the new policy and trigger email notification
     *
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy $policy entity
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     * @throws \Exception If policy could not be saved
     */
    protected function enablePolicy(UserAccessControl $uac, AccountRecoveryOrganizationPolicy $policy): AccountRecoveryOrganizationPolicy // phpcs:ignore
    {
        $key = $this->buildPublicKeyEntityFromDataOrFail($uac);
        $policy->account_recovery_organization_public_key = $key;
        $policy = $this->AccountRecoveryOrganizationPolicies->replace($uac, $policy);

        // Trigger event for email notifications and co.
        $event = new Event(self::AFTER_ENABLE_POLICY_EVENT, $this, compact('policy', 'uac'));
        $this->AccountRecoveryOrganizationPolicies->getEventManager()->dispatch($event);

        return $this->getCurrentPolicyEntity(false);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy entity
     * @throws \Exception Will re-throw any exception raised in $callback after rolling back the transaction.
     */
    public function disablePolicy(UserAccessControl $uac): AccountRecoveryOrganizationPolicy
    {
        $newPolicy = $this->AccountRecoveryOrganizationPolicies->newEntityForDisable($uac);
        $oldKey = $this->buildRevokedKeyEntityFromDataOrFail($uac);

        $this->AccountRecoveryOrganizationPolicies->getConnection()->transactional(
            function () use ($newPolicy, $oldKey, $uac) {
                // Create new policy and delete old one
                $this->AccountRecoveryOrganizationPolicies->replace($uac, $newPolicy);

                // Set previous key as deleted
                $this->AccountRecoveryOrganizationPublicKeys->saveOrFail($oldKey, ['atomic' => false]);

                // Truncate private key, passwords and user settings
                $this->AccountRecoveryPrivateKeys->truncate();
                $this->AccountRecoveryPrivateKeyPasswords->truncate();
                $this->AccountRecoveryUserSettings->truncate();

                // Cancel pending or non completed requests
                $this->AccountRecoveryRequests->rejectAllNonCompleted($uac);
            }
        );

        // Trigger event for email notifications and co.
        $event = new Event(self::AFTER_DISABLE_POLICY_EVENT, $this, ['uac' => $uac, 'policy' => $newPolicy]);
        $this->AccountRecoveryOrganizationPolicies->getEventManager()->dispatch($event);

        return $this->getCurrentPolicyEntity(false);
    }

    /**
     * @param \App\Utility\UserAccessControl $uac user access control
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPublicKey $publicKey entity
     * @return iterable array of AccountRecoveryPrivateKeyPasswords
     */
    public function buildPasswordEntitiesFromDataOrFail(
        UserAccessControl $uac,
        AccountRecoveryOrganizationPublicKey $publicKey
    ): iterable {
        $passwordsData = $this->getData('account_recovery_private_key_passwords') ?? [];
        $service = new AccountRecoveryPrivateKeyPasswordsValidationService();
        $key = $publicKey->armored_key;
        $rules = 'rotateKeys';

        // validate without business rules since new key is not saved yet
        return $service->buildPasswordEntitiesFromDataOrFail($uac, $passwordsData, $key, $rules);
    }

    /**
     * Ensure the correct number of passwords are provided by the end user data
     *
     * Should be done in the same transaction than the save for data integrity purpose
     * This assume the account_recovery_private_key_passwords is therefore validated
     * It uses the original data for array operation speed versus working with entities
     *
     * @CustomValidationException if some of the private key passwords are missing
     * @return void
     */
    private function assertPasswordsCount()
    {
        $passwordsData = $this->getData('account_recovery_private_key_passwords');

        // Check there is the correct number of passwords
        $actual = count($passwordsData);
        $expected = $this->AccountRecoveryPrivateKeyPasswords->find()->count();
        if ($actual !== $expected) {
            $msg = __('An invalid number of passwords sent. Expected {0} and got {1}.', $expected, $actual);
            throw new CustomValidationException(__('Could not validate password data.'), [
                'account_recovery_private_key_passwords' => [
                    'invalidPasswordCount' => $msg,
                ],
            ]);
        }

        // Check there is the correct private key id for the passwords
        $missing = $this->AccountRecoveryPrivateKeys->find()
            ->select('id')
            ->where(['id NOT IN' => Hash::extract($passwordsData, '{n}.private_key_id')])
            ->all();
        if (count($missing)) {
            throw new CustomValidationException(__('Could not validate password data.'), [
                'account_recovery_private_key_passwords' => [
                    'missingPasswordForPrivateKeyIds' => Hash::extract($missing->toArray(), '{n}.id'),
                ],
            ]);
        }
    }
}
