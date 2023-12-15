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

namespace Passbolt\Sso\Controller\Adfs;

use Passbolt\Sso\Controller\OAuth2\SsoOAuth2Stage1DryRunController;
use Passbolt\Sso\Service\Sso\Adfs\SsoAdfsService;

/**
 * @see \Passbolt\Sso\Controller\OAuth2\SsoOAuth2Stage1DryRunController For full code.
 */
class SsoAdfsStage1DryRunController extends SsoOAuth2Stage1DryRunController
{
    /**
     * Returns SSO service name that will be used to create the service instance.
     *
     * @return string
     */
    protected function getSsoServiceName(): string
    {
        return SsoAdfsService::class;
    }
}
