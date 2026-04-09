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

use App\Model\Validation\EmailValidationRule;
use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\SmtpSettings\Model\Validation\SmtpSettingsClientValidationRule;
use Passbolt\SmtpSettings\Service\SmtpSettingsSendTestMailerService;

class EmailConfigurationForm extends Form
{
    public const ALLOWED_AUTH_METHODS = [
        'none',
        'username_only',
        'username_and_password',
        'oauth2_client_credentials',
    ];

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
            ->addField('client', ['type' => 'string'])
            ->addField('username', ['type' => 'string'])
            ->addField('password', ['type' => 'string'])
            ->addField('email_test_to', ['type' => 'string'])
            // OAuth2 fields
            ->addField('tenant_id', ['type' => 'string'])
            ->addField('client_id', ['type' => 'string'])
            ->addField('client_secret', ['type' => 'string'])
            ->addField('oauth_username', ['type' => 'string']);
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
            ->allowEmptyString('client')
            ->add('client', 'isClientValid', new SmtpSettingsClientValidationRule());

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
            ->add('email_test_to', 'email', new EmailValidationRule([
                'message' => __('The test email should be a valid email address.'),
            ]));

        $validator
            ->allowEmptyString('tenant_id')
            ->uuid('tenant_id', __('The tenant ID should be a valid UUID.'));

        $validator
            ->allowEmptyString('client_id')
            ->uuid('client_id', __('The client ID should be a valid UUID.'));

        $validator
            ->allowEmptyString('client_secret')
            ->utf8('client_secret', __('The client secret should be a valid BMP-UTF8 string.'))
            ->maxLength('client_secret', 256, __('The client secret should not exceed 256 characters.'));

        $validator
            ->allowEmptyString('oauth_username')
            ->add('oauth_username', 'email', new EmailValidationRule([
                'message' => __('The OAuth2 username should be a valid email address.'),
            ]))
            ->maxLength('oauth_username', 256, __('The OAuth2 username should not exceed 256 characters.'));

        return $validator;
    }

    /**
     * @param \Cake\Validation\Validator $validator The validator class object.
     * @return \Cake\Validation\Validator
     */
    public function validationWebInstaller(Validator $validator): Validator
    {
        $this->validationDefault($validator);

        $validator
            ->requirePresence('authentication_method', true, __('The authentication method is required.'))
            ->inList('authentication_method', self::ALLOWED_AUTH_METHODS, __(
                'The authentication method should be one of the following: {0}.',
                implode(', ', self::ALLOWED_AUTH_METHODS)
            ));

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

        $validator->add('sender_email', 'email', new EmailValidationRule([
            'message' => __('The sender email should be a valid email address.'),
        ]));

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
            SmtpSettingsSendTestMailerService::EMAIL_TEST_TO,
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
        $data = $this->setClient($data);
        $data = $this->filterUsernameAndPassword($data);
        $data = $this->filterOauth2Fields($data);

        // If OAuth2 auth method, upgrade the validator with required-field rules
        if (($data['authentication_method'] ?? null) === 'oauth2_client_credentials') {
            $validatorName = $options['validate'] ?? 'default';
            $validator = $this->getValidator($validatorName);
            $this->addOauth2ClientCredentialsValidation($validator);
        }

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

    /**
     * In order to avoid empty strings passed to client,
     * we ensure that the client is well set to null is empty
     *
     * @param array $data Form data
     * @return array
     */
    protected function setClient(array $data): array
    {
        if (!isset($data['client']) || empty($data['client'])) {
            $data['client'] = null;
        }

        return $data;
    }

    /**
     * @param array $data The data to filter.
     * @return array
     */
    private function filterUsernameAndPassword(array $data): array
    {
        if (!isset($data['authentication_method'])) {
            return $data;
        }

        if ($data['authentication_method'] === 'none') {
            $data['username'] = $data['username'] === '' ? null : $data['username'];
            $data['password'] = $data['password'] === '' ? null : $data['password'];
        } elseif ($data['authentication_method'] === 'username_only') {
            $data['password'] = $data['password'] === '' ? null : $data['password'];
        }

        return $data;
    }

    /**
     * When authentication method is oauth2_client_credentials,
     * nullify legacy username/password fields.
     * When authentication method is set but NOT oauth2, nullify OAuth2 fields.
     *
     * @param array $data The data to filter.
     * @return array
     */
    private function filterOauth2Fields(array $data): array
    {
        if (!isset($data['authentication_method'])) {
            return $data;
        }

        if ($data['authentication_method'] === 'oauth2_client_credentials') {
            $data['username'] = null;
            $data['password'] = null;
        } else {
            $data['tenant_id'] = null;
            $data['client_id'] = null;
            $data['client_secret'] = null;
            $data['oauth_username'] = null;
        }

        return $data;
    }

    /**
     * Add required-field validation rules for OAuth2 client credentials fields.
     * Called from execute() to enforce stricter rules when OAuth2 is the auth method.
     *
     * @param \Cake\Validation\Validator $validator validator
     * @return \Cake\Validation\Validator
     */
    private function addOauth2ClientCredentialsValidation(Validator $validator): Validator
    {
        // Only upgrade to required + non-empty; format rules (uuid, email, maxLength, utf8)
        // are already set in validationDefault().
        $validator
            ->requirePresence('tenant_id', true, __('The tenant ID is required.'))
            ->notEmptyString('tenant_id', __('The tenant ID should not be empty.'));

        $validator
            ->requirePresence('client_id', true, __('The client ID is required.'))
            ->notEmptyString('client_id', __('The client ID should not be empty.'));

        $validator
            ->requirePresence('client_secret', true, __('The client secret is required.'))
            ->notEmptyString('client_secret', __('The client secret should not be empty.'));

        $validator
            ->requirePresence('oauth_username', true, __('The OAuth2 username is required.'))
            ->notEmptyString('oauth_username', __('The OAuth2 username should not be empty.'));

        return $validator;
    }
}
