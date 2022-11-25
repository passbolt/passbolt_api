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
namespace Passbolt\SelfRegistration\Form;

use Cake\Form\Form;
use Cake\Validation\Validator;

abstract class SelfRegistrationBaseSettingsForm extends Form
{
    /**
     * Providers allowed for self registration
     */
    public const USER_SELF_REGISTRATION_PROVIDERS = [
        'email_domains',
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
            ->allowEmptyString(
                'provider',
                __('The provider should not be empty if data is set.'),
                function ($context): bool {
                    return empty($context['data']['data']);
                }
            )
            ->inList(
                'provider',
                self::USER_SELF_REGISTRATION_PROVIDERS,
                __('The provider should be one of the following: {0}.', $this->getReadableListOfProviders())
            );

        $validator->allowEmptyString(
            'data',
            __('The data should not be empty if the provider is set.'),
            function ($context): bool {
                return empty($context['data']['provider']);
            }
        );

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $sanitizedData = [];
        $sanitizedData['provider'] = $data['provider'];
        $sanitizedData['data'] = $data['data'];

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
