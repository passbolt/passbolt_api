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
 * @since         4.10.0
 */

namespace Passbolt\Metadata\Model\Rule;

use Cake\ORM\Entity;
use Passbolt\Metadata\Service\MetadataTypesSettingsGetService;

class IsFolderV5ToV4DowngradeAllowedRule
{
    /**
     * Checks if v5 resource is updated to v4, fails if settings doesn't allow it.
     *
     * @param \Cake\ORM\Entity $entity The entity to check
     * @param array $options Options passed to the check
     * @return bool
     */
    public function __invoke(Entity $entity, array $options): bool
    {
        $name = $entity->get('name');
        $oldName = $entity->getOriginal('name');

        if (!self::isFolderDowngradeToV4($oldName, $name)) {
            return true;
        }

        $metadataTypesSettings = MetadataTypesSettingsGetService::getSettings();

        return $metadataTypesSettings->isV4DowngradeAllowed();
    }

    /**
     * @param string|null $oldName Old folder name.
     * @param string|null $name Update folder name.
     * @return bool
     * @throws \Exception
     */
    public static function isFolderDowngradeToV4(?string $oldName, ?string $name): bool
    {
        return is_null($oldName) && !is_null($name);
    }
}
