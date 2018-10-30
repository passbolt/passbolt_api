<?php
/**
 * Passbolt ~ Open source password manager for teams
 * Copyright (c) Passbolt SARL (https://www.passbolt.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Passbolt SARL (https://www.passbolt.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.passbolt.com Passbolt(tm)
 * @since         2.0.0
 */
namespace Passbolt\WebInstaller\Form;

use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Utility\Hash;

use Cake\Validation\Validator;

class GpgKeyGenerateForm extends Form
{
    /**
     * GpgKey generate configuration schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('name', 'string')
            ->addField('email', ['type' => 'string'])
            ->addField('comment', ['type' => 'string']);
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('name', 'create', __('A key name is required.'))
            ->notEmpty('name', __('A key name is required.'))
            ->utf8('name', __('The key name is not a valid utf8 string.'));

        $validator
            ->requirePresence('email', 'create', __('A key email is required.'))
            ->notEmpty('email', __('A key email is required.'))
            ->utf8('email', __('The key email is not a valid utf8 string.'))
            ->email('email', Configure::read('passbolt.email.validate.mx'), __('The key email is not a valid email address'));

        $validator
            ->allowEmpty('comment')
            ->utf8('comment', __('The key comment is not a valid utf8 string.'));

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
