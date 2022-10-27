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
 * @since         3.8.0
 */

namespace Passbolt\SmtpSettings\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Passbolt\SmtpSettings\Service\SmtpSettingsGetSettingsInDbService;

/**
 * SmtpSettingFactory
 */
class SmtpSettingFactory extends OrganizationSettingFactory
{
    /**
     * Defines the factory's default values. This is useful for
     * not nullable fields. You may use methods of the present factory here too.
     *
     * @return void
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $property = OrganizationSetting::UUID_NAMESPACE . SmtpSettingsGetSettingsInDbService::SMTP_SETTINGS_PROPERTY_NAME;
        $this->patchData([
            'property' => $property,
            'property_id' => UuidFactory::uuid($property),
        ]);
    }
}
