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
 * @since         5.4.0
 */
namespace Passbolt\Metadata\Service;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UuidFactory;
use Cake\Core\Configure;
use Cake\Database\Expression\QueryExpression;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\ORM\TableRegistry;
use Passbolt\Metadata\Model\Dto\MetadataSettingsGetStartedDto;

class MetadataSettingsGetStartedService
{
    use LocatorAwareTrait;

    /**
     * @return \Passbolt\Metadata\Model\Dto\MetadataSettingsGetStartedDto
     */
    public function get(): MetadataSettingsGetStartedDto
    {
        // Set flag default value to true
        $flag = Configure::read('passbolt.plugins.metadata.enableForExistingInstances', true);
        if (!$flag) {
            return new MetadataSettingsGetStartedDto(false);
        }

        $metadataSettings = $this->getMetadataSettings();
        if (!empty($metadataSettings)) {
            return new MetadataSettingsGetStartedDto(false);
        }

        $metadataKeysQuery = $this->fetchTable('Passbolt/Metadata.MetadataKeys')->find();
        if ($metadataKeysQuery->count() !== 0) {
            return new MetadataSettingsGetStartedDto(false);
        }

        $metadataPrivateKeysQuery = $this->fetchTable('Passbolt/Metadata.MetadataPrivateKeys')->find();
        if ($metadataPrivateKeysQuery->count() !== 0) {
            return new MetadataSettingsGetStartedDto(false);
        }

        return new MetadataSettingsGetStartedDto(true);
    }

    /**
     * @return array
     * @throws \Exception
     */
    private function getMetadataSettings(): array
    {
        try {
            /** @var \Cake\ORM\Query\SelectQuery $query */
            $query = TableRegistry::getTableLocator()->get('OrganizationSettings')->find();

            $typesSettingProperty = OrganizationSetting::UUID_NAMESPACE . MetadataTypesSettingsGetService::ORG_SETTING_PROPERTY; // phpcs:ignore
            $keysSettingProperty = OrganizationSetting::UUID_NAMESPACE . MetadataKeysSettingsGetService::ORG_SETTING_PROPERTY; // phpcs:ignore

            return $query
                ->where([
                    $query->newExpr()->or(function (QueryExpression $or) use ($typesSettingProperty, $keysSettingProperty) { // phpcs:ignore

                        return $or
                            ->eq('property_id', UuidFactory::uuid($typesSettingProperty))
                            ->eq('property_id', UuidFactory::uuid($keysSettingProperty));
                    }),
                ])
                ->all()
                ->toArray();
        } catch (RecordNotFoundException) {
            return [];
        }
    }
}
