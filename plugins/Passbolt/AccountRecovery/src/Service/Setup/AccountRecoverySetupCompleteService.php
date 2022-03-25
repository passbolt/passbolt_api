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

namespace Passbolt\AccountRecovery\Service\Setup;

use App\Error\Exception\CustomValidationException;
use App\Error\Exception\ValidationException;
use App\Model\Entity\User;
use App\Service\OpenPGP\MessageValidationService;
use App\Service\Setup\SetupCompleteService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
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
class AccountRecoverySetupCompleteService extends SetupCompleteService
{
    /**
     * @var \Passbolt\AccountRecovery\Model\Table\AccountRecoveryUserSettingsTable
     */
    public $AccountRecoveryUserSettings;

    /**
     * @var \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy entity
     */
    public $policy;

    /**
     * @var array user provided data
     */
    public $data;

    /**
     * @var \App\Utility\UserAccessControl $uac current user
     */
    public $uac;

    /**
     * @param \Cake\Http\ServerRequest|null $request Server request
     */
    public function __construct(?ServerRequest $request = null)
    {
        parent::__construct($request);
        $this->loadModel('Passbolt/AccountRecovery.AccountRecoveryUserSettings');
        $this->loadModel(AccountRecoveryPrivateKeysTable::class);
        $this->loadModel(AccountRecoveryPrivateKeyPasswordsTable::class);

        $service = new AccountRecoveryOrganizationPolicyGetService();
        $this->policy = $service->get();
    }

    /**
     * Setup completion
     * Save the user gpg public key and set the account to active
     *
     * @throws \Cake\Http\Exception\BadRequestException invalid request
     * @throws \Cake\Http\Exception\InternalErrorException if something went wrong when updating the data
     * @param string $userId uuid of the user
     * @param array|null $saveOptions options
     * @return \App\Model\Entity\User
     */
    public function complete(string $userId, ?array $saveOptions = []): User
    {
        $this->assertRequestSanity();
        $user = $this->buildUserEntity($userId); // checks token and user
        $this->uac = new UserAccessControl($user->role->name, $user->id);

        // Validate additional settings
        if ($this->isAccountRecoveryUserSettingProvided()) {
            $userSetting = $this->validateAccountRecoveryUserSetting();
            $user->set('account_recovery_user_setting', $userSetting);
            $this->assertRules($userSetting);
            if ($userSetting->isApproved()) {
                $key = $this->validateAccountRecoveryPrivateKey();
                $passwords = $this->buildPasswordEntitiesFromDataOrFail();
                $key->set('account_recovery_private_key_passwords', $passwords);
                $user->set('account_recovery_private_key', $key);
            }
        }

        return $this->saveUserEntity($user, $saveOptions);
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
        if ($this->policy->isMandatory() && !$setting->isApproved()) {
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
     * Assert that there is not too much or not enough data
     * Mandatory: both private key and password must be provided
     * Disabled: none of them must be provided
     *
     * @return void
     * @throws \Cake\Http\Exception\BadRequestException if data is missing or too much data is sent
     */
    protected function assertRequestSanity(): void
    {
        if ($this->policy->isDisabled()) {
            if ($this->isAccountRecoveryUserSettingProvided()) {
                throw new BadRequestException(__('Account recovery is disabled. Key backup is not supported.'));
            }
        } elseif ($this->policy->isMandatory()) {
            if (!$this->isPrivateKeyProvided() || !$this->arePasswordsProvided()) {
                throw new BadRequestException(
                    __('Account recovery is mandatory. Please provide the mandatory data.')
                );
            }
        }
    }

    /**
     * @return bool true if the account_recovery_organization_public_key data is set
     */
    protected function isAccountRecoveryUserSettingProvided(): bool
    {
        $publicKey = $this->request->getData('account_recovery_user_setting');

        return isset($publicKey) && is_array($publicKey);
    }

    /**
     * @return bool true if the account_recovery_organization_public_key data is set
     */
    protected function isPrivateKeyProvided(): bool
    {
        $publicKey = $this->request->getData('account_recovery_user_setting.account_recovery_private_key');

        return isset($publicKey) && is_array($publicKey);
    }

    /**
     * @return bool true if the account_recovery_organization_public_key data is set
     */
    protected function arePasswordsProvided(): bool
    {
        $publicKey = $this->request
            ->getData('account_recovery_user_setting.account_recovery_private_key_passwords');

        return isset($publicKey) && is_array($publicKey);
    }

    /**
     * Adds post-save validation on account recovery related data, in case the saving failed.
     *
     * @param \App\Model\Entity\User $user User entity
     * @param array|null $saveOptions options
     * @return \App\Model\Entity\User
     */
    protected function saveUserEntity(User $user, ?array $saveOptions = []): User
    {
        $user = parent::saveUserEntity($user, $saveOptions);

        if ($this->isAccountRecoveryUserSettingProvided()) {
            if ($user->get('account_recovery_user_setting')->hasErrors()) {
                throw new ValidationException(
                    'Could not save the account recovery setting.',
                    $user->get('account_recovery_user_setting'),
                    $this->AccountRecoveryUserSettings
                );
            }

            if ($user->has('account_recovery_private_key') && $user->get('account_recovery_private_key')->hasErrors()) {
                throw new ValidationException(
                    'Could not save the account recovery private key.',
                    $user->get('account_recovery_private_key'),
                    $this->AccountRecoveryPrivateKeys
                );
            }
        }
        return $user;
    }

    /**
     * @throws \App\Error\Exception\CustomValidationException if the settings does not validate
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    protected function validateAccountRecoveryUserSetting(): AccountRecoveryUserSetting
    {
        $status = $this->request->getData('account_recovery_user_setting.status') ?? '';
        try {
            return $this->AccountRecoveryUserSettings->buildAndValidateEntity($this->uac, $status);
        } catch (ValidationException $exception) {
            throw new CustomValidationException($exception->getMessage(), [
                'account_recovery_user_setting' => $exception->getErrors(),
            ]);
        }
    }

    /**
     * @return iterable array of AccountRecoveryPrivateKeyPasswords
     */
    public function buildPasswordEntitiesFromDataOrFail(): iterable
    {
        $passwordsData = $this->request
                ->getData('account_recovery_user_setting.account_recovery_private_key_passwords') ?? [];

        try {
            $service = new AccountRecoveryPrivateKeyPasswordsValidationService();
            $publicKey = $this->policy->account_recovery_organization_public_key->armored_key;

            return $service->buildPasswordEntitiesFromDataOrFail($this->uac, $passwordsData, $publicKey);
        } catch (CustomValidationException $exception) {
            // re-wrap errors under parent object
            throw new CustomValidationException($exception->getMessage(), [
                'account_recovery_user_setting' => $exception->getErrors(),
            ]);
        }
    }

    /**
     * @throws \App\Error\Exception\CustomValidationException if the private key does not validate
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey
     */
    protected function validateAccountRecoveryPrivateKey(): AccountRecoveryPrivateKey
    {
        $data = $this->request
            ->getData('account_recovery_user_setting.account_recovery_private_key') ?? [];

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
}
