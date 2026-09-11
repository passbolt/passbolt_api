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
 * @since         5.13.0
 */
namespace Passbolt\DirectorySync\Service\Edition;

use App\Model\Entity\OrganizationSetting;
use App\Utility\UuidFactory;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\DirectorySync\Utility\DirectoryOrgSettings;
use Passbolt\Edition\Service\Cleanup\EditionDowngradeCleanupServiceInterface;

/**
 * Wipes DirectorySync PRO data on edition downgrade.
 */
final class DirectorySyncDowngradeCleanupService implements EditionDowngradeCleanupServiceInterface
{
    use LocatorAwareTrait;

    /**
     * Tables owned by the DirectorySync plugin. Ordered child → parent.
     */
    private const TABLES = [
        'directory_reports_items',
        'directory_reports',
        'directory_relations',
        'directory_ignore',
        'directory_entries',
    ];

    /**
     * @inheritDoc
     */
    public function cleanup(): void
    {
        /** @var \Cake\Database\Connection $connection */
        $connection = ConnectionManager::get('default');
        foreach (self::TABLES as $table) {
            $connection->delete($table);
        }

        // DirectorySync writes its setting through the base OrganizationSettingsTable,
        // which namespaces the property when computing property_id.
        $this->fetchTable('OrganizationSettings')->deleteAll([
            'property_id' => UuidFactory::uuid(
                OrganizationSetting::UUID_NAMESPACE . DirectoryOrgSettings::ORG_SETTINGS_PROPERTY,
            ),
        ]);
    }
}
