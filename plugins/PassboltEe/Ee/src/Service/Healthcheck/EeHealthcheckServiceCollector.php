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
 * @since         4.7.0
 */

namespace Passbolt\Ee\Service\Healthcheck;

use App\Service\Healthcheck\HealthcheckServiceCollector;

class EeHealthcheckServiceCollector extends HealthcheckServiceCollector
{
    /**
     * List of all EE specific available health check domains.
     */
    public const DOMAIN_SSO = 'sso';
    public const DOMAIN_DIRECTORY_SYNC = 'directorySync';

    /**
     * @return array
     */
    protected function getDomainTitleMapping(): array
    {
        $defaultTitleMapping = [
            self::DOMAIN_SSO => __('SSO'),
            self::DOMAIN_DIRECTORY_SYNC => __('Directory Sync'),
        ];

        return array_merge(parent::getDomainTitleMapping(), $defaultTitleMapping);
    }
}
