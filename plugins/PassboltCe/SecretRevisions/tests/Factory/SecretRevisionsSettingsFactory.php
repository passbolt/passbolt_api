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
 * @since         5.7.0
 */
namespace Passbolt\SecretRevisions\Test\Factory;

use App\Model\Entity\OrganizationSetting;
use App\Test\Factory\OrganizationSettingFactory;
use App\Utility\UuidFactory;
use Passbolt\SecretRevisions\Service\SecretRevisionsSettingsSetService;

/**
 * SecretRevisionsSettingsFactory
 */
class SecretRevisionsSettingsFactory extends OrganizationSettingFactory
{
    /**
     * @inheritDoc
     */
    protected function setDefaultTemplate(): void
    {
        parent::setDefaultTemplate();

        $this->patchData([
            'property' => SecretRevisionsSettingsSetService::ORG_SETTING_PROPERTY,
            'property_id' => UuidFactory::uuid(OrganizationSetting::UUID_NAMESPACE . SecretRevisionsSettingsSetService::ORG_SETTING_PROPERTY),
            'value' => json_encode(self::getDefaultData()),
        ]);
    }

    /**
     * @return array
     */
    public static function getDefaultData(): array
    {
        return [
            'max_revisions' => 1,
            'allow_sharing_revisions' => false,
        ];
    }
}
