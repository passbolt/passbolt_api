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
 * @since         3.9.0
 */

namespace Passbolt\Sso\Controller\Azure;

use App\Service\Cookie\AbstractSecureCookieService;
use App\Utility\Application\FeaturePluginAwareTrait;
use Passbolt\Sso\Controller\AbstractSso2Stage2Controller;
use Passbolt\Sso\Model\Dto\SsoSettingsDto;
use Passbolt\Sso\Model\Entity\SsoSetting;
use Passbolt\Sso\Service\Sso\AbstractSsoService;
use Passbolt\Sso\Service\Sso\Azure\SsoAzureService;

class SsoAzureStage2Controller extends AbstractSso2Stage2Controller
{
    use FeaturePluginAwareTrait;

    /**
     * @inheritDoc
     */
    protected function ssoServiceFactory(
        AbstractSecureCookieService $cookieService,
        SsoSettingsDto $settingsDto
    ): AbstractSsoService {
        return new SsoAzureService($cookieService, $settingsDto);
    }

    /**
     * @inheritDoc
     */
    protected function getProviderName(): string
    {
        return SsoSetting::PROVIDER_AZURE;
    }
}
