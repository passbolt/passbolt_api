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
namespace Passbolt\SmtpSettings\Form;

use Cake\Core\Configure;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestEmailService;

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
            ->addField('email_test_to', ['type' => 'string']);
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
            ->utf8('sender_email', __('The sender email should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('host', 'create', __('A host name is required.'))
            ->notEmptyString('host', __('The host name should not be empty.'))
            ->utf8('host', __('The host name should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('tls', 'create', __('A TLS setting is required.'))
            ->add('tls', 'tls', [
                'rule' => function ($value) {
                    return $value === true || $value == 1;
                },
                'message' => __('The TLS setting should be "true" or NULL.'),
            ])
            ->allowEmptyString('tls');

        $validator
            ->requirePresence('port', 'create', __('A port number is required.'))
            ->integer('port', __('The port number should be numeric.'))
            ->range('port', [1, 65535], __('The port number should be between {0} and {1}.', '1', '65535'));

        $validator
            ->requirePresence('username', 'create', __('A username is required.'))
            ->allowEmptyString('username')
            ->utf8('username', __('The username should be a valid BMP-UTF8 string.'));

        $validator
            ->requirePresence('password', 'create', __('A password is required.'))
            ->allowEmptyString('password')
            ->utf8('password', __('The password should be a valid BMP-UTF8 string.'));

        $validator
            ->allowEmptyString('email_test_to')
            ->email(
                'email_test_to',
                Configure::read('passbolt.email.validate.mx'),
                __('The test email should be a valid email address.')
            );

        return $validator;
    }

    /**
     * - Default validation rules
     * - Sender email should be a valid email
     *
     * @see SmtpSettingsSetService
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationUpdate(Validator $validator): Validator
    {
        $this->validationDefault($validator);

        $validator->email(
            'sender_email',
            Configure::read('passbolt.email.validate.mx'),
            __('The sender email should be a valid email address.')
        );

        return $validator;
    }

    /**
     * - Default validation rules
     * - Sender email should be a valid email
     * - Test email recipient should be a valid email
     *
     * @see SmtpSettingsSetService
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    public function validationSendTestEmail(Validator $validator): Validator
    {
        $this->validationUpdate($validator);

        $validator->requirePresence(
            SmtpSettingsSendTestEmailService::EMAIL_TEST_TO,
            'create',
            __('A test recipient is required.')
        );

        return $validator;
    }

    /**
     * @inheritDoc
     */
    public function execute(array $data, array $options = []): bool
    {
        $data = $this->mapTlsToTrueOrNull($data);

        return parent::execute($data, $options);
    }

    /**
     * Map the value of TLS to null if set to 0 or false
     *
     * @param array $data Form data
     * @return array
     */
    protected function mapTlsToTrueOrNull(array $data): array
    {
        if (!isset($data['tls'])) {
            return $data;
        }

        $tls = filter_var($data['tls'], FILTER_VALIDATE_BOOLEAN);
        $data['tls'] = $tls ? true : null;

        return $data;
    }
}
