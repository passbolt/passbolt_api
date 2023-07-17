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
namespace Passbolt\Sso\Error\Exception;

use Cake\Core\Exception\CakeException;
use Cake\Log\Log;
use Throwable;

class GoogleException extends CakeException
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
            Log::error('Unkown Google error:' . $error);
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
     * Return error message from Google (non translated)
     *
     * @return string
     */
    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    /**
     * @see https://support.google.com/accounts/answer/12917337?hl=en
     * @return string[]
     */
    public static function allowedErrors(): array
    {
        return [
            /**
             * You can't sign in to this app because it doesn't comply with Google's OAuth 2.0 policy.
             *
             * @see https://support.google.com/accounts/answer/12917337?hl=en#400origin&zippy=%2Caccess-denied%2Corigin-mismatch-or-redirect-uri-mismatch
             */
            'redirect_uri_mismatch',
            'origin_mismatch',

            /**
             * Access blocked: App sent an invalid request.
             *
             * @see https://support.google.com/accounts/answer/12917337?hl=en#400invalid&zippy=%2Caccess-denied
             */
            'invalid_request',

            /**
             * No registered origin.
             *
             * @see https://support.google.com/accounts/answer/12917337?hl=en#401&zippy=%2Caccess-denied
             */
            'invalid_client',

            /*
             * @see https://support.google.com/accounts/answer/12917337?hl=en#403access&zippy=%2Caccess-denied
             */
            'access_denied',
        ];
    }
}
