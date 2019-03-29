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

class EmailConfigurationForm extends Form
{
    /**
     * Email configuration schema.
     * @param Schema $schema schema
     * @return Schema
     */
    protected function _buildSchema(Schema $schema)
    {
        return $schema
            ->addField('sender_name', 'string')
            ->addField('sender_email', ['type' => 'string'])
            ->addField('host', ['type' => 'string'])
            ->addField('tls', ['type' => 'integer'])
            ->addField('port', ['type' => 'string'])
            ->addField('username', ['type' => 'string'])
            ->addField('password', ['type' => 'string'])
            ->addField('test_email_to', ['type' => 'string']);
    }

    /**
     * Validation rules.
     * @param Validator $validator validator
     * @return Validator
     */
    protected function _buildValidator(Validator $validator)
    {
        $validator
            ->requirePresence('sender_name', 'create', __('A sender name is required.'))
            ->notEmpty('sender_name', __('A sender name is required.'))
            ->utf8('sender_name', __('The sender name is not a valid utf8 string.'));

        $validator
            ->requirePresence('sender_email', 'create', __('A sender email is required.'))
            ->notEmpty('sender_email', __('A sender email is required.'))
            ->utf8('sender_email', __('The sender email is not a valid utf8 string.'))
            ->email('sender_email', Configure::read('passbolt.email.validate.mx'), __('The sender email is not a valid email address'));

        $validator
            ->requirePresence('host', 'create', __('A host name is required.'))
            ->notEmpty('host', __('A host name is required.'))
            ->utf8('host', __('The host is not a valid utf8 string.'));

        $validator
            ->requirePresence('tls', 'create', __('A TLS value is required.'))
            ->notEmpty('tls', __('A TLS value is required.'))
            ->boolean('tls');

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->notEmpty('port', __('A port number is required.'))
            ->numeric('port', __('Port number should be numeric'))
            ->range('port', [0, 65535], __('Port should be between 0 and 65535'));

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->allowEmpty('username')
            ->utf8('username', __('The username is not a valid utf8 string.'));

        $validator
            ->requirePresence('password', 'create', __('A password is required.'))
            ->allowEmpty('password')
            ->utf8('password', __('The host is not a valid utf8 string.'));

        $validator
            ->email('test_email_to', Configure::read('passbolt.email.validate.mx'), __('The test email should be a valid email address.'));

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
