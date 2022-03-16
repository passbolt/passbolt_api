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

use App\Error\Exception\ValidationException;
use App\Model\Entity\Role;
use App\Model\Entity\User;
use App\Service\Setup\SetupCompleteService;
use App\Utility\UserAccessControl;
use Cake\Http\Exception\BadRequestException;
use Cake\Http\ServerRequest;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryOrganizationPolicy;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey;
use Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeyPasswordsTable;
use Passbolt\AccountRecovery\Model\Table\AccountRecoveryPrivateKeysTable;
use Passbolt\AccountRecovery\Service\AccountRecoveryOrganizationPolicies\AccountRecoveryOrganizationPolicyGetService;

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
     * @param \Cake\Http\ServerRequest $request Server request
     */
    public function __construct(ServerRequest $request)
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
        $user = $this->buildUserEntity($userId);

        $userSetting = $this->validateAccountRecoveryUserSetting($userId);
        $user->set('account_recovery_user_setting', $userSetting);

        if ($userSetting->isApproved()) {
            $user->set('account_recovery_private_key', $this->validateAccountRecoveryPrivateKey($userId));
        }

        return $this->saveUserEntity($user, $saveOptions);
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
        $policy = $this->policy->policy;
        if ($policy === AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_DISABLED) {
            if (!$this->isPrivateKeyProvided() || $this->arePasswordsProvided()) {
                throw new BadRequestException(__('Account recovery is disabled. Too much data.'));
            }
        } elseif ($policy === AccountRecoveryOrganizationPolicy::ACCOUNT_RECOVERY_ORGANIZATION_POLICY_MANDATORY) {
            if (!($this->isPrivateKeyProvided() && $this->arePasswordsProvided())) {
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

        return $user;
    }

    /**
     * @param string $userId User ID
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryUserSetting
     */
    protected function validateAccountRecoveryUserSetting(string $userId): AccountRecoveryUserSetting
    {
        $status = $this->request->getData('account_recovery_user_setting.status');
        $setting = $this->AccountRecoveryUserSettings->newEntity([
            'status' => $status,
            'user_id' => $userId,
            'created_by' => $userId,
            'modified_by' => $userId,
        ]);

        if ($setting->hasErrors()) {
            if ($setting->getError('status')['inList'] ?? false) {
                $msg = $setting->getError('status')['inList'];
            } else {
                $msg = __('The account recovery user setting is not valid.');
            }
            throw new ValidationException(
                $msg,
                $setting,
                $this->AccountRecoveryUserSettings
            );
        }

        return $setting;
    }

    /**
     * @param string $userId User ID
     * @return \Passbolt\AccountRecovery\Model\Entity\AccountRecoveryPrivateKey
     */
    protected function validateAccountRecoveryPrivateKey(string $userId): AccountRecoveryPrivateKey
    {
        $data = $this->request
            ->getData('account_recovery_user_setting.account_recovery_private_key');
        $privateKeyPasswords = $this->request
            ->getData('account_recovery_user_setting.account_recovery_private_key_passwords');

        $privateKeyEntity = $this->AccountRecoveryPrivateKeys->buildAndValidateEntity(
            new UserAccessControl(Role::USER, $userId),
            $data,
            $privateKeyPasswords
        );

        if (!$this->AccountRecoveryPrivateKeys->checkRules($privateKeyEntity)) {
            $msg = __('Could not validate public key data.');
            throw new ValidationException($msg, $privateKeyEntity, $this->AccountRecoveryPrivateKeys);
        }

        return $privateKeyEntity;
    }
}
