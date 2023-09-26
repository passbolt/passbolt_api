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
 * @since         3.3.0
 */

namespace Passbolt\JwtAuthentication\Error\Exception\RefreshToken;

use Cake\Http\Exception\BadRequestException;
use Throwable;

/**
 * Exception raised when the refresh token is not associated to any user.
 */
class UserDeactivatedException extends BadRequestException
{
    /**
     * @inheritDoc
     */
    public function __construct(?string $message = null, ?int $code = null, ?Throwable $previous = null)
    {
        if (empty($message)) {
            $message = __('The user is not activated or disabled.');
        }
        parent::__construct($message, $code, $previous);
    }
}
