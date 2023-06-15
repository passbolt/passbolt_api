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
 * @since         4.1.0
 */

namespace Passbolt\EmailNotificationSettings\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Cake\I18n\FrozenTime;
use Passbolt\EmailNotificationSettings\Test\Lib\EmailNotificationSettingsTestTrait;
use Passbolt\EmailNotificationSettings\Utility\NotificationSettingsSource\DbEmailNotificationSettingsSource;

class EmailNotificationSettingFactory extends OrganizationSettingFactory
{
    use EmailNotificationSettingsTestTrait;

    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $property = OrganizationSetting::UUID_NAMESPACE . DbEmailNotificationSettingsSource::NAMESPACE;

        $this->patchData([
            'property' => $property,
            'property_id' => UuidFactory::uuid($property),
            'value' => json_encode(self::getDefaultEmailNotificationConfig()),
            'created' => FrozenTime::now(),
            'modified' => FrozenTime::now(),
            'created_by' => UuidFactory::uuid(),
            'modified_by' => UuidFactory::uuid(),
        ]);
    }
}
