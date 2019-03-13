<?php
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

use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class AccountCreationForm extends Form
{
    /**
     * Account creation schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('first_name', 'string')
            ->addField('last_name', ['type' => 'string'])
            ->addField('username', ['type' => 'string']);
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('first_name', 'create', __('A first name is required'))
            ->notEmpty('first_name')
            ->utf8('first_name', __('First name should be a valid utf8 string.'))
            ->maxLength('first_name', 255, __('The first name length should be maximum 254 characters.'));

        $validator
            ->requirePresence('last_name', 'create', __('A last name is required'))
            ->notEmpty('last_name')
            ->utf8('last_name', __('Last name should be a valid utf8 string.'))
            ->maxLength('last_name', 255, __('The last name length should be maximum 254 characters.'));

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->notEmpty('username', __('A username is required.'))
            ->maxLength('username', 255, __('The username length should be maximum {0} characters.', 255))
            ->email('username', Configure::read('passbolt.email.validate.mx'), __('The username should be a valid email address.'));

        return $validator;
    }

    /**
     * Execute implementation.
     * @param array $data form data
     * @return bool
     */
    protected function _execute(array $data)
    {
        return true;
    }
}
