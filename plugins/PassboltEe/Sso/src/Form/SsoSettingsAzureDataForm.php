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
 * @since         3.9.0
 */
namespace Passbolt\Sso\Form;

use App\Model\Validation\DateTime\IsDateInFutureValidationRule;
use Cake\I18n\FrozenTime;
use Cake\Validation\Validator;
use Passbolt\Sso\Model\Entity\SsoSetting;

class SsoSettingsAzureDataForm extends BaseSsoSettingsForm
{
    /**
     * Supported URLs
     *
     * @see https://learn.microsoft.com/en-us/azure/active-directory/develop/authentication-national-cloud#azure-ad-authentication-endpoints
     */
    public const SUPPORTED_AZURE_URLS = [
        // Azure AD global service
        'https://login.microsoftonline.com',
        // Azure AD for US Government
        'https://login.microsoftonline.us',
        // Azure AD China
        'https://login.partner.microsoftonline.cn',
    ];

    /**
     * Prompt options.
     */
    public const PROMPT_LOGIN = 'login';
    public const PROMPT_NONE = 'none';

    /**
     * Supported prompt values. Currently, we only accept "login" or "none".
     *
     * @link https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-protocols-oidc#send-the-sign-in-request
     */
    public const SUPPORTED_PROMPT_VALUES = [self::PROMPT_LOGIN, self::PROMPT_NONE];

    /**
     * Supported email claim aliases.
     */
    public const SUPPORTED_EMAIL_CLAIM_ALIASES = [
        SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_EMAIL,
        SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_PREFERRED_USERNAME,
        SsoSetting::AZURE_EMAIL_CLAIM_ALIAS_UPN,
    ];

    /**
     * @inheritDoc
     */
    protected function getDataValidator(): Validator
    {
        $dataValidator = new Validator();

        $dataValidator
            ->requirePresence('url', __('A URL is required.'))
            ->notEmptyString('url', __('The URL should not be empty.'))
            ->maxLength('url', 64)
            ->inList('url', self::SUPPORTED_AZURE_URLS, __('The URL is not supported.'));

        $dataValidator
            ->requirePresence('tenant_id', __('A tenant id is required.'))
            ->notEmptyString('tenant_id', __('The tenant id should not be empty.'))
            ->uuid('tenant_id', __('The tenant id should be a valid UUID.'));

        $dataValidator
            ->requirePresence('client_id', __('A client id is required.'))
            ->notEmptyString('client_id', __('The client id should not be empty.'))
            ->uuid('client_id', __('The client id should be a valid UUID.'));

        $dataValidator
            ->requirePresence('client_secret', __('A client secret is required.'))
            ->notEmptyString('client_secret', __('The client secret should not be empty.'))
            ->ascii('client_secret', __('The client secret should be a valid string.'))
            ->maxLength('client_secret', 256, __('The client secret is too large.'));

        $dataValidator
            ->requirePresence('client_secret_expiry', __('A client secret expiry is required.'))
            ->dateTime('client_secret_expiry', ['ymd'], __('The expiry should be a valid date.'))
            ->notEmptyDateTime('client_secret_expiry', __('The expiry should not be empty.'))
            ->add('client_secret_expiry', 'custom', new IsDateInFutureValidationRule());

        $dataValidator
            ->notEmptyString('prompt', __('The prompt should not be empty.'))
            ->inList(
                'prompt',
                self::SUPPORTED_PROMPT_VALUES,
                __('The prompt should be one of the following: {0}.', implode(', ', self::SUPPORTED_PROMPT_VALUES))
            );

        $dataValidator
            ->notEmptyString('email_claim', __('The email claim should not be empty.'))
            ->inList(
                'email_claim',
                self::SUPPORTED_EMAIL_CLAIM_ALIASES,
                __(
                    'The email claim should be one of the following: {0}.',
                    implode(', ', self::SUPPORTED_EMAIL_CLAIM_ALIASES)
                )
            );

        return $dataValidator;
    }

    /**
     * Execute the form if it is valid.
     *
     * First validates the form, then calls the `_execute()` hook method.
     * This hook method can be implemented in subclasses to perform
     * the action of the form. This may be sending email, interacting
     * with a remote API, or anything else you may need.
     *
     * @param array $data Form data.
     * @param array $options unused.
     * @return bool False on validation failure, otherwise returns the
     *   result of the `_execute()` method.
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->massageData($data);

        return parent::execute($data);
    }

    /**
     * Pre-format the data to facilitate data entry
     *
     * @param array $data user provided input
     * @return array
     */
    protected function massageData(array $data): array
    {
        // TODO provider specific spa?
        if (isset($data['data']['url']) && is_string($data['data']['url'])) {
            // Trim unwanted slashes
            $data['data']['url'] = rtrim($data['data']['url'], '/');

            // add protocol if missing
            if (substr($data['data']['url'], 0, 4) !== 'http') {
                $data['data']['url'] = 'https://' . $data['data']['url'];
            }
        }

        if (isset($data['data']['client_secret_expiry']) && is_string($data['data']['client_secret_expiry'])) {
            try {
                $data['data']['client_secret_expiry'] = new FrozenTime($data['data']['client_secret_expiry']);
            } catch (\Exception $exception) {
                $data['data']['client_secret_expiry'] = null;
            }
        }

        return $data;
    }
}
