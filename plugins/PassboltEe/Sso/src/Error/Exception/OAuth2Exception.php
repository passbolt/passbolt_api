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
 * @since         4.4.0
 */
namespace Passbolt\Sso\Error\Exception;

use Cake\Core\Exception\CakeException;
use Cake\Log\Log;
use Throwable;

class OAuth2Exception extends CakeException
{
    /**
     * Error.
     *
     * @var string
     */
    protected $error;

    /**
     * Error description.
     *
     * @var string
     */
    protected $errorDescription;

    /**
     * @inheritDoc
     */
    public function __construct(string $error, string $errorDescription, ?int $code = null, ?Throwable $previous = null)
    {
        $this->error = $error;
        $this->errorDescription = $errorDescription;

        if (!in_array($error, $this->allowedErrors())) {
            Log::error('Unkown OAuth2 error:' . $error);
        }

        parent::__construct($errorDescription, $code ?? $this->_defaultCode, $previous);
    }

    /**
     * Return error code
     *
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * Return error message from OAuth2 (non translated)
     *
     * @return string
     */
    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    /**
     * @see https://openid.net/specs/openid-connect-core-1_0.html#AuthError
     * @return string[]
     */
    public function allowedErrors(): array
    {
        return [
            'interaction_required',
            'login_required',
            'invalid_request_uri',
            'invalid_request_object',
            'request_not_supported',
            'request_uri_not_supported',
            'registration_not_supported',
        ];
    }
}
