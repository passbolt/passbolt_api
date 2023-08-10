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
 * @since         3.6.0
 */
declare(strict_types=1);

namespace Passbolt\AccountRecovery\Notification;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class AccountRecoveryNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    use EmailNotificationSettingsDefinitionTrait;

    public const REQUEST_CREATE_ADMIN = 'send_accountRecovery_request_admin';
    public const REQUEST_CREATE_USER = 'send_accountRecovery_request_user';

    public const RESPONSE_APPROVED_USER = 'send_accountRecovery_response_user_approved';
    public const RESPONSE_REJECTED_USER = 'send_accountRecovery_response_user_rejected';
    public const RESPONSE_CREATED_ADMIN = 'send_accountRecovery_response_created_admin';
    public const RESPONSE_CREATED_ALL_ADMINS = 'send_accountRecovery_response_created_allAdmins';

    public const BAD_REQUEST_GUESSING = 'send_accountRecovery_request_guessing';
    public const POLICY_UPDATE = 'send_accountRecovery_policy_update';

    public const FIELDS = [
        self::REQUEST_CREATE_ADMIN,
        self::REQUEST_CREATE_USER,
        self::RESPONSE_APPROVED_USER,
        self::RESPONSE_REJECTED_USER,
        self::RESPONSE_CREATED_ADMIN,
        self::RESPONSE_CREATED_ALL_ADMINS,
        self::BAD_REQUEST_GUESSING,
        self::POLICY_UPDATE,
    ];

    /**
     * @param \Cake\Form\Schema $schema An instance of schema
     * @return \Cake\Form\Schema
     */
    public function buildSchema(Schema $schema)
    {
        foreach (static::FIELDS as $fieldName) {
            $schema->addField($fieldName, ['type' => 'boolean', 'default' => true]);
        }

        return $schema;
    }

    /**
     * @param \Cake\Validation\Validator $validator An instance of validator
     * @return \Cake\Validation\Validator
     */
    public function buildValidator(Validator $validator)
    {
        foreach (static::FIELDS as $fieldName) {
            $validator->boolean($fieldName, __('An email notification setting should be a boolean.'));
        }

        return $validator;
    }
}
