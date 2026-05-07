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
 * @since         5.11.0
 */
namespace Passbolt\Sso\Form;

use Cake\Validation\Validator;

class SsoSettingsPingOneDataForm extends SsoSettingsOAuth2DataForm
{
    /**
     * Supported PingOne regional auth domains.
     *
     * @see https://docs.pingidentity.com/pingone/introduction_to_pingone/p1_introduction.html
     */
    public const SUPPORTED_PINGONE_URLS = [
        'auth.pingone.com',
        'auth.pingone.eu',
        'auth.pingone.ca',
        'auth.pingone.asia',
        'auth.pingone.com.au',
        'auth.pingone.sg',
    ];

    /**
     * @inheritDoc
     */
    protected function getDataValidator(): Validator
    {
        $dataValidator = parent::getDataValidator();

        // Remove validations for fields that use static defaults (not user-configurable)
        $dataValidator->remove('openid_configuration_path');
        $dataValidator->remove('scope');

        // Override URL validation to restrict to PingOne domains
        $dataValidator->add('url', 'isPingOneUrl', [
            'rule' => function ($value) {
                $host = parse_url($value, PHP_URL_HOST);

                return in_array($host, self::SUPPORTED_PINGONE_URLS, true);
            },
            'message' => __('The URL must be a valid PingOne authentication domain.'),
        ]);

        $dataValidator
            ->requirePresence('environment_id', __('An environment id is required.'))
            ->notEmptyString('environment_id', __('The environment id should not be empty.'))
            ->uuid('environment_id', __('The environment id should be a valid UUID.'));

        $dataValidator
            ->notEmptyString('email_claim', __('The email claim should not be empty.'))
            ->maxLength('email_claim', 64, __('The email claim is too large.'));

        return $dataValidator;
    }

    /**
     * @inheritDoc
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
