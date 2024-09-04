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

use Passbolt\Metadata\Service\MetadataKeyShareDefaultService;
use Passbolt\Metadata\Service\MetadataKeyShareServiceInterface;

class ShareMetadataKeyServiceFactory
{
    /**
     * @return \Passbolt\Metadata\Service\MetadataKeyShareServiceInterface
     */
    public function get(): MetadataKeyShareServiceInterface
    {
        // In the future we may check the settings to see which strategy to use
        // to onboard users and share the keys.
        // Stage 0 - Keys is shared by the server
        // Stage 1 - Depending on settings there may be another service with different security properties
        // For example:
        // - It could be triggering an email prompt for an admin to login, and share it via the web extension
        // - It could be a notification / action on mobile phone by an admin
        // - It could be sending a request to a 3rd party service in charge of this, etc.
        return new MetadataKeyShareDefaultService();
    }
}
