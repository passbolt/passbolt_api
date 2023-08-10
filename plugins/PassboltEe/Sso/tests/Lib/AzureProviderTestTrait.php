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
namespace Passbolt\Sso\Test\Lib;

use Cake\Core\Configure;
use Cake\Routing\Router;
use Passbolt\Sso\Utility\Azure\Provider\AzureProvider;

trait AzureProviderTestTrait
{
    public function getDummyAzureProvider(): AzureProvider
    {
        return new AzureProvider([
            'clientId' => Configure::read('passbolt.selenium.sso.azure.clientId'),
            'clientSecret' => Configure::read('passbolt.selenium.sso.azure.secretId'),
            'redirectUri' => Router::url('/sso/azure/redirect', true),
            'tenant' => Configure::read('passbolt.selenium.sso.azure.tenantId'),
        ]);
    }
}
