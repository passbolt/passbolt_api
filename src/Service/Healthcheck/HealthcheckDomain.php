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
 * @since         4.6.0
 */

namespace App\Service\Healthcheck;

use Cake\Utility\Inflector;

class HealthcheckDomain
{
    /**
     * Returns title to show it to the user for this domain health check.
     *
     * @param string $domain Domain to get title from.
     * @return string
     */
    public static function getTitle(string $domain): string
    {
        $domainTitleMapping = [
            'environment' => __('Environment'),
            'configFiles' => __('Config files'),
            'core' => __('Core config'),
        ];

        if (isset($domainTitleMapping[$domain])) {
            return $domainTitleMapping[$domain];
        }

        // If mapping not found, change it to humanize form programmatically
        return Inflector::humanize($domain);
    }
}
