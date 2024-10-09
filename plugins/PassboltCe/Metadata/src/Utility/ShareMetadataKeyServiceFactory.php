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
namespace Passbolt\Metadata\Utility;

use Cake\ORM\Locator\LocatorAwareTrait;
use Passbolt\Metadata\Service\MetadataKeyShareDefaultService;
use Passbolt\Metadata\Service\MetadataKeyShareNothingService;
use Passbolt\Metadata\Service\MetadataKeyShareServiceInterface;
use Passbolt\Metadata\Service\MetadataKeysSettingsGetService;

class ShareMetadataKeyServiceFactory
{
    use LocatorAwareTrait;

    /**
     * @return \Passbolt\Metadata\Service\MetadataKeyShareServiceInterface
     */
    public function get(): MetadataKeyShareServiceInterface
    {
        // Nothing to share
        $metadataKeysTable = $this->fetchTable('Passbolt/Metadata.MetadataKeys');
        $keyCount = $metadataKeysTable->find()->all()->count();
        if ($keyCount === 0) {
            return new MetadataKeyShareNothingService();
        }

        // Key is sharable by server directly
        $settings = (new MetadataKeysSettingsGetService())->getSettings();
        if (!$settings->isKeyShareZeroKnowledge()) {
            return new MetadataKeyShareDefaultService();
        } else {
            // Trigger an email prompt for an admin to login, and share it via the web extension
            return new MetadataKeyShareNothingService();
        }
    }
}
