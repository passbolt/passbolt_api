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
 * @since         3.0.0
 */
namespace Passbolt\Sso\Error\Exception;

use Cake\Core\Exception\CakeException;
use Cake\Log\Log;
use Throwable;

class AzureException extends CakeException
{
    /**
     * @var string $error
     */
    protected $error;

    /**
     * @var string $errorDescription
     */
    protected $errorDescription;

    /**
     * Constructor.
     *
     * Allows you to create exceptions that are treated as framework errors and disabled
     * when debug mode is off.
     *
     * @param string $error error code
     * @param string $errorDescription error message
     *   that are made available in the view, and sprintf()'d into Exception::$_messageTemplate
     * @param int|null $code The error code
     * @param \Throwable|null $previous the previous exception.
     */
    public function __construct(string $error, string $errorDescription, ?int $code = null, ?Throwable $previous = null)
    {
        $this->error = $error;
        $this->errorDescription = $errorDescription;

        if (!in_array($error, $this->allowedErrors())) {
            Log::error('Unkown Azure error:' . $error);
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
     * Return error message from Azure (non translated)
     *
     * @return string
     */
    public function getErrorDescription(): string
    {
        return $this->errorDescription;
    }

    /**
     * Ref. https://learn.microsoft.com/en-us/azure/active-directory/develop/v2-oauth2-auth-code-flow#error-codes-for-authorization-endpoint-errors
     *
     * @return string[]
     */
    public static function allowedErrors(): array
    {
        return [
            /*
             * invalid_request
             * Protocol error like a missing required parameter. Fix and resubmit the request. This development error
             * should be caught during application testing.
             */
            'invalid_request',

            /*
             * unauthorized_client
             * The client application can't request an authorization code. This error can occur when the client application
             * isn't registered in Azure AD or isn't added to the user's Azure AD tenant. The application can prompt the
             * user with instructions to install the application and add it to Azure AD
             */
            'unauthorized_client',

            /*
             * access_denied
             * The resource owner denied consent. The client application can notify the user that it can't proceed
             * unless the user consents.
             */
            'access_denied',

            /*
             * unsupported_response_type
             * The authorization server doesn't support the response type in the request.
             * Fix and resubmit the request. This development error should be caught during application testing.
             */
            'unsupported_response_type',

            /*
             * server_error The server encountered an unexpected error. Retry the request. These errors can result from
             * temporary conditions. The client application might explain to the user that its response is delayed because
             * of a temporary error.
             */
            'server_error',

            /*
             * temporarily_unavailable
             * The server is temporarily too busy to handle the request. Retry the request.
             * The client application might explain to the user that its response is delayed because of a temporary
             * condition.
             */
            'temporarily_unavailable',

            /*
             * invalid_resource
             * The target resource is invalid because it doesn't exist, Azure AD can't find it, or it's configured
             * incorrectly. This error indicates that the resource, if it exists, hasn't been configured in the
             * tenant. The application can prompt the user with instructions for installing the application and adding it
             * to Azure AD.
             */
            'invalid_resource',
        ];
    }
}
