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
 * @since         2.13.0
 */

namespace Passbolt\EmailNotificationSettings\Utility;

use Cake\Event\EventListenerInterface;
use Cake\Form\Schema;
use Cake\Validation\Validator;

interface EmailNotificationSettingsDefinitionInterface extends EventListenerInterface
{
    /**
     * Allow to define new fields on the schema instance passed by the EmailNotificationSettingsForm
     * Use the default attribute from the field to add a default value.
     *
     * @param \Cake\Form\Schema $schema Schema instance
     * @return \Cake\Form\Schema
     */
    public function buildSchema(Schema $schema);

    /**
     * Allow to define new rules on the validator instance passed by the EmailNotificationSettingsForm
     *
     * @param \Cake\Validation\Validator $validator Validator instance
     * @return \Cake\Validation\Validator
     */
    public function buildValidator(Validator $validator);

    /**
     * @return \Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\ReadableEmailNotificationSettingsSourceInterface
     */
    public function getDefaultSettingsSource();
}
