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
 * @since         5.11.0
 */

namespace Passbolt\Sso\Test\TestCase\Controller;

use Cake\TestSuite\TestCase;
use Passbolt\Sso\Controller\SsoErrorController;

/**
 * Regression test: every prefix directory under the Sso plugin's Controller folder
 * that contains a Stage2 (redirect) or Success controller MUST have an ErrorController
 * extending SsoErrorController. Without it, CakePHP's error handling won't render the
 * SSO error template for HTML error responses.
 *
 * @link https://book.cakephp.org/5.x/development/errors.html#custom-controller
 */
class SsoErrorControllerPresenceTest extends TestCase
{
    /**
     * Prefixes that render HTML error responses and require an ErrorController.
     * This list must be updated when a new SSO provider is added.
     *
     * @var array
     */
    private const SSO_PREFIXES_REQUIRING_ERROR_CONTROLLER = [
        'Azure',
        'Google',
        'OAuth2',
        'Adfs',
        'PingOne',
        'Success',
    ];

    /**
     * Verify each SSO prefix directory has an ErrorController extending SsoErrorController.
     *
     * @retrun void
     */
    public function testSsoErrorControllerPresence_EverySsoPrefixHasErrorController(): void
    {
        foreach (self::SSO_PREFIXES_REQUIRING_ERROR_CONTROLLER as $prefix) {
            /** @var class-string $fqcn */
            $fqcn = "Passbolt\\Sso\\Controller\\{$prefix}\\ErrorController";

            $this->assertTrue(
                class_exists($fqcn),
                "Missing ErrorController for prefix '{$prefix}': {$fqcn} does not exist. "
                . 'CakePHP requires an ErrorController in each prefix folder for proper error template rendering.'
            );

            $this->assertTrue(
                is_subclass_of($fqcn, SsoErrorController::class),
                "ErrorController for prefix '{$prefix}' must extend SsoErrorController."
            );
        }
    }
}
