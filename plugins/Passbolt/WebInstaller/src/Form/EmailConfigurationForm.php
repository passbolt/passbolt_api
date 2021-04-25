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

use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class EmailConfigurationForm extends Form
{
    /**
     * Email configuration schema.
     *
     * @param \Cake\Form\Schema $schema schema
     * @return \Cake\Form\Schema
     */
    protected function _buildSchema(Schema $schema): Schema
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
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('sender_name', 'create', __('A sender name is required.'))
            ->notEmptyString('sender_name', __('The sender name should not be empty.'))
            ->utf8('sender_name', __('The sender name should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('sender_email', 'create', __('A sender email is required.'))
            ->notEmptyString('sender_email', __('The sender email should not be empty.'))
            ->utf8('sender_email', __('The sender email should be a valid BMP-UTF8 string.'))
            ->email(
                'sender_email',
                Configure::read('passbolt.email.validate.mx'),
                __('The sender email should be a valid email address.')
            );

        $validator
            ->requirePresence('host', 'create', __('A host name is required.'))
            ->notEmptyString('host', __('The host name should not be empty.'))
            ->utf8('host', __('The host name should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('tls', 'create', __('A TLS setting is required.'))
            ->boolean('tls', __('The TLS setting should be a valid boolean.'));

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->numeric('port', __('The port number should be numeric.'))
            ->range('port', [0, 65535], __('The port number should be between {0} and {1}.', '0', '65535'));

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->allowEmptyString('username')
            ->utf8('username', __('The username should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('password', 'create', __('A password is required.'))
            ->allowEmptyString('password')
            ->utf8('password', __('The password should be a valid BMP-UTF8 string.'));

        $validator
            ->email(
                'test_email_to',
                Configure::read('passbolt.email.validate.mx'),
                __('The test email should be a valid email address.')
            );

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
