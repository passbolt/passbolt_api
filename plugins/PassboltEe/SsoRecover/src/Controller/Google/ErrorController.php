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
 * @since         4.0.0
 */

namespace Passbolt\SsoRecover\Controller\Google;

use Passbolt\Sso\Controller\SsoErrorController;

/**
 * @see \Passbolt\Sso\Controller\SsoErrorController For actual code.
 *
 * This is because CakePHP works on convention. When an error is thrown from prefix controller,
 * it needs to have ErrorController class present inside prefix folder.
 * @link https://book.cakephp.org/4/en/development/errors.html#custom-errorcontroller
 * @link https://github.com/cakephp/cakephp/issues/17025
 */
class ErrorController extends SsoErrorController
{
    // nothing to do here
}
