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
 * @since         5.17.0
 */
namespace App\Error\Exception;

class WebauthnRelyingPartyIdInvalidException extends CustomValidationException
{
    public const ERROR_KEY = 'invalidDomain';

    /**
     * @param string $message Human-readable error message.
     * @param array|null $errors Validation errors. Defaults to `['error' => ['invalidDomain' => $message]]`
     *                           so the standard Passbolt error envelope carries the specific key.
     */
    public function __construct(string $message, ?array $errors = null)
    {
        parent::__construct($message, $errors ?? ['error' => [self::ERROR_KEY => $message]]);
    }
}
