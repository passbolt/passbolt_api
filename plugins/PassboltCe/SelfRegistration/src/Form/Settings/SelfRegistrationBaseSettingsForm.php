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
 * @since         3.10.0
 */
namespace Passbolt\SelfRegistration\Form\Settings;

use Cake\Form\Form;
use Cake\Validation\Validator;

class SelfRegistrationBaseSettingsForm extends Form
{
    public const SELF_REGISTRATION_EMAIL_DOMAINS = 'email_domains';
    /**
     * Providers allowed for self registration
     */
    public const USER_SELF_REGISTRATION_PROVIDERS = [
        self::SELF_REGISTRATION_EMAIL_DOMAINS,
    ];

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->notEmptyString('provider', __('The provider should not be empty.'))
            ->inList(
                'provider',
                self::USER_SELF_REGISTRATION_PROVIDERS,
                __('The provider should be part of the supported list: {0}.', $this->getReadableListOfProviders())
            );

        $validator->notEmptyArray('data', __('The data should not be empty.'));

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $sanitizedData = [];
        $sanitizedData['provider'] = $data['provider'] ?? '';
        $sanitizedData['data'] = $data['data'] ?? [];

        return parent::execute($sanitizedData, $options);
    }

    /**
     * @return string
     */
    protected function getReadableListOfProviders(): string
    {
        return $this->implodeComerSeparated(self::USER_SELF_REGISTRATION_PROVIDERS);
    }

    /**
     * @param array $array The array to implode
     * @return string
     */
    protected function implodeComerSeparated(array $array): string
    {
        return implode(' ,', $array);
    }
}
