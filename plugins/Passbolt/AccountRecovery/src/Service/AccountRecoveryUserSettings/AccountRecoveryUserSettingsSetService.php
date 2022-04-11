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

namespace Passbolt\AccountRecovery\Service\AccountRecoveryUserSettings;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Service\OpenPGP\MessageValidationService;
use App\Utility\UserAccessControl;
use Cake\Datasource\ModelAwareTrait;
use Cake\Http\Exception\BadRequestException;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;
use Passbolt\AccountRecovery\Service\AccountRecoveryPrivateKeyPasswords\AccountRecoveryPrivateKeyPasswordsValidationService; // phpcs:ignore

/**
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable $AccountRecoveryUserSettings
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable $AccountRecoveryPrivateKeys
 * @property \Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable $AccountRecoveryPrivateKeyPasswords
 */
class AccountRecoveryUserSettingsSetService
{
    use ModelAwareTrait;

    /**
     * @var \App\Utility\UserAccessControl
     */
    protected $uac;

    /**
     * @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy
     */
    protected $organizationPolicy;

    /**
     * @var array
     */
    protected $data;

    /**
     * @param \App\Utility\UserAccessControl $uac Logged in user
     */
    public function __construct(UserAccessControl $uac)
    {
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        $this->loadModel(AccountRecoveryPrivateKeysTable::class);
        $this->loadModel(AccountRecoveryPrivateKeyPasswordsTable::class);
        $this->uac = $uac;
    }

    /**
     * @param array $data Payload
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    public function set(array $data): AccountRecoveryUserSetting
    {
        // Ensure user can only enroll once
        // It's not possible for a user to enroll and de-enroll or enroll and re-enroll
        $currentSettings = (new AccountRecoveryUserSettingsGetService())->get($this->uac->getId());
        if (isset($currentSettings) && $currentSettings->isApproved()) {
            throw new BadRequestException(__('User account recovery settings cannot be edited.'));
        }

        $setting = $this->patchEntity($data);
        $this->AccountRecoveryUserSettings->saveOrFail($setting);

        return $setting;
    }

    /**
     * @param array $data Payload
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    public function patchEntity(array $data): AccountRecoveryUserSetting
    {
        $this->data = $data;
        $this->organizationPolicy = (new AccountRecoveryOrganizationPolicyGetService())->getOrFail();
        $status = $data['status'] ?? '';
        $setting = $this->validateAccountRecoveryUserSetting($status);
        $this->assertRules($setting);
        if ($setting->isApproved()) {
            $key = $this->validateAccountRecoveryPrivateKey();
            $passwords = $this->buildPasswordEntitiesFromDataOrFail();
            $key->set('account_recovery_private_key_passwords', $passwords);
            $setting->set('account_recovery_private_key', $key);
        }

        $this->validateStatusAgainstOrganizationPolicy($setting);

        return $setting;
    }

    /**
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting $setting Setting to validate
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if the status is rejected but the organisation setting to mandatory
     */
    protected function validateStatusAgainstOrganizationPolicy(AccountRecoveryUserSetting $setting): void
    {
        if ($this->organizationPolicy->isMandatory() && $setting->isRejected()) {
            throw new BadRequestException(__('The account recovery is mandatory and cannot be rejected.'));
        }
    }

    /**
     * @param string $status Status
     * @throws \App\Error\Exception\CustomValidationException if the settings does not validate
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    protected function validateAccountRecoveryUserSetting(string $status): AccountRecoveryUserSetting
    {
        try {
            return $this->AccountRecoveryUserSettings->buildAndValidateEntity($this->uac, $status);
        } catch (ValidationException $exception) {
            throw new CustomValidationException($exception->getMessage(), [
                'account_recovery_user_setting' => $exception->getErrors(),
            ]);
        }
    }

    /**
     * Check that the user selected setting makes sense as per select org policy
     *
     * @param \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting $setting entity
     * @throws \Cake\Http\Exception\BadRequestException if user rejects and policy is mandatory
     * @return void
     */
    protected function assertRules(AccountRecoveryUserSetting $setting): void
    {
        if ($this->organizationPolicy->isMandatory() && !$setting->isApproved()) {
            throw new CustomValidationException(__('Invalid request. You cannot opt-out.'), [
                'account_recovery_user_setting' => [
                    'status' => [
                        'isMandatoryRule' => __('The status must be set to approved.'),
                    ],
                ],
            ]);
        }

        if (!$setting->isApproved() && ($this->isPrivateKeyProvided() || $this->arePasswordsProvided())) {
            throw new CustomValidationException(__('Invalid request. You cannot both opt-out and provide backup.'), [
                'account_recovery_user_setting' => [
                    'status' => [
                        'isMatchingData' => __('The status must be set to approved.'),
                    ],
                ],
            ]);
        }

        if ($setting->isApproved() && (!$this->isPrivateKeyProvided() || !$this->arePasswordsProvided())) {
            $e = [];
            if (!$this->isPrivateKeyProvided()) {
                $e['account_recovery_user_setting']['account_recovery_private_key'] = [
                    '_required' => __('The private key backup must be provided.'),
                ];
            }
            if (!$this->arePasswordsProvided()) {
                $e['account_recovery_user_setting']['account_recovery_private_key_passwords'] = [
                    '_required' => __('The private key backup must be provided.'),
                ];
            }
            throw new CustomValidationException(__('Invalid request. Private key or password are missing.'), $e);
        }
    }

    /**
     * @throws \App\Error\Exception\CustomValidationException if the private key does not validate
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey
     */
    protected function validateAccountRecoveryPrivateKey(): AccountRecoveryPrivateKey
    {
        $data = $this->data['account_recovery_private_key'] ?? [];
        try {
            // Entity validation &
            $privateKeyEntity = $this->AccountRecoveryPrivateKeys->buildAndValidateEntity($this->uac, $data);

            // Validate private key OpenPGP message &
            $rules = MessageValidationService::getSymmetricMessageRules();
            MessageValidationService::parseAndValidateMessage($privateKeyEntity->data, $rules);

            // Validate business rules
            if (!$this->AccountRecoveryPrivateKeys->checkRules($privateKeyEntity)) {
                $errors = $privateKeyEntity->getErrors();
            }
        } catch (CustomValidationException | ValidationException $exception) {
            $errors = $exception->getErrors();
        }

        if (isset($errors) || !isset($privateKeyEntity)) {
            $msg = __('Could not validate private key data.');
            throw new CustomValidationException($msg, [
                'account_recovery_user_setting' => [
                    'account_recovery_private_key' => $errors ?? [],
                ],
            ]);
        }

        return $privateKeyEntity;
    }

    /**
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKeyPassword[] array of AccountRecoveryPrivateKeyPasswords
     */
    public function buildPasswordEntitiesFromDataOrFail(): array
    {
        $passwordsData = $this->data['account_recovery_private_key']['account_recovery_private_key_passwords'] ?? [];
        try {
            $service = new AccountRecoveryPrivateKeyPasswordsValidationService();
            $publicKey = $this->organizationPolicy->account_recovery_organization_public_key->armored_key;

            return $service->buildPasswordEntitiesFromDataOrFail($this->uac, $passwordsData, $publicKey);
        } catch (CustomValidationException $exception) {
            // re-wrap errors under parent object
            throw new CustomValidationException($exception->getMessage(), [
                'account_recovery_user_setting' => $exception->getErrors(),
            ]);
        }
    }

    /**
     * @return bool true if the account_recovery_private_key data is set
     */
    protected function isPrivateKeyProvided(): bool
    {
        return isset($this->data['account_recovery_private_key']);
    }

    /**
     * @return bool true if the account_recovery_private_key.account_recovery_private_key_passwords data is set
     */
    protected function arePasswordsProvided(): bool
    {
        return isset($this->data['account_recovery_private_key']['account_recovery_private_key_passwords']);
    }
}
