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
 * @since         4.2.0
 */
namespace Passbolt\PasswordPolicies\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\PasswordPolicies\Model\Dto\PasswordPoliciesSettingsDto;
use Passbolt\PasswordPolicies\Validation\PassphraseGeneratorSettingsValidator;
use Passbolt\PasswordPolicies\Validation\PasswordGeneratorSettingsValidator;

class PasswordPoliciesSettingsForm extends Form
{
    /**
     * The list of password generator types.
     *
     * @var array
     */
    public const PASSWORD_GENERATORS = [
        PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSWORD,
        PasswordPoliciesSettingsDto::PASSWORD_GENERATOR_PASSPHRASE,
    ];

    /**
     * @inheritDoc
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('default_generator', 'string')
            ->addField('password_generator_settings', 'array')
            ->addField('passphrase_generator_settings', 'array')
            ->addField('external_dictionary_check', 'boolean');
    }

    /**
     * @inheritDoc
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence(
                'default_generator',
                true,
                __('The default generator is required.')
            )
            ->inList(
                'default_generator',
                self::PASSWORD_GENERATORS,
                __(
                    'The default generator should be one of the following: {0}.',
                    implode(', ', self::PASSWORD_GENERATORS)
                )
            );

        $validator
            ->requirePresence(
                'external_dictionary_check',
                true,
                __('The external dictionary check is required.')
            )
            ->boolean(
                'external_dictionary_check',
                __('The external dictionary check should be a boolean.')
            );

        $validator
            ->requirePresence(
                'password_generator_settings',
                true,
                __('The password generator settings is required.')
            )
            ->notEmptyArray('password_generator_settings', __('The password generator settings should not be empty.'))
            // Check at least one mask is selected.
            ->add('password_generator_settings', 'noMaskSelected', [
                'rule' => [PasswordGeneratorSettingsValidator::class, 'checkAtLeastOneMaskIsSelected'],
                'message' => __('The password generator settings should have at least one mask selected.'),
            ])
            ->addNested('password_generator_settings', new PasswordGeneratorSettingsValidator());

        $validator
            ->requirePresence(
                'passphrase_generator_settings',
                true,
                __('The passphrase generator settings is required.')
            )
            ->notEmptyArray(
                'passphrase_generator_settings',
                __('The passphrase generator settings should not be empty.')
            )
            ->addNested('passphrase_generator_settings', new PassphraseGeneratorSettingsValidator());

        return $validator;
    }
}
