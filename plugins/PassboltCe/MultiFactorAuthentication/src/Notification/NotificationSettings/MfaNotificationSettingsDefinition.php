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
 * @since         5.17.0
 */

namespace Passbolt\MultiFactorAuthentication\Notification\NotificationSettings;

use Cake\Form\Schema;
use Cake\Validation\Validator;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionInterface;
use Passbolt\EmailNotificationSettings\Utility\EmailNotificationSettingsDefinitionTrait;

class MfaNotificationSettingsDefinition implements EmailNotificationSettingsDefinitionInterface
{
    use EmailNotificationSettingsDefinitionTrait;

    /**
     * @inheritDoc
     */
    public function buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField('send_mfa_disable_user', ['type' => 'boolean', 'default' => true])
            ->addField('send_mfa_disable_admin', ['type' => 'boolean', 'default' => true]);
    }

    /**
     * @inheritDoc
     */
    public function buildValidator(Validator $validator): Validator
    {
        return $validator
            ->boolean('send_mfa_disable_user', __('The send on MFA disabled user setting should be a boolean.'))
            ->boolean('send_mfa_disable_admin', __('The send on MFA disabled admin setting should be a boolean.'));
    }
}
