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
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Form;

use App\Model\Validation\EmailValidationRule;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class AccountCreationForm extends Form
{
    /**
     * Account creation schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('first_name', 'string')
            ->addField('last_name', ['type' => 'string'])
            ->addField('username', ['type' => 'string']);
    }

    /**
     * Validation rules.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('first_name', 'create', __('A first name is required.'))
            ->notEmptyString('first_name', __('The first name should not be empty.'))
            ->utf8('first_name', __('The first name should be a valid BMP-UTF8 string.'))
            ->maxLength(
                'first_name',
                255,
                __('The first name length should be maximum {0} characters.', 255)
            );

        $validator
            ->requirePresence('last_name', 'create', __('A last name is required.'))
            ->notEmptyString('last_name', __('The last name should not be empty.'))
            ->utf8('last_name', __('The last name should be a valid BMP-UTF8 string.'))
            ->maxLength(
                'last_name',
                255,
                __('The last name length should be maximum {0} characters.', 255)
            );

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmptyString('username', __('The username should not be empty.'))
            ->maxLength(
                'username',
                255,
                __('The username length should be maximum 255 characters.')
            )
            ->add('username', 'email', new EmailValidationRule([
                'message' => __('The username should be a valid email address.'),
            ]));

        return $validator;
    }

    /**
     * Execute implementation.
     *
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data): bool
    {
        return true;
    }
}
